<?php defined('SYSPATH') OR die('No direct script access.');

abstract class Kohana_Oauth
{
	
	protected static $storage;
	
	protected static $instance = NULL;
	
	public static $request;
	
	public static $config;
	
	public static function instance()
	{
		if (self::$instance === NULL)
		{
			self::$config = Kohana::$config->load('oauth');
			
			self::$storage = new Kohana_Oauth_Database();
			
			$grant_types = array();
			
			$valid_grant_types = array(
				'user_credentials' => 'OAuth2_GrantType_UserCredentials',
				'client_credentials' => 'OAuth2_GrantType_ClientCredentials',
				'refresh_token' => 'OAuth2_GrantType_RefreshToken',
				'authorization_code' => 'OAuth2_GrantType_AuthorizationCode',
			);
			
			foreach ($valid_grant_types as $grant_type => $handler)
			{
				if (in_array($grant_type, self::$config->grant_types))
				{
					array_push($grant_types, new $handler(self::$storage));
				}
			}
			
			self::$instance = new OAuth2_Server(self::$storage, self::$config->server, $grant_types);
			
			self::$request = OAuth2_Request::createFromGlobals();
		}
		
		return self::$instance;
	}
	
	public static function set_oauth_response(&$response, $oauth_response)
	{
		$response->protocol('HTTP/' . $oauth_response->version);
		
		$response->status($oauth_response->getStatusCode());
		
		if ($oauth_response->getHttpHeaders())
		{
			foreach ($oauth_response->getHttpHeaders() as $header_key => $header_value)
			{
				$response->headers($header_key, $header_value);
			}
		}
		
		$response->body($oauth_response->getResponseBody());
	}
	
}
