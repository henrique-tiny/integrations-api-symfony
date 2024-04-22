<?php

namespace App\Adapter\Provider\Tray\Client;

use App\Adapter\Provider\Tray\Client\Api\AuthorizationApi;
use App\Adapter\Provider\Tray\Client\Middleware\MapResponseMiddleware;
use App\Adapter\Provider\Tray\Client\Middleware\AuthorizationMiddleware;
use App\Adapter\Provider\Tray\ConfigurationService;
use App\Infrastructure\Framework\DependecyInjection\AbstractRestClient;
use Psr\Log\LoggerInterface;

class RestClient extends AbstractRestClient {

	private ?\Closure $onUpdateAuth = null;
	
	public function __construct(
		LoggerInterface $logger,
		private Auth $auth,
		private App $app,
        private ConfigurationService $configurationService,
		private AuthorizationApi $authorizationApi,
	) {
		error_log("debug: " . __CLASS__ . ' - ' . __FUNCTION__ . PHP_EOL);
		parent::__construct(
			logger: $logger, 
			middlewares: [
				new AuthorizationMiddleware($this, $this->authorizationApi),
				new MapResponseMiddleware(),
			],
			baseUri: $this->configurationService->getUrl()
		);
	}

	public function getClientId(): string {
		return $this->app->getAppId();
	}

	public function getClientSecret(): string {
		return $this->app->getAppSecret();
	}

	public function getAuth(): ?Auth {
		return $this->auth;
	}

	public function setAuth(?Auth $auth): self {
		$this->auth = $auth;

		if ($this->onUpdateAuth instanceof \Closure) {
			call_user_func($this->onUpdateAuth, $auth);
		}

		return $this;
	}

	public function setOnUpdateAuth(\Closure $onUpdateAuth): self {
		$this->onUpdateAuth = $onUpdateAuth;
		return $this;
	}

}
