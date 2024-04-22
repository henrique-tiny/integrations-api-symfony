<?php

namespace App\Adapter\Provider\MercadoLivre\Client;

use App\Adapter\Provider\MercadoLivre\Client\App;
use Exception;
use GuzzleHttp\Middleware;
use TinyERP\Client\Response\Response;
use TinyERP\Client\Client as TinyClient;

class AuthorizationClient extends TinyClient {

	public function __construct(
		private App $app
	) {
		parent::__construct("https://api.mercadolibre.com");
	}

	public function getAppId(): string {
		return $this->app->getAppId();
	}

	public function getAppSecret(): string {
		return $this->app->getAppSecret();
	}

	protected function getAdditionalMiddlewares() {
		return [
			Middleware::mapResponse(function (Response $response) {
				$body = $response->getContents();
				if ($response->getStatusCode() != 200) {
					throw new Exception("Não foi possível obter o token de acesso. Verifique as suas credenciais.", $response->getStatusCode());
				}

				$dados = [
					"token" => $body->access_token,
					"refresh_token" => $body->refresh_token,
					"expires_in" => $body->expires_in,
				];

				foreach ($dados as $key => $value) {
					if (empty($value)) {
						throw new Exception("Não foi possível obter o token de acesso. Verifique as suas credenciais.", $response->getStatusCode());
					}
				}

				return $response;
			}),
		];
	}

}
