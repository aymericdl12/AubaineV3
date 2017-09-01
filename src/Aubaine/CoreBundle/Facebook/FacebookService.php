<?php

namespace Aubaine\CoreBundle\Facebook;
use Facebook\Facebook;


class FacebookService
{
	// Ces variables réceptionnerons nos identifiants
	private $appId;
	private $appSecret;
	private $pageID;
	private $token;
	private $appsecret_proof;
    // Cette variable contiendra notre connexion avec l’API et sera utilisé pour interagir 
	private $connection;

	public function __construct($appId,$appSecret,$pageID,$token)
	{
		$this->appId = $appId;
		$this->appSecret = $appSecret;
		$this->pageID = $pageID;
		$this->token = $token;
		$this->appsecret_proof= hash_hmac('sha256', '639260759607903', '6e1639828958455d8a6549ff4f275190'); 

        // Cette instruction nous permettra de nous connecter à l'API
		$this->connection = new \Facebook\Facebook([
		  'app_id' => $this->appId,
		  'app_secret' => $this->appSecret,
		  'appsecret_proof' => hash_hmac('sha256', $this->appId, $this->appSecret),
		  'default_graph_version' => 'v2.10',
		  'default_access_token' => $this->token,
		]);

	}

	// Cette fonction permettra de poster sur notre page Facebook
	public function poster($msg)
	{

		try {
		  // Get the \Facebook\GraphNodes\GraphUser object for the current user.
		  // If you provided a 'default_access_token', the '{access-token}' is optional.
		  $response = $this->connection->get('/Le-bco-1159788157453961');
		} catch(\Facebook\Exceptions\FacebookResponseException $e) {
		  // When Graph returns an error
		  return 'Graph returned an error: ' . $e->getMessage();
		  exit;
		} catch(\Facebook\Exceptions\FacebookSDKException $e) {
		  // When validation fails or other local issues
		  return 'Facebook SDK returned an error: ' . $e->getMessage();
		  exit;
		}

		$me = $response->getGraphObject();
		return 'Informations: ' . $me->getProperty('offers_v3');

	}

}