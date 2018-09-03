<?php
	namespace Email;
	use Horde_Mime_Part;
	use Horde_String;
	use Horde_Imap_Client_Data_Fetch;

	class EmailMessage {

		const FLAG_ANSWERED = '\\answered';
	    const FLAG_DELETED = '\\deleted';
	    const FLAG_DRAFT = '\\draft';
	    const FLAG_FLAGGED = '\\flagged';
	    const FLAG_RECENT = '\\recent';
	    const FLAG_SEEN = '\\seen';
	    // RFC 3503 [3.3]
	    const FLAG_MDNSENT = '$mdnsent';
	    // RFC 5550 [2.8]
	    const FLAG_FORWARDED = '$forwarded';
	    // RFC 5788 registered keywords:
	    // http://www.ietf.org/mail-archive/web/morg/current/msg00441.html
	    const FLAG_JUNK = '$junk';
	    const FLAG_NOTJUNK = '$notjunk';

		/**
	     * The Horde_Mime_Part object for the message.
	     *
	     * @var Horde_Mime_Part
	     */
	    protected $_message;
	    protected $_parts;
	    public $_bodytext='';
	    /**
	     * $msg - the Horde_Mime_Part corresponding to the structure 
	     */
	    public function __construct($msg,$bodytext)
    	{
    		$this->_message = $msg;
    		$this->_bodytext = $bodytext;
    		$this->_parts = array('body' => array(),
							'attachments' => array());
			
    	}

    	/**
	     * Return the descriptive part label, making sure it is not empty.
	     *
	     * @param Horde_Mime_Part $part  The MIME Part object.
	     * @param boolean $use_descrip   Use description? If false, uses name.
	     *
	     * @return string  The part label (non-empty).
	     */
	    public function getPartName(Horde_Mime_Part $part, $use_descrip = false)
	    {
	        $name = $use_descrip
	            ? $part->getDescription(true)
	            : $part->getName(true);

	        if ($name) {
	            return $name;
	        }

	        switch ($ptype = $part->getPrimaryType()) {
	        case 'multipart':
	            if (($part->getSubType() == 'related') &&
	                ($view_id = $part->getMetaData('viewable_part')) &&
	                ($viewable = $this->getMimePart($view_id, array('nocontents' => true)))) {
	                return $this->getPartName($viewable, $use_descrip);
	            }
	            /* Fall-through. */

	        case 'application':
	        case 'model':
	            $ptype = $part->getSubType();
	            break;
	        }

	        switch ($ptype) {
	        case 'audio':
	            return _("Audio");

	        case 'image':
	            return _("Image");

	        case 'message':
	        case '':
	        case Horde_Mime_Part::UNKNOWN:
	            return _("Message");

	        case 'multipart':
	            return _("Multipart");

	        case 'text':
	            return _("Text");

	        case 'video':
	            return _("Video");

	        default:
	            // Attempt to translate this type, if possible. Odds are that
	            // it won't appear in the dictionary though.
	            return _(Horde_String::ucfirst($ptype));
	        }
	    }

		public function processParts(&$query, $parts = null, $level = 1,$peek=false)
	    { 
	    	if(!$parts && $level == 1){
	    		$parts = $this->_message->getParts();
	    	}
	    	foreach ($parts as $key => $value) {
	        		
	            if ($value->isAttachment() || 
	            		($value->getDisposition() == 'inline' && 
	            			!empty($value->getDispositionParameter('filename')))) {
	                $query->bodyPart($value->getMimeId(),array('peek'=>$peek));
	                $this->_parts['attachments'][] = $value;
	            
	            }
	            if($this->findBody($value)){
	            	$query->bodyPart($value->getMimeId(),array('peek'=>$peek));
	        		$this->_parts['body'][$value->getMimeId()] = $value;
	        	}
	        	
	        	$this->processParts($query, $value->getParts(), $level + 1,$peek);
	        }
	    }

	    public function updateContents(Horde_Imap_Client_Data_Fetch $result){
	    	foreach ($this->_parts['body'] as $key => $value) {
				$value->setContents($result->getBodyPart($value->getMimeId(), true), array("usestream"=>true));
			}
			foreach ($this->_parts['attachments'] as $key => $value) {
				$value->setContents($result->getBodyPart($value->getMimeId(), true), array("usestream"=>true));
			}
	    }
	    private function findBody(Horde_Mime_Part $part, $subtype = null){
	    	//print_r($part);

	    	$id = $part->getMimeId();
	 
            if (($part->getPrimaryType() == 'text') &&
                (is_null($subtype) || ($part->getSubType() == $subtype)) &&
                ($part->getDisposition() !== 'attachment')){
            	return $id;
            }

            return null;
	    }
    	
    	public function getBodyParts(){
    		return $this->_parts['body'];
    	}
    	public function getBodyText(){
    		return $this->_bodytext;
    	}

    	public function getAttachmentParts(){
    		return $this->_parts['attachments'];
    	}
	}
?>