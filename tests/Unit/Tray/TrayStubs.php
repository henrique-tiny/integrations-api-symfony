<?php

namespace Tests\Unit\Tray;

use App\Adapter\Provider\Tray\Client\App;
use App\Adapter\Provider\Tray\Client\Auth;
use App\Adapter\Provider\Tray\Client\AuthorizationClient;
use App\Adapter\Provider\Tray\Client\RestClient;
use App\Adapter\Provider\Tray\ConfigurationService;
use App\Infrastructure\Framework\Http\Service\EnvironmentConfigurationService;
use Tests\Stubs\FakeConfiguration;
use Tests\Stubs\FakeLogger;
use TinyERP\Client\Request\Body\BodyInterface;
use App\Adapter\Provider\Tray\Client\Api\AuthorizationApi;
use TinyERP\Client\Request\RequestOptions;
use TinyERP\Client\Response\Response;

abstract class TrayStubs {
	public static function client() {
		return new class extends RestClient {
	
			public \Closure|null $getHandler = null;
			public \Closure|null $postHandler = null;
			public \Closure|null $putHandler = null;
			public \Closure|null $deleteHandler = null;
	
			public function __construct() {
				parent::__construct(
					new FakeLogger,
					auth(),
					app(),
					configurationService(),
					authorizationApi()
				);
			}
	
			public function get(string $endpoint, array $parameters = [], array $headers = [], ?RequestOptions $options = null): Response {
				if ($this->getHandler === null) {
					throw new \Exception("Handler not defined");
				}
	
				return call_user_func($this->getHandler, $endpoint, $parameters, $headers, $options);
			}
	
			public function post(string $endpoint, ?BodyInterface $body = null, array $headers = [], array $parameters = [], ?RequestOptions $options = null): Response {
				if ($this->postHandler === null) {
					throw new \Exception("Handler not defined");
				}
	
				return call_user_func($this->postHandler, $endpoint, $body, $headers, $parameters, $options);
			}
	
			public function put(string $endpoint, ?BodyInterface $body = null, array $headers = [], array $parameters = [], ?RequestOptions $options = null): Response {
				if ($this->putHandler === null) {
					throw new \Exception("Handler not defined");
				}
	
				return call_user_func($this->putHandler, $endpoint, $body, $headers, $parameters, $options);
			}
	
			public function delete(string $endpoint, array $parameters = [], array $headers = [], ?RequestOptions $options = null): Response {
				if ($this->deleteHandler === null) {
					throw new \Exception("Handler not defined");
				}
	
				return call_user_func($this->deleteHandler, $endpoint, $parameters, $headers, $options);
			}
	
		};
	}
	
	public static function configurationService(): ConfigurationService {
		return configurationService();
	}
	
	public static function auth(): Auth {
		return auth();
	}
	
	public static function app(): App {
		return app();
	}
	
	public static function authorizationApi(): AuthorizationApi {
		return authorizationApi();
	}
	
	public static function authorizationClient(): AuthorizationClient {
		return authorizationClient();
	}
	
	public static function environmentConfigurationService(): EnvironmentConfigurationService {
		return environmentConfigurationService();
	}
}

function configurationService(): ConfigurationService {
	return new ConfigurationService(
		new FakeConfiguration([
			"url" => "https://api.tray.com.br",
			"app_id" => "app_id",
			"app_secret" => "app_secret",
			"token" => "token",
			"refreshToken" => "refreshToken",
			"expiresIn" => date('Y-m-d H:i:s', strtotime('+1 hour')),
			"refreshTokenExpiresIn" => date('Y-m-d H:i:s', strtotime('+1 day')),
			"clientId" => "TRAY_APP_TRAY_REST_PADRAO_CONSUMER_KEY",
			"additional" => (object) ["code" => "code"],
		])
	);
}
	
function auth(): Auth {
	return new Auth(
		configurationService()
	);
}

function app(): App {
	return new App(
		configurationService(),
		environmentConfigurationService()
	);
}

function authorizationApi(): AuthorizationApi {
	return new AuthorizationApi(
		authorizationClient(),
		configurationService(),
	);
}

function authorizationClient(): AuthorizationClient {
	return new AuthorizationClient(
		new FakeLogger,
		app(),
		configurationService(),
	);
}

function environmentConfigurationService(): EnvironmentConfigurationService {
	return new EnvironmentConfigurationService([
		"TRAY_APP_TRAY_REST_PADRAO_CONSUMER_KEY" => "TRAY_APP_TRAY_REST_PADRAO_CONSUMER_KEY",
		"TRAY_APP_TRAY_REST_PADRAO_CONSUMER_SECRET" => "TRAY_APP_TRAY_REST_PADRAO_CONSUMER_SECRET",
		"TRAY_APP_TRAY_REST_FATURADOR_CONSUMER_KEY" => "TRAY_APP_TRAY_REST_FATURADOR_CONSUMER_KEY",
		"TRAY_APP_TRAY_REST_FATURADOR_CONSUMER_SECRET" => "TRAY_APP_TRAY_REST_FATURADOR_CONSUMER_SECRET",
	]);
}
