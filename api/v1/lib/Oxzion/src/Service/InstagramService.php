<?php

namespace Oxzion\Service;
use ElasticSearch\ElasticClient;
use Oxzion\Db\Config;

class VA_ExternalLogic_InstagramService {
	private $connection;
	private $oauth_token;
	private $oauth_token_secret;
	private $client_id = 'c8094968acc741d6bbdb6f20c7bb46db';
	private $client_secret = '5e7c1a58d0be48cca7c3141b14db8d96';
	private $redirect_uri = 'http://localhost/Club2.6.2/public/employee/test/instaredirect';
	private $token = '340467677.c809496.1d3783c4f6e74e058f72e24e4fdafa4a';

	public function __construct(){
		$this->connection = new Instagram($this->client_id);
		$this->connection->setAccessToken($this->token);
	}
	public function getPosts($hashtag){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://www.instagram.com/explore/tags/".$hashtag."/?__a=1");
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$result = curl_exec($ch);
			if ($result === FALSE) {
				die('Curl failed: ' . curl_error($ch));
			}
			curl_close($ch);
			$result = json_decode($result,true);
			$instaset = array();
			foreach ($result['graphql']['hashtag']['edge_hashtag_to_media']['edges'] as $key => $post) {
				if(!$post['node']['is_video']){
					$instaset[$i]['user'] = '';
					$instaset[$i]["url"] = $post['node']['display_url'];
					$instaset[$i]['text'] = $post['node']['edge_media_to_caption']['edges'][0]['node']['text'];
					$instaset[$i]['id'] = $post['node']['id'];
					$instaset[$i]['type'] = 5;
					$i++;
				}
				// if($post['node']['is_video']){
				// 	$instaset[$i]['user'] = $post->user->username;
				// 	$instaset[$i]["url"] = $post['node']['display_url'];
				// 	$instaset[$i]['text'] = $post['node']['edge_media_to_caption']['edges'][0]['node']['text'];
				// 	$instaset[$i]['id'] = $post['node']['id'];
				// 	$instaset[$i]['type'] = 5;
				// 	$i++;
				// }
			}
			return $instaset;
	}
	// public function getPosts($hashtag){
	// 	$result = $this->connection->getTagMedia($hashtag,20);
	// 	$i=0;
	// 	$instaset = array();
	// 	foreach ($result->data as $key => $post) {
	// 		if($post->type=='image'){
	// 			$instaset[$i]['user'] = $post->user->username;
	// 			$instaset[$i]["url"] = $post->images->standard_resolution;
	// 			$instaset[$i]['text'] = $post->caption->text;
	// 			$instaset[$i]['id'] = $post->caption->id;
	// 			$instaset[$i]['type'] = 5;
	// 			$i++;
	// 		}
	// 		if($post->type=='video'){
	// 			$instaset[$i]['user'] = $post->user->username;
	// 			$instaset[$i]["url"] = $post->videos->standard_resolution->url;
	// 			$instaset[$i]['text'] = $post->caption->text;
	// 			$instaset[$i]['id'] = $post->caption->id;
	// 			$instaset[$i]['type'] = 5;
	// 			$i++;
	// 		}
	// 	}
	// 	return $instaset;
	// }
}