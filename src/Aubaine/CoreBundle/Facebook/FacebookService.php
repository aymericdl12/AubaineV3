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
		  'app_id' => '639260759607903',
		  'app_secret' => '6e1639828958455d8a6549ff4f275190',
		  'appsecret_proof' => hash_hmac('sha256', '639260759607903', '6e1639828958455d8a6549ff4f275190'),
		  'default_graph_version' => 'v2.10',
		  'default_access_token' => 'EAAJFZA35ZByl8BAMxibAPsM7z6ZClEtVZBjHXzoL7MS5GZBv6qBbZAIwtLLTluQpqFYKK3ekgcpzZAZCcNh28bPZCbr0Mwdb3ncK53hbYyiZBoflnZAaF6PxfGAwvo2k5e7GpniqFZCvgmNbmza2TLW9q9LlKkEVa3wsE01ZCkGhJBZAQj5Q0EqxIBZAIdMaWZB2yzQ4EjUZD',
		]);

	}

	// Cette fonction permettra de poster sur notre page Facebook
	public function poster($msg)
	{

		try {
		  // Get the \Facebook\GraphNodes\GraphUser object for the current user.
		  // If you provided a 'default_access_token', the '{access-token}' is optional.
		  $response = $this->connection->get('/me');
		} catch(\Facebook\Exceptions\FacebookResponseException $e) {
		  // When Graph returns an error
		  return 'Graph returned an error: ' . $e->getMessage();
		  exit;
		} catch(\Facebook\Exceptions\FacebookSDKException $e) {
		  // When validation fails or other local issues
		  return 'Facebook SDK returned an error: ' . $e->getMessage();
		  exit;
		}

		$me = $response->getGraphUser();
		return 'Logged in as ' . $me->getName();

	}

}