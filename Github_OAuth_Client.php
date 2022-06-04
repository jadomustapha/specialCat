<?php
class Github_OAuth_Client{
    public $authorizeURL = "https://github.com/login/oauth/authorize";
    public $tokenURL = "https://github.com/login/oauth/access_token";
    public $apiURLBase = "https://api.github.com/";
    public $clientID;
    public $clientSecret;
    public $redirectUri;
    
    /**
     * Construct object
     */
    public function __construct(array $config = []){
        $this->clientID = isset($config['client_id']) ? $config['client_id'] : '';
        if(!$this->clientID){
            die('Required "client_id" key not supplied in config');
        }
        
        $this->clientSecret = isset($config['client_secret']) ? $config['client_secret'] : '';
        if(!$this->clientSecret){
            die('Required "client_secret" key not supplied in config');
        }
        
        $this->redirectUri = isset($config['redirect_uri']) ? $config['redirect_uri'] : '';
    }
    
    /**
     * Get the authorize URL
     *
     * @returns a string
     */
    public function getAuthorizeURL($state){
        return $this->authorizeURL . '?' . http_build_query([
            'client_id' => $this->clientID,
            'redirect_uri' => $this->redirectUri,
            'state' => $state,
            'scope' => 'user:email'
        ]);
    }
    
    /**
     * Exchange token and code for an access token
     */
    public function getAccessToken($state, $oauth_code){
        $token = self::apiRequest($this->tokenURL . '?' . http_build_query([
            'client_id' => $this->clientID,
            'client_secret' => $this->clientSecret,
            'state' => $state,
            'code' => $oauth_code
        ]));
        return $token->access_token;
    }
    
    /**
     * Make an API request
     *
     * @return API results
     */
    public function apiRequest($access_token_url){
        $apiURL = filter_var($access_token_url, FILTER_VALIDATE_URL)?$access_token_url:$this->apiURLBase.'user?access_token='.$access_token_url;
        $context  = stream_context_create([
          'http' => [
            'user_agent' => 'CodexWorld GitHub OAuth Login',
            'header' => 'Accept: application/json'
          ]
        ]);
        $response = @file_get_contents($apiURL, false, $context);
        
        return $response ? json_decode($response) : $response;
    }

}