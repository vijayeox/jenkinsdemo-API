<?php

require __DIR__ .'/autoload.php';
use Search\SolrSearchEngine;

class VA_ExternalLogic_SolrSearch{

	private $form;
	private $comment;
	private $message;
	private $ole;
	private $user;
	private $attachment;
	private $avatarobj;

	public function __construct(){
		$this->avatarobj = VA_Logic_Session::getAvatar();
		$this->form = 'INSTANCE_FORM';
		$this->comment= 'COMMENT';
		$this->message= 'MESSAGE';
		$this->ole= 'OLE';
		$this->user= 'USER';
		$this->attachment= 'ATTACHMENT';
		//currently these are the only entities that are stored
		// add more if required
	}


	public function searchWithParams($params){
		if(trim($params['searchval'])){
			$searchconfig['searchtext'] = '+'.implode(' +',explode(' ',trim($params['searchval'])));
			$searchconfig['searchtext'] = trim($params['searchval']);
		}else{
			return null; // make sure there is a search parameter always
		}
		$searchconfig['start'] = ($params['start'])?$params['start']:0;
		$searchconfig['rows'] = ($params['rows'])?$params['rows']:25;
		$searchconfig['toarray'] = ($params['toarray'])?$params['toarray']:0;
        $searchconfig['name'] = '"'.$this->avatarobj->firstname.' '.$this->avatarobj->lastname.'"';
        $searchconfig['orgid'] = $this->avatarobj->orgid;
        $searchconfig['avatargroups'] = $this->avatarobj->getGroupArray();
		$searchconfig['fields'] = '';

		// not putting these in a loop as each entity might have different specification.
		// pass the reference to the function to get refined data

		if($params['entity']){
			$result = $this->SearchEntities($params['entity'], $searchconfig);
		}else{
			$result['form'] = $this->SearchEntities('form', $searchconfig);
			$result['comment'] = $this->SearchEntities('comment', $searchconfig);
			$result['message'] = $this->SearchEntities('message', $searchconfig);
			$result['ole'] = $this->SearchEntities('ole', $searchconfig);
			$result['user'] = $this->SearchEntities('user', $searchconfig);
			$result['attachment'] = $this->SearchEntities('attachment', $searchconfig);
		}
		return $result;
	}

	public function SearchEntities($entity, &$searchconfig, $sortkey=null, $sortdir=null){
		list($entity_type, $type) = explode('_',$entity);
		$filterquery = array('entity_type' => $this->$entity_type);
		$filterstring = '';
		// Move these cases to different functions if they start getting complicated
		// add fields if there is a field which has lot of characters in it, remove it when retrieving them
		switch ($entity) {
			case 'form':
			case 'comment':
				$filterquery[] = array('ownergroup_id'=>implode(' OR ',$searchconfig['avatargroups']),
				                    	'assignedgroup_id'=>implode(' OR ',$searchconfig['avatargroups']));
				break;
			case 'message':
				$filterquery['recipient_list'] = $searchconfig['name'];
				break;
			case 'message_sent':
				$filterquery['from_user'] = $searchconfig['name'];
				break;
			case 'ole':
				$filterquery['group_id'] = implode(' ',$searchconfig['avatargroups']);// group filter
				break;
			case 'user':
				$filterquery['status'] = 'Active';
				$filterquery['role_s'] = 'employee';
				break;
			case 'attachment':
				$filterquery[] = array(
					'ownergroup_id'=>implode(' OR ',$searchconfig['avatargroups']),
				                    	'assignedgroup_id'=>implode(' OR ',$searchconfig['avatargroups']),
										'recipient_list'=> $searchconfig['name'] );
				break;
			default:
				break;
		}

		$filterquery['org_id'] = $searchconfig['orgid'];// org filter

		if($this->avatarobj->getFlagValue('onlyme') && ($entity_type == 'form' || $entity_type == 'comment')){  //only my data
			$filterquery[] = array('createdby_user'=> $searchconfig['name'],'assignedto_user'=> $searchconfig['name']);
		}
		$fields = $this->getFieldsForEntities($entity_type);
		$searchconfig['fields'] = ($params['fields'])?$params['fields']:$fields['fields'];
		$searchconfig['boostfield'] = $fields['boostfield'];

        array_walk($filterquery, function($value, $key) use(&$filterstring){
        	if(is_array($value)){
        		foreach($value as $k=>$v){
        			$filter .= $k.':('.$v.') OR ';
        		}
        		$keys = implode(' OR ',array_keys($value));
        		$filterstring .= $keys.'->'.rtrim($filter,' OR').',';
        	}else{
        		$filterstring .= $key.'->'.$key.':('.$value.'),';
        	}
        });

        // converting the array to the required format
        $searchconfig['filter'] = trim($filterstring,',');
		return array('data'=>$this->prepareQuery($searchconfig),'display_keys'=>$fields['display_keys'],'content_keys'=>$fields['content_keys']);
	}

