<?php defined('SYSPATH') or die('No direct script access.');

class Model_Oauth extends Model_Database
{
	
	public function checkClientCredentials($client_id, $client_secret = NULL)
	{
		$table = Oauth::$config->storage['client_table'];
		
		$result = DB::query(Database::SELECT, "SELECT * FROM $table WHERE client_id = :client_id LIMIT 1;")
			->parameters(array(
				':client_id' => $client_id,
			))
			->execute()
			->as_array();
		
		return $result ? $result[0]['client_secret'] == $client_secret : NULL;
	}
	
	public function getClientDetails($client_id)
	{
		$table = Oauth::$config->storage['client_table'];
		
		$result = DB::query(Database::SELECT, "SELECT * FROM $table WHERE client_id = :client_id LIMIT 1;")
			->parameters(array(
				':client_id' => $client_id,
			))
			->execute()
			->as_array();
		
		return $result ? $result[0] : NULL;
	}
	
	public function checkRestrictedGrantType($client_id, $grant_type)
	{
		$details = $this->getClientDetails($client_id);
		
		if (isset($details['grant_types']))
		{
			return in_array($grant_type, (array) $details['grant_types']);
		}
		
		return TRUE;
	}
	
	public function getAccessToken($access_token)
	{
		$table = Oauth::$config->storage['access_token_table'];
		
		$result = DB::query(Database::SELECT, "SELECT * FROM $table WHERE access_token = :access_token LIMIT 1;")
			->parameters(array(
				':access_token' => $access_token,
			))
			->execute()
			->as_array();
		
		if ($result)
		{
			$result[0]['expires'] = strtotime($result[0]['expires']);
		}
		
		return $result ? $result[0] : NULL;
	}
	
	public function setAccessToken($access_token, $client_id, $user_id, $expires, $scope = NULL)
	{
		$table = Oauth::$config->storage['access_token_table'];
		
		$expires = date('Y-m-d H:i:s', $expires);
		
		if ($this->getAccessToken($access_token))
		{
			$result = DB::query(Database::UPDATE, "UPDATE $table SET client_id = :client_id, expires = :expires, user_id = :user_id, scope = :scope WHERE access_token = :access_token;");
		}
		else
		{
			$result = DB::query(Database::INSERT, "INSERT INTO $table(access_token, client_id, expires, user_id, scope) VALUES(:access_token, :client_id, :expires, :user_id, :scope);");
		}
		
		$result = $result->parameters(array(
				':client_id' => $client_id,
				':expires' => $expires,
				':user_id' => $user_id,
				':scope' => $scope,
				':access_token' => $access_token,
			))
			->execute();
		
		return $result;
	}
	
	public function getAuthorizationCode($authorization_code)
	{
		$table = Oauth::$config->storage['code_table'];
		
		$result = DB::query(Database::SELECT, "SELECT * FROM $table WHERE authorization_code = :authorization_code LIMIT 1;")
			->parameters(array(
				':authorization_code' => $authorization_code,
			))
			->execute()
			->as_array();
		
		if ($result)
		{
			$result[0]['expires'] = strtotime($result[0]['expires']);
		}
		
		return $result ? $result[0] : NULL;
	}
	
	public function setAuthorizationCode($authorization_code, $client_id, $user_id, $redirect_uri, $expires, $scope = NULL)
	{
		$table = Oauth::$config->storage['code_table'];
		
		$expires = date('Y-m-d H:i:s', $expires);
		
		if ($this->getAuthorizationCode($authorization_code))
		{
			$result = DB::query(Database::UPDATE, "UPDATE $table SET client_id = :client_id, user_id = :user_id, redirect_uri = :redirect_uri, expires = :expires, scope = :scope WHERE authorization_code = :authorization_code;");
		}
		else
		{
			$result = DB::query(Database::INSERT, "INSERT INTO $table(authorization_code, client_id, user_id, redirect_uri, expires, scope) VALUES(:authorization_code, :client_id, :user_id, :redirect_uri, :expires, :scope);");
		}
		
		$result = $result->parameters(array(
				':client_id' => $client_id,
				':user_id' => $user_id,
				':redirect_uri' => $redirect_uri,
				':expires' => $expires,
				':scope' => $scope,
				':authorization_code' => $authorization_code,
			))
			->execute();
		
		return $result;
	}
	
