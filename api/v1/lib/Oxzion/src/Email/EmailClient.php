<?php
namespace Oxzion\Email;

use Horde_Mail_Rfc822_List;
use Horde_Exception;
use Horde_Mime_Headers;
use Horde_Mime_Headers_Date;
use Horde_Mime_Headers_MessageId;
use Horde_Mime_Mdn;
use Horde_Text_Filter;
use Horde_Mime_Part;
use Horde_Text_Flowed;
use Horde_Mime_Magic;
use Horde_Mime_Headers_ContentParam;
use Horde_Url_Data;
use Horde_Mail_Transport_Smtphorde;
use Horde_Mime_Headers_UserAgent;
use Horde_Injector;
use Horde_Injector_TopLevel;
use Horde_Domhtml;
use DOMXPath;

class EmailClient
{
    /**
     * Builds and sends a MIME message.
     *
     * @param string $body                  The message body.
     * @param array attDetails			    array of items with following properties
     * 	- file :  Temporary file containing attachment contents
     *  - bytes : Size of data, in bytes.
     *  - filename : Filename of data
     *  - type : Mime type of data
     * @param array $header                 List of message headers.
     * @param array $smtpConfig				Config values for smtp
     *     - host: [*] (string) SMTP server host.
     *     - password: (string) Password to use for SMTP server authentication.
     *     - port: [*] (integer) SMTP server port.
     *     - secure: [*] (string) Use SSL or TLS to connect.
     *               Possible options:
     *                 - false (No encryption)
     *                 - 'ssl' (Auto-detect SSL version)
     *                 - 'sslv2' (Force SSL version 2)
     *                 - 'sslv3' (Force SSL version 3)
     *                 - 'tls' (TLS) [DEFAULT]
     *                 - 'tlsv1' (TLS direct version 1.x connection to server)
     *                 - true (Use TLS, if available)
     *     - username: (string) Username to use for SMTP server authentication.
     *		- token: (string) If set, will authenticate via the XOAUTH2
     * @param array $opts                   An array of options w/the
     *                                      following keys:
     *  - html: (boolean) Whether this is an HTML message.
     *          DEFAULT: false
     *  - priority: (string) The message priority ('high', 'normal', 'low').
     *  - save_sent: (boolean) Save sent mail? DEFAULT true
     *  - sent_mail: The sent-mail mailbox name (UTF-8). DEFAULT 'sent'
     *  - strip_attachments: (bool) Strip attachments from the message?
     *  - useragent: (string) The User-Agent string to use.
      *
     * @throws Horde_Exception
     * @throws MailException
     */
    public function buildAndSendMessage($body, $attDetails, $header, $smtpConfig, array $opts = array(), $draftid=null)
    {
        /* Set up defaults. */
        $opts = array_merge(array(
                'save_sent' => true,
                'sent_mail' => 'sent'
            ), $opts);
        /* We need at least one recipient. */
        $recip = $this->recipientList($header);
        if (!count($recip['list'])) {
            if ($recip['has_input']) {
                throw new MailException("Invalid e-mail address.");
            }
            throw new MailException("Need at least one message recipient.");
        }

        /* Initalize a header object for the outgoing message. */
        $headers = $this->_prepareHeaders($header, $opts);
        /* Add the 'User-Agent' header. */
        $headers->addHeaderOb(new Horde_Mime_Headers_UserAgent(
                null,
                empty($opts['useragent'])
                ? 'Oxzion Email Client'
                : $opts['useragent']
            ));

        $message = $this->_createMimeMessage($body, $attDetails, array(
                'html' => !empty($opts['html']),
                'recip' => $recip['list'],
            ));

        /* Send the messages out now. */
        $this->sendMessage($recip['list'], $headers, $message, $smtpConfig);
    }