	public function prepareQuery(&$searchconfig){

		$searchparams =array('start='.$searchconfig['start'],
				        	'rows='.$searchconfig['rows'],
				        	'fields='.$searchconfig['fields'].',score',
				        	'filterquery='.$searchconfig['filter'],
				        	'highlighting=*');

		$queryArray = $this->getOptions(implode('&',$searchparams));
		$queryArray['boostfield'] = $searchconfig['boostfield'];
		$result_obj = $this->search($searchconfig['searchtext'], $queryArray);
		$result = ($searchconfig['toarray'])?  VA_Logic_Utilities::object_to_array(json_decode($result_obj)) :$result_obj;

		foreach($result['highlighting'] as $key=>$value){
			unset($result['highlighting'][$key]);
			$newkey = explode('-',$key);
			$newvalue = array_values($value);
			$tempstring = strip_tags(trim(preg_replace('/\s\s+/', ' ', $newvalue[0][0])));
			if(count(explode('>',$tempstring))>1){ continue;}
			$tempstring = str_replace('|&|','<b class="highlight">',$tempstring);
			$tempstring = str_replace('|&*|','</b>',$tempstring);
			if($tempstring !='')$result['highlighting'][$newkey[1]]['data'] = $tempstring;
			if(count($value)- 1)$result['highlighting'][$newkey[1]]['count'] = count($value)-1;
		}
		return $result;
	}



	public 	function search($q, $options = array()){
		$searchEngine = new SolrSearchEngine();
		$result = $searchEngine->search($q, $options);
		return $result->getResponse()->getBody();
	}

	public function getOptions($data){
		$options = array('component' => array());
		$params = explode('&', $data);
		foreach ($params as $index => $val) {
			$iParam = explode('=', $val);
			$key = $iParam[0];
			$value = $iParam[1];

			switch ($key) {
				case 'fields':
					$options[$key]=array_map('trim', explode(',', $value));
					break;
				case 'sort':
				// not giving users option to sort as we are sorting by relevance score
				break;
				case 'filterquery':
					$temp = $this->extractMap($value);
					$fQuery = array();
					foreach ($temp as $k => $v) {
						$fQuery[$k] = array('query' => $v);
					}
					$options[$key] = $fQuery;
					break;
				case 'highlighting':
					$options['component'][$key] = array('field' => array_map('trim', explode(',', $value)));
					break;
				case 'grouping':
					$temp = $this->extractMap($value);
					$options['component'][$key] = array();
					if(isset($temp['fields'])){
						$options['component'][$key]['fields'] = explode(';', $temp['fields']);
					}
					if(isset($temp['queries'])){
						$options['component'][$key]['queries'] = explode(';', $temp['queries']);
					}
					break;
				default:
					$options[$key] = $value;
					break;
			}
		}
		return $options;
	}

	function extractMap($data, $separator = ','){
		$map = array();
		if(isset($data)){
			$val = explode($separator, $data);
			array_walk($val, function($a, $key) use(&$map){
				$ele = explode('->', $a);
				if(array_key_exists(1, $ele)){
					$map[$ele[0]] = $ele[1];
				}
			});
		}
		return $map;
	}

