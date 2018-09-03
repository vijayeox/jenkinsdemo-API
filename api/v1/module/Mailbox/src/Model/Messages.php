<?php

namespace Mailbox\Model;

class Messages{

    protected $data = array(
            'id' => NULL,
            'fromid' => NULL,
            'subject' => NULL,
            'message' => NULL,
            'replyid' => '0',
            'date_created' => NULL,
            'setflag' => '0',
            'tags' => NULL,
            'externalemail' => NULL,
            'ccemaillist' => NULL,
            'bccemaillist' => NULL,
            'old_message' => '0',
            'instanceformid' => NULL,
    );
    
            public function getId() {
                return $this->data['id'];
            }

            public function setId($id) {
                $this->data['id'] = $id;
            }
            public function getFromid() {
                return $this->data['fromid'];
            }

            public function setFromid($fromid) {
                $this->data['fromid'] = $fromid;
            }
            public function getSubject() {
                return $this->data['subject'];
            }

            public function setSubject($subject) {
                $this->data['subject'] = $subject;
            }
            public function getMessage() {
                return $this->data['message'];
            }

            public function setMessage($message) {
                $this->data['message'] = $message;
            }
            public function getReplyid() {
                return $this->data['replyid'];
            }

            public function setReplyid($replyid) {
                $this->data['replyid'] = $replyid;
            }
            public function getDateCreated() {
                return $this->data['date_created'];
            }

            public function setDateCreated($dateCreated) {
                $this->data['date_created'] = $dateCreated;
            }
            public function getSetflag() {
                return $this->data['setflag'];
            }

            public function setSetflag($setflag) {
                $this->data['setflag'] = $setflag;
            }
            public function getTags() {
                return $this->data['tags'];
            }

            public function setTags($tags) {
                $this->data['tags'] = $tags;
            }
            public function getExternalemail() {
                return $this->data['externalemail'];
            }

            public function setExternalemail($externalemail) {
                $this->data['externalemail'] = $externalemail;
            }
            public function getCcemaillist() {
                return $this->data['ccemaillist'];
            }

            public function setCcemaillist($ccemaillist) {
                $this->data['ccemaillist'] = $ccemaillist;
            }
            public function getBccemaillist() {
                return $this->data['bccemaillist'];
            }

            public function setBccemaillist($bccemaillist) {
                $this->data['bccemaillist'] = $bccemaillist;
            }
            public function getOldMessage() {
                return $this->data['old_message'];
            }

            public function setOldMessage($oldMessage) {
                $this->data['old_message'] = $oldMessage;
            }
            public function getInstanceformid() {
                return $this->data['instanceformid'];
            }

            public function setInstanceformid($instanceformid) {
                $this->data['instanceformid'] = $instanceformid;
            }

    public function exchangeArray($data) {
        foreach ($data as $key => $value)
        {
            if (!array_key_exists($key, $this->data)) {
                continue;//throw new \Exception("$key field does not exist in " . __CLASS__);
            }
            $this->data[$key] = $value;
        }
    }

    public function toArray() {
        return $this->data;
    }
}