    /**
     * Sends a message.
     *
     * @param Horde_Mail_Rfc822_List $email  The e-mail list to send to.
     * @param Horde_Mime_Headers $headers    The object holding this message's
     *                                       headers.
     * @param Horde_Mime_Part $message       The object that contains the text
     *                                       to send.
     * @param array $smtpConfig				Config values for smtp
     *     - host: [*] (string) SMTP server host.
     *     - password: (string) Password to use for SMTP server authentication.
     *     - port: [*] (integer) SMTP server port.
     *     - secure: [*] (string) Use SSL or TLS to connect.
     *               Possible options:
     *                 - false (No encryption)
     *                 - 'ssl' (Auto-detect SSL version)
     *                 - 'sslv2' (Force SSL version 2)
     *                 - 'sslv3' (Force SSL version 3)
     *                 - 'tls' (TLS) [DEFAULT]
     *                 - 'tlsv1' (TLS direct version 1.x connection to server)
     *                 - true (Use TLS, if available)
     *     - username: (string) Username to use for SMTP server authentication.
     *
     * @throws MailException
     */
    public function sendMessage(
            Horde_Mail_Rfc822_List $email,
            Horde_Mime_Headers $headers,
            Horde_Mime_Part $message,
            $smtpConfig
        ) {
        $smtpConfig = array_merge(
                array('timeout' => 30,
                'debug' => __DIR__.'/../../../../logs/smtp.log'),
                $smtpConfig
            );
        if ($smtpConfig['secure'] == 2) {
            $smtpConfig['secure'] = true;
        } elseif ($smtpConfig['secure'] == 1) {
            $smtpConfig['secure'] = 'ssl';
        }
        /* Fallback to UTF-8 (if replying, original message might be in
         * US-ASCII, for example, but To/Subject/Etc. may contain 8-bit
         * characters. */
        $message->setHeaderCharset('UTF-8');
        /* Remove Bcc header if it exists. */
        if (isset($headers['bcc'])) {
            $headers = clone $headers;
            unset($headers['bcc']);
        }
        try {
            $transport = $this->getsmtpTransport($smtpConfig);
            $message->send($email, $headers, $transport);
        } catch (Horde_Mime_Exception $e) {
            throw new MailException($e);
        }
    }
    /**
     * Create the base Horde_Mime_Part for sending.
     *
     * @param string $body                Message body.
     * @param array attDetails
     * 	- file :  Temporary file containing attachment contents
     *  - bytes : Size of data, in bytes.
     *  - filename : Filename of data
     *  - type : Mime type of data
     * @param array $options              Additional options:
     *   - html: (boolean) Is this a HTML message?
     *   - noattach: (boolean) Don't add attachment information.
     *   - recip: (Horde_Mail_Rfc822_List) The recipient list.
     *
     * @return Horde_Mime_Part  The base MIME part.
     *
     * @throws Horde_Exception
     * @throws MailException
     */
    protected function _createMimeMessage($body, $attDetails, array $options = array())
    {
        /* Get body text. */
        if (empty($options['html'])) {
            $body_html = null;
        } else {
            $tfilter = new Horde_Text_Filter(new Horde_Injector(new Horde_Injector_TopLevel()));

            $body_html = $tfilter->filter(
                        $body,
                        'Xss',
                        array(
                            'return_dom' => true,
                            'strip_style_attributes' => false
                            )
                    );
            $body_html_body = $body_html->getBody();

            $body = $tfilter->filter(
                        $body_html->returnHtml(),
                        'Html2text',
                        array(
                            'width' => 0
                            )
                    );
        }

        /* Set up the body part now. */
        $textBody = new Horde_Mime_Part();
        $textBody->setType('text/plain');
        $textBody->setCharset('utf-8');
        $textBody->setDisposition('inline');

        /* Send in flowed format. */
        // $flowed = new Horde_Text_Flowed($body, 'utf-8');
        // $flowed->setDelSp(true);
        // $textBody->setContentTypeParameter('format', 'flowed');
        // $textBody->setContentTypeParameter('DelSp', 'Yes');
        // $text_contents = $flowed->toFlowed();
        // $textBody->setContents($text_contents);

        /* Determine whether or not to send a multipart/alternative
         * message with an HTML part. */
        if (!empty($options['html'])) {
            $htmlBody = new Horde_Mime_Part();
            $htmlBody->setType('text/html');
            $htmlBody->setCharset('utf-8');
            $htmlBody->setDisposition('inline');
            $htmlBody->setDescription("HTML Message");
            $this->_cleanHtmlOutput($body_html);
            $to_add = $htmlBody;
            /* Now, all parts referred to in the HTML data have been added
             * to the attachment list. Convert to multipart/related if
             * this is the case. Exception: if text representation is empty,
             * just send HTML part. */
            // if (strlen(trim($text_contents))) {
            // 	$textpart = new Horde_Mime_Part();
            // 	$textpart->setType('multipart/alternative');
            // 	$textpart[] = $textBody;
            // 	$textpart[] = $to_add;
            // 	$textpart->setHeaderCharset('utf-8');
            // 	$textBody->setDescription("Plaintext Message");
            // } else {
            $textpart = $to_add;
            // }

            $htmlBody->setContents(
                    $tfilter->filter(
                        $body_html->returnHtml(array(
                            'charset' => 'utf-8',
                            'metacharset' => true
                            )),
                        'Cleanhtml',
                        array(
                            'charset' => 'utf-8'
                            )
                    )
                );
            $base = $textpart;
        } else {
            $base = $textpart = $textBody;
        }

        /* Add attachments. */
        $aparts = array();
        foreach ($attDetails as $key => $value) {
            $type = isset($value['type']) ? $value['type'] : null;
            $aparts[] = $this->_getAttachmentPart($value['file'], $value['bytes'], $value['filename'], $type);
        }
        if (!empty($aparts)) {
            if (is_null($base) && (count($aparts) === 1)) {
                /* If this is a single attachment with no text, the
                 * attachment IS the message. */
                $base = reset($aparts);
            } else {
                $base = new Horde_Mime_Part();
                $base->setType('multipart/mixed');
                if (!is_null($textpart)) {
                    $base[] = $textpart;
                }
                foreach ($aparts as $val) {
                    $base[] = $val;
                }
            }
        }

        /* If we reach this far with no base, we are sending a blank message.
         * Assume this is what the user wants. */
        if (is_null($base)) {
            $base = $textBody;
        }

        /* Flag this as the base part and rebuild MIME IDs. */
        $base->isBasePart(true);
        $base->buildMimeIds();

        return $base;
    }