	public function expireAuthorizationCode($authorization_code)
	{
		$table = Oauth::$config->storage['code_table'];
		
		$result = DB::query(Database::DELETE, "DELETE FROM $table WHERE authorization_code = :authorization_code;")
			->parameters(array(
				':authorization_code' => $authorization_code,
			))
			->execute();
		
		return $result;
	}
	
	public function checkUserCredentials($username, $password)
    {
		$user = $this->getUser($username);
		
        return $user ? $this->checkPassword($user, $password) : FALSE;
    }
	
	public function getUserDetails($username)
	{
		return $this->getUser($username);
	}
	
	public function getRefreshToken($refresh_token)
	{
		$table = Oauth::$config->storage['refresh_token_table'];
		
		$result = DB::query(Database::SELECT, "SELECT * FROM $table WHERE refresh_token = :refresh_token LIMIT 1;")
			->parameters(array(
				':refresh_token' => $refresh_token,
			))
			->execute()
			->as_array();
		
		if ($result)
		{
			$result[0]['expires'] = strtotime($result[0]['expires']);
		}
		
		return $result ? $result[0] : NULL;
	}
	
	public function setRefreshToken($refresh_token, $client_id, $user_id, $expires, $scope = NULL)
	{
		$table = Oauth::$config->storage['refresh_token_table'];
		
		$expires = date('Y-m-d H:i:s', $expires);
		
		$result = DB::query(Database::INSERT, "INSERT INTO $table(refresh_token, client_id, user_id, expires, scope) VALUES(:refresh_token, :client_id, :user_id, :expires, :scope);")
			->parameters(array(
				':refresh_token' => $refresh_token,
				':client_id' => $client_id,
				':user_id' => $user_id,
				':expires' => $expires,
				':scope' => $scope,
			))
			->execute();
		
		return $result;
	}
	
	public function unsetRefreshToken($refresh_token)
	{
		$table = Oauth::$config->storage['refresh_token_table'];
		
		$result = DB::query(Database::DELETE, "DELETE FROM $table WHERE refresh_token = :refresh_token;")
			->parameters(array(
				':refresh_token' => $refresh_token,
			))
			->execute();
		
		return $result;
	}
	
	public function checkPassword($user, $password)
	{
		return $user['password'] == $password;
	}
	
	public function getUser($username)
	{
		$table = Oauth::$config->storage['user_table'];
		
		$result = DB::query(Database::SELECT, "SELECT * FROM $table WHERE username = :username LIMIT 1;")
			->parameters(array(
				':username' => $username,
			))
			->execute()
			->as_array();
		
		return $result ? $result[0] : NULL;
	}
	
	public function setUser($username, $password, $first_name = NULL, $last_name = NULL)
	{
		$table = Oauth::$config->storage['user_table'];
		
		if ($this->getUser($username))
		{
			$result = DB::query(Database::UPDATE, "UPDATE $table SET username = :username, password = :password, first_name = :first_name, last_name = :last_name WHERE username = :username;");
		}
		else
		{
			$result = DB::query(Database::INSERT, "INSERT INTO $table(username, password, first_name, last_name) VALUES(:username, :password, :first_name, :last_name);");
		}
		
		$result = $result->parameters(array(
				':username' => $username,
				':password' => $password,
				':first_name' => $first_name,
				':last_name' => $last_name,
			))
			->execute();
		
		return $result;
	}
	
	public function getClientKey($client_id, $subject)
	{
		$table = Oauth::$config->storage['jwt_table'];
		
		$result = DB::query(Database::SELECT, "SELECT public_key FROM $table WHERE client_id = :client_id AND subject = :subject LIMIT 1;")
			->parameters(array(
				':client_id' => $client_id,
				':subject' => $subject,
			))
			->execute()
			->as_array();
		
		return $result ? $result[0] : NULL;
	}
	
}
