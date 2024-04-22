<?php

namespace App\Adapter\Provider\Tray\Client\Api;

use App\Adapter\Provider\Tray\Client\Auth;
use App\Adapter\Provider\Tray\Client\AuthorizationClient;
use App\Adapter\Provider\Tray\ConfigurationService;
use DateTime;
use DateTimeZone;
use TinyERP\Client\Request\Body\JsonBody;

class AuthorizationApi {

	final public function __construct(
		private AuthorizationClient $client,
		private ConfigurationService $configurationService,
	) {
	}

	public function refreshToken(string $refreshToken): Auth {
		try {
			$res = $this->client->get("/web_api/auth", ["refresh_token" => $refreshToken]);
	
			$body = $res->getContents();
	
			$token = strval($body->access_token);
			$refreshToken = strval($body->refresh_token);
			$expiresIn = strval($body->date_expiration_access_token);
			$refreshTokenExpiresIn = strval($body->date_expiration_refresh_token);
	
			$this->configurationService->updateAuth($token, $refreshToken, $expiresIn, $refreshTokenExpiresIn);
	
			return new Auth($this->configurationService);
		} catch (\Throwable $th) {
			if ($th->getCode() === 401) {
				return $this->generateToken();
			}

			throw $th;
		}
	}

	public function generateToken(): Auth {
		$res = $this->client->post(
			"/web_api/auth", 
			new JsonBody([
				"consumer_key" => $this->client->getAppId(),
				"consumer_secret" => $this->client->getAppSecret(),
				"code" => $this->configurationService->getCode()
			])
		);
		
		$body = $res->getContents();

		$token = strval($body->access_token);
		$refreshToken = strval($body->refresh_token);
		$expiresIn = DateTime::createFromFormat("Y-m-d H:i:s", strval($body->date_expiration_access_token), new DateTimeZone("America/Sao_Paulo"));
		$refreshTokenExpiresIn = DateTime::createFromFormat("Y-m-d H:i:s", strval($body->date_expiration_refresh_token), new DateTimeZone("America/Sao_Paulo"));

		if ($expiresIn === false || $refreshTokenExpiresIn === false) {
			throw new \Exception("Invalid date format for the expiresIn values");
		}

		$expiresIn = $expiresIn->setTimezone(new DateTimeZone("UTC"))->format("Y-m-d H:i:s");
		$refreshTokenExpiresIn = $refreshTokenExpiresIn->setTimezone(new DateTimeZone("UTC"))->format("Y-m-d H:i:s");

		$this->configurationService->updateAuth($token, $refreshToken, $expiresIn, $refreshTokenExpiresIn);

		return new Auth($this->configurationService);
	}

}