    /**
     * Clean outgoing HTML (remove unexpected data URLs).
     *
     * @param Horde_Domhtml $html  The HTML data.
     */
    protected function _cleanHtmlOutput(Horde_Domhtml $html)
    {
        global $registry;

        $xpath = new DOMXPath($html->dom);

        foreach ($xpath->query('//*[@src]') as $node) {
            $src = $node->getAttribute('src');

            /* Check for attempts to sneak data URL information into the
             * output. */
            if (Horde_Url_Data::isData($src)) {
                $node->removeAttribute('src');
            }
        }
    }

    /**
     * Adds an attachment to the outgoing compose message.
     *
     * @param string $atc_file  Temporary file containing attachment contents.
     * @param integer $bytes    Size of data, in bytes.
     * @param string $filename  Filename of data.
     * @param string $type      MIME type of data.
     *
     * @return Horde_Mime_Part  Attachment object.
     * @throws MailException
     */
    protected function _getAttachmentPart($atc_file, $bytes, $filename, $type)
    {
        $apart = new Horde_Mime_Part();
        $apart->setBytes($bytes);
        if (strlen($filename)) {
            $apart->setName($filename);
            if ($type == 'application/octet-stream') {
                $type = Horde_Mime_Magic::filenameToMIME($filename, false);
            }
        }
        $apart->setType($type);
        if (($apart->getType() == 'application/octet-stream') ||
                ($apart->getPrimaryType() == 'text')) {
            $analyze = Horde_Mime_Magic::analyzeFile($atc_file, null, array(
                    'nostrip' => true
                    ));
            $apart->setCharset('UTF-8');

            if ($analyze) {
                $ctype = new Horde_Mime_Headers_ContentParam(
                        'Content-Type',
                        $analyze
                    );
                $apart->setType($ctype->value);
                if (isset($ctype->params['charset'])) {
                    $apart->setCharset($ctype->params['charset']);
                }
            }
        } else {
            $apart->setHeaderCharset('UTF-8');
        }

        $apart->setContents(fopen($atc_file, 'r'), array('stream' => true));
        return $apart;
    }
    /**
     * Cleans up and returns the recipient list. Method designed to parse
     * user entered data; does not encode/validate addresses.
     *
     * @param array $hdr  An array of MIME headers and/or address list
     *                    objects. Recipients will be extracted from the 'to',
     *                    'cc', and 'bcc' entries.
     *
     * @return array  An array with the following entries:
     *   - has_input: (boolean) True if at least one of the headers contains
     *                user input.
     *   - header: (array) Contains the cleaned up 'to', 'cc', and 'bcc'
     *             address list (Horde_Mail_Rfc822_List objects).
     *   - list: (Horde_Mail_Rfc822_List) Recipient addresses.
     */
    public function recipientList($hdr)
    {
        $addrlist = new Horde_Mail_Rfc822_List();
        $has_input = false;
        $header = array();

        foreach (array('to', 'cc', 'bcc') as $key) {
            if (isset($hdr[$key])) {
                $ob = EmailUtils::parseAddressList($hdr[$key]);
                if (count($ob)) {
                    $addrlist->add($ob);
                    $header[$key] = $ob;
                    $has_input = true;
                } else {
                    $header[$key] = null;
                }
            }
        }

        return array(
                'has_input' => $has_input,
                'header' => $header,
                'list' => $addrlist
                );
    }

