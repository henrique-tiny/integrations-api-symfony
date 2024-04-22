<?php

namespace App\Adapter\Provider\Shopify\Client;

use App\Adapter\DTO\Authentication\ConfigurationInterface;
use App\Adapter\Provider\Shopify\Client\Middleware\MapResponseMiddleware;
use App\Adapter\Provider\Shopify\Client\Middleware\AuthorizationMiddleware;
use TinyERP\Client\Client as TinyClient;

class RestClient extends TinyClient {

	private ?\Closure $onUpdateAuth = null;
	
	public function __construct(
		private Auth $auth,
		private App $app,
        private ConfigurationInterface $configurationService,
	) {
        $config = $configurationService->getConfig();
		parent::__construct($config->get("url"));
	}

	protected function getAdditionalMiddlewares() {
		$middlewares = [
			new AuthorizationMiddleware($this),
			new MapResponseMiddleware(),
		];

		return $middlewares;
	}

	public function getClientId(): string {
		return $this->app->getClientId();
	}

	public function getClientSecret(): string {
		return $this->app->getClientSecret();
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
