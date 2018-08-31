<?php
namespace Email;
use Horde_Mail_Rfc822_List;
use Horde_Mail_Exception;
use Horde_Mail_Rfc822;

class EmailUtils
{
	/**
     * Wrapper around Horde_Mail_Rfc822#parseAddressList(). Ensures all
     * addresses have a default mail domain appended.
     *
     * @param mixed $in    The address string or an address list object.
     * @param array $opts  Options to override the default.
     *
     * @return Horde_Mail_Rfc822_List  See Horde_Mail_Rfc822#parseAddressList().
     *
     * @throws Horde_Mail_Exception, MailException
     */
    public static function parseAddressList($in, array $opts = array())
    {
        if ($in instanceof Horde_Mail_Rfc822_List) {
            $res = clone $in;
            foreach ($res->raw_addresses as $val) {
                if (is_null($val->host)) {
                	throw new MailException("Invalid Email Address :".$val);    
                }
            }
        } else {
            $rfc822 = new Horde_Mail_Rfc822();
            $res = $rfc822->parseAddressList($in, array_merge(array(
                'default_domain' => null,
                'validate' => false
            ), $opts));
        }

        $res->setIteratorFilter(Horde_Mail_Rfc822_List::HIDE_GROUPS);

        return $res;
    }
}
?>