	public function getFieldsForEntities($entity){
		switch ($entity){
			case 'form':
				return array('fields'=>'entity_id,title_txt,date_created,assigned_group,owner_group,status,assignedto_user,createdby_user',
							'boostfield'=>'title_txt^4 desc_txt^0.1',
							'display_keys'=>array('id'=>'entity_id','text'=>'title_txt'),
							'content_keys'=>array(VA_Logic_Session::translate('MOD_SEARCH_ID')=>'entity_id',
												  VA_Logic_Session::translate('MOD_EMPLOYEE_ASSIGNED_GROUP')=>'assigned_group',
							                      VA_Logic_Session::translate('MOD_EMPLOYEE_AVATAR_MODULE_OWNER_GROUP_NEW')=>'owner_group',
							                      VA_Logic_Session::translate('MOD_EMPLOYEE_AVATAR_MODULE_STATUS')=>'status',
							                      VA_Logic_Session::translate('MOD_EMPLOYEE_AVATAR_MODULE_ASSIGNEDTO')=>'assignedto_user',
							                      VA_Logic_Session::translate('MOD_EMPLOYEE_AVATAR_MODULE_OWNER')=>'createdby_user'));
				break;
			case 'comment':
				return array('fields'=>'entity_id,instanceform_id,title_txt,comment_txt,date_created,commenting_user',
							'boostfield'=>'comment_txt^4 title_txt^4',
							'display_keys'=>array('id'=>'instanceform_id','text'=>'comment_txt'),
							'content_keys'=>array(VA_Logic_Session::translate('MOD_SEARCH_ID')=>'instanceform_id',
							                      VA_Logic_Session::translate('MOD_SEARCH_TITLE')=>'title_txt',
							                      VA_Logic_Session::translate('MOD_SEARCH_COMMENTING_USER')=>'commenting_user',
							                      VA_Logic_Session::translate('MOD_SEARCH_COMMENTED_CREATED')=>'date_created'));
				break;
			case 'message':
				return array('fields'=> 'entity_id,subject_txt,date_created,from_user',
							'boostfield'=>'subject_txt^4 message_txt^0.1',
							'display_keys'=>array('id'=>'entity_id','text'=>'subject_txt'),
							'content_keys'=>array(VA_Logic_Session::translate('MOD_EMPLOYEE_AVATAR_INBOX_FROM')=>'from_user',
							                      VA_Logic_Session::translate('MOD_SEARCH_MESSAGE_ON')=>'date_created'));
				break;
			case 'ole':
				return array('fields'=> 'entity_id,ole_txt,reply_id,group_id,date_created,ole_group,createdby_user',
							'boostfield'=>'ole_txt^4',
							'display_keys'=>array('id'=>'reply_id','text'=>'ole_txt','param1'=>'group_id'),
							'content_keys'=>array(VA_Logic_Session::translate('MOD_SEARCH_GROUP')=>'ole_group',
							                      VA_Logic_Session::translate('MOD_SEARCH_OLE_CREATED_BY')=>'createdby_user'));
				break;
			case 'user':
				return array('fields'=> 'entity_id,firstname_txt,lastname_txt',
							'boostfield'=>'firstname_txt^4',
							'display_keys'=>array('id'=>'entity_id','text'=>array('firstname_txt','lastname_txt')));
				break;
			case 'attachment':
				return array('fields'=>'entity_id,filename_txt,title_txt,instanceform_id,message_id,subject_txt,from_user,assigned_group,owner_group,assignedto_user,createdby_user',
							'boostfield'=>'filename_txt^4 attr_text^0.1 title_txt^4 subject_txt^4',
							'display_keys'=>array('text'=>'filename_txt'),
							'content_keys'=>array(VA_Logic_Session::translate('MOD_SEARCH_ID')=>array('instanceform_id','message_id'),
							                      VA_Logic_Session::translate('MOD_CLUBVA_EMPLOYEE_EMAIL_TITLE_SUBJECT')=>'subject_txt',
							                      VA_Logic_Session::translate('MOD_EMPLOYEE_AVATAR_INBOX_FROM')=>'from_user',
												  VA_Logic_Session::translate('MOD_EMPLOYEE_ASSIGNED_GROUP')=>'assigned_group',
							                      VA_Logic_Session::translate('MOD_EMPLOYEE_AVATAR_MODULE_OWNER_GROUP_NEW')=>'owner_group',
							                      VA_Logic_Session::translate('MOD_EMPLOYEE_AVATAR_MODULE_ASSIGNEDTO')=>'assignedto_user',
							                      VA_Logic_Session::translate('MOD_EMPLOYEE_AVATAR_MODULE_OWNER')=>'createdby_user'));
				break;
			default:
				return array('fields'=>'*');
				break;
		}
	}

	function stripUnclosedTags($input) {
    // Close <br> tags
		$buffer = str_replace("<br>", "<br/>", $input);
    // Find all matching open/close HTML tags (using recursion)
		$pattern = "/<([\w]+)([^>]*?) (([\s]*\/>)| (>((([^<]*?|<\!\-\-.*?\-\->)| (?R))*)<\/\\1[\s]*>))/ixsm";
		preg_match_all($pattern, $buffer, $matches, PREG_OFFSET_CAPTURE);
    // Mask matching open/close tag sequences in the buffer
		foreach ($matches[0] as $match) {
			$ofs = $match[1];
			for ($i = 0; $i < strlen($match[0]); $i++, $ofs++)
				$buffer[$ofs] = "#";
		}
    // Remove unclosed tags
		$buffer = preg_replace("/<.*$/", "", $buffer);
    // Put back content of matching open/close tag sequences to the buffer
		foreach ($matches[0] as $match) {
			$ofs = $match[1];
			for ($i = 0; $i < strlen($match[0]) && $ofs < strlen($buffer); $i++, $ofs++)
				$buffer[$ofs] = $match[0][$i];
		}
		return $buffer;
	}
}