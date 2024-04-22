<?php

namespace App\Adapter\Provider\Tray\Client;

use App\Adapter\Provider\Tray\Client\App;
use App\Adapter\Provider\Tray\ConfigurationService;
use App\Infrastructure\Framework\DependecyInjection\AbstractRestClient;
use Exception;
use GuzzleHttp\Middleware;
use Psr\Log\LoggerInterface;
use TinyERP\Client\Response\Response;

class AuthorizationClient extends AbstractRestClient {

	public function __construct(
		LoggerInterface $logger,
		private App $app,
		private ConfigurationService $configurationService
	) {
		parent::__construct(
			$logger,
			[
				Middleware::mapResponse(function (Response $response) {
					$body = $response->getContents();
					if ($response->getStatusCode() != 200) {
						throw new Exception("Não foi possível obter o token de acesso. Verifique as suas credenciais.", $response->getStatusCode());
					}
	
					$dados = [
						$body->access_token,
						$body->refresh_token,
						$body->date_expiration_access_token,
						$body->date_expiration_refresh_token,
					];
	
					foreach ($dados as $key => $value) {
						if (empty($value)) {
							throw new Exception("Não foi possível obter o token de acesso. Verifique as suas credenciais.", $response->getStatusCode());
						}
					}
	
					return $response;
				}),
			],
			$this->configurationService->getUrl()
		);
	}

	public function getAppId(): string {
		return $this->app->getAppId();
	}

	public function getAppSecret(): string {
		return $this->app->getAppSecret();
	}

}
