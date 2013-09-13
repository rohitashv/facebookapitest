<?php

require 'facebook-sdk/facebook.php';
class facebookAllInOne
{
	public $facebook;
	public $app_id;
	public $redirect_uri;
	public $user;
	public $access_token;
	public function __construct($app_id,$app_secret,$return_uri)
	{
		$this->facebook = new Facebook(array(
		  'appId'  => $app_id,
		  'secret' => $app_secret,
		));
		$this->app_id = $app_id;
		$this->user = $this->facebook->getUser();
		$this->redirect_uri = $return_uri;
		$this->access_token = $this->facebook->getAccessToken();
		if($this->user)
		{
		}
		else
		{
			$login_url_params = array(
				'scope' => 'manage_pages,email,user_birthday,read_insights,publish_stream',
				'fbconnect' =>  1,
				'redirect_uri' => $return_uri
			);
			$login_url = $this->facebook->getLoginUrl($login_url_params);
			header("Location: {$login_url}");
			exit();
		}
	}
	
	public function getUserDetailWithEmail()
	{
		
		if ($this->user)
		{
			$user_profile = $this->facebook->api(
			'/me',
			'GET',
			array(
				'access_token' => $this->access_token
			)
		  );
		}
		return json_encode($user_profile);
	}
	
	public function getUserProfileUrl($width,$height)
	{
		$user_id = $this->user;
		$pic_url = "https://graph.facebook.com/$user_id/picture?width=$width&height=$height";
		return $pic_url;
	}

    public function getPageLikes()
	{
		$req = $this->facebook->getSignedRequest();
		return $req;
	}

    public function getInsignts()
    {
        $now = strtotime(date('now'));
        $insights = $this->facebook->api('543294989034533/insights',
            'GET',
            array(
                'access_token' => $this->access_token
            )
        );
        return $insights;

    }

    public function postOnWall($link,$msg)
    {
		$data = $this->facebook->api('/me/feed', 'post',
				array(
					'message'=>$msg,
					'link' => $link,
					'access_token' => $this->access_token
				)
			);
        return $data;
    }
	public function GetPostOnWall()
	{
		$pageid = $this->user;
 
		// now we can access various parts of the graph, starting with the feed
		$pagefeed = $this->facebook->api("/" . $pageid . "/feed");
		return $pagefeed;
	}
}