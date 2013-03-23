<?php defined('SYSPATH') OR die('No direct access allowed.');

return array
(
	'storage' => array(
		'client_table' => 'oauth_clients',
		'access_token_table' => 'oauth_access_tokens',
		'refresh_token_table' => 'oauth_refresh_tokens',
		'code_table' => 'oauth_authorization_codes',
		'user_table' => 'oauth_users',
		'jwt_table' => 'oauth_jwt',
	),
	'server' => array(
		'token_type' => 'bearer',
		'access_lifetime' => 3600,
		'www_realm' => 'Service',
		'token_param_name' => 'access_token',
		'token_bearer_header_name' => 'Bearer',
		'supported_scopes' => array(),
		'enforce_state' => FALSE,
		'allow_implicit' => TRUE,
	),
	'grant_types' => array(
		'authorization_code',
		'user_credentials',
		'client_credentials',
		'refresh_token',
	),
);
