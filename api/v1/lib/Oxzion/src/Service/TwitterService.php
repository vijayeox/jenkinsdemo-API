<?php
require __DIR__ .'/autoload.php';

use Abraham\TwitterOAuth\TwitterOAuth;
define('CONSUMER_KEY', "uHlxjwo0pvIFa2cwTXvMPOei2");
define('CONSUMER_SECRET', "SbNft3DzVfITt6la8QIoEiuSf5ujKUrwn3LFjeg7yTmJ4c3fLJ");
define('OAUTH_CALLBACK', getenv('OAUTH_CALLBACK'));

class VA_ExternalLogic_TwitterService {
	private $connection;
	private $oauth_token;
	private $oauth_token_secret;
	public function __construct(){
		$this->connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
		$request_token = $this->connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));
		$this->oauth_token = $request_token['oauth_token'];
		$this->oauth_token_secret = $request_token['oauth_token_secret'];
		$url = $this->connection->url('oauth/authorize', array('oauth_token' => $this->oauth_token));
	}
	public function getTweets($tweetstring){
		$query = array(
			"q" => $tweetstring,
			"tweet_mode"=>"extended"
		);
		$i=0;
		$results = $this->connection->get('search/tweets', $query);
		foreach ($results->statuses as $result) {
			if(!$result->retweeted_status){
				if($result->entities->media){
					foreach ($result->entities->media as $key => $value) {
						if($value->type=='photo'){
							$tweetset[$i]["user"] = $result->user->screen_name;
							$tweetset[$i]["url"] = $value->media_url_https;
							$tweetset[$i]['text'] = $result->full_text;
							$tweetset[$i]['id'] = $result->id;
							$tweetset[$i]['type'] = 4;
							$i++;
						}
					}
				} else {
					// print_r($result);exit;
				}
			}
		}
		return $tweetset;
	}
}