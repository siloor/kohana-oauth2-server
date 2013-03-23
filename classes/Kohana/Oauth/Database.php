<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Oauth_Database implements OAuth2_Storage_AuthorizationCodeInterface, OAuth2_Storage_AccessTokenInterface, OAuth2_Storage_ClientCredentialsInterface, OAuth2_Storage_UserCredentialsInterface, OAuth2_Storage_RefreshTokenInterface, OAuth2_Storage_JWTBearerInterface
{
	
	public function checkClientCredentials($client_id, $client_secret = null)
	{
		return Model::factory('Oauth')->checkClientCredentials($client_id, $client_secret);
	}
	
	public function getClientDetails($client_id)
	{
		return Model::factory('Oauth')->getClientDetails($client_id);
	}
	
	public function checkRestrictedGrantType($client_id, $grant_type)
	{
		return Model::factory('Oauth')->checkRestrictedGrantType($client_id, $grant_type);
	}
	
	public function getAccessToken($access_token)
	{
		return Model::factory('Oauth')->getAccessToken($access_token);
	}
	
	public function setAccessToken($access_token, $client_id, $user_id, $expires, $scope = null)
	{
		return Model::factory('Oauth')->setAccessToken($access_token, $client_id, $user_id, $expires, $scope);
	}
	
	public function getAuthorizationCode($code)
	{
		return Model::factory('Oauth')->getAuthorizationCode($code);
	}
	
	public function setAuthorizationCode($code, $client_id, $user_id, $redirect_uri, $expires, $scope = null)
	{
		return Model::factory('Oauth')->setAuthorizationCode($code, $client_id, $user_id, $redirect_uri, $expires, $scope);
	}
	
	public function expireAuthorizationCode($code)
	{
		return Model::factory('Oauth')->expireAuthorizationCode($code);
	}
	
	public function checkUserCredentials($username, $password)
	{
		return Model::factory('Oauth')->checkUserCredentials($username, $password);
	}
	
	public function getUserDetails($username)
	{
		return Model::factory('Oauth')->getUserDetails($username);
	}
	
	public function getRefreshToken($refresh_token)
	{
		return Model::factory('Oauth')->getRefreshToken($refresh_token);
	}
	
	public function setRefreshToken($refresh_token, $client_id, $user_id, $expires, $scope = null)
	{
		return Model::factory('Oauth')->setRefreshToken($refresh_token, $client_id, $user_id, $expires, $scope);
	}
	
	public function unsetRefreshToken($refresh_token)
	{
		return Model::factory('Oauth')->unsetRefreshToken($refresh_token);
	}
	
	protected function checkPassword($user, $password)
	{
		return Model::factory('Oauth')->checkPassword($user, $password);
	}
	
	public function getUser($username)
	{
		return Model::factory('Oauth')->getUser($username);
	}
	
	public function setUser($username, $password, $firstName = null, $lastName = null)
	{
		return Model::factory('Oauth')->setUser($username, $password, $firstName, $lastName);
	}
	
	public function getClientKey($client_id, $subject)
	{
		return Model::factory('Oauth')->getClientKey($client_id, $subject);
	}
	
}