    /**
     * Prepare header object with basic header fields and converts headers
     * to the current compose charset.
     *
     * @param array $headers  Array with 'from', 'to', 'cc', 'bcc', and
     *                        'subject' values.
     * @param array $opts     An array of options w/the following keys:
     *   - priority: (string) The message priority ('high', 'normal', 'low').
     *
     * @return Horde_Mime_Headers  Headers object with the appropriate headers
     *                             set.
     */
    protected function _prepareHeaders($headers, array $opts = array())
    {
        $ob = new Horde_Mime_Headers();

        $ob->addHeaderOb(Horde_Mime_Headers_Date::create());
        $ob->addHeaderOb(Horde_Mime_Headers_MessageId::create());

        $hdrs = array(
                'From' => 'from',
                'To' => 'to',
                'Cc' => 'cc',
                'Bcc' => 'bcc',
                'In-Reply-To' => 'in_reply_to',
                'References' => 'conversation_id',
                'Subject' => 'subject'
                );
        foreach ($hdrs as $key => $val) {
            if (isset($headers[$val]) && (is_object($headers[$val]) || strlen($headers[$val]))) {
                if ($key=='In-Reply-To'||$key=='References') {
                    $ob->addHeader($key, "<".$headers[$val].">");
                } else {
                    $ob->addHeader($key, $headers[$val]);
                }
            }
        }
        $from = $ob['from']->getAddressList(true)->first();
        if (is_null($from->host)) {
            throw new MailException("From Address is Invalid");
        }
        /* Add Reply-To header. Done after pre_sent hook since from address
         * could be change by hook and/or Reply-To was set by hook. */
        if (!empty($headers['replyto']) &&
                ($headers['replyto'] != $from->bare_address) &&
                !isset($ob['reply-to'])) {
            $ob->addHeader('Reply-To', $headers['replyto']);
        }
        /* Add priority header, if requested. */
        if (!empty($opts['priority'])) {
            switch ($opts['priority']) {
                    case 'high':
                    $ob->addHeader('Importance', 'High');
                    $ob->addHeader('X-Priority', '1 (Highest)');
                    break;

                    case 'low':
                    $ob->addHeader('Importance', 'Low');
                    $ob->addHeader('X-Priority', '5 (Lowest)');
                    break;
                }
        }

        return $ob;
    }

    private function getOAuth64($email, $accessToken)
    {
        return base64_encode("user=".$email."\001auth=Bearer ".$accessToken. "\001\001");
    }
    private function getsmtpTransport($smtpConfig)
    {
        if (isset($smtpConfig['token'])) {
            $smtpConfig['token'] = $this->getOAuth64($smtpConfig['username'], $smtpConfig['token']);
        }
        $transport = new Horde_Mail_Transport_Smtphorde($smtpConfig);
        return $transport;
    }
}
