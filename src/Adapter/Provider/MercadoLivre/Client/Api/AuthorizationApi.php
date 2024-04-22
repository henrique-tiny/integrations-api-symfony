<?php

namespace App\Adapter\Provider\MercadoLivre\Client\Api;

use App\Adapter\Provider\MercadoLivre\Client\Auth;
use App\Adapter\Provider\MercadoLivre\Client\AuthorizationClient;
use TinyERP\Client\Request\Body\JsonBody;

class AuthorizationApi {

	final public function __construct(private AuthorizationClient $client) {
	}

	public function refreshToken(string $refreshToken): Auth {
		$res = $this->client->post(
			"/oauth/token",
			(new JsonBody([
				"grant_type" => "refresh_token",
				"client_id" => $this->client->getAppId(),
				"client_secret" => $this->client->getAppSecret(),
				"refresh_token" => $refreshToken,
			]))
		);

		$body = $res->getContents();
		$token = strval($body->access_token);
		$refreshToken = strval($body->refresh_token);
		$expiresIn = intval($body->expires_in);

		return Auth::fromParams($token, $refreshToken, $expiresIn);
	}

}
