<?php

namespace App\Adapter\Provider\MercadoLivre\Client;

use App\Adapter\Provider\MercadoLivre\Client\Api\AuthorizationApi;
use App\Adapter\Provider\MercadoLivre\Client\Middleware\MapResponseMiddleware;
use App\Adapter\Provider\MercadoLivre\Client\Middleware\AuthorizationMiddleware;
use TinyERP\Client\Client as TinyClient;

class RestClient extends TinyClient {

	private ?\Closure $onUpdateAuth = null;
	
	public function __construct(
		private Auth $auth,
		private AuthorizationApi $authorizationApi,
		private App $app
	) {
		parent::__construct("https://api.mercadolibre.com");
	}

	protected function getAdditionalMiddlewares() {
		$middlewares = [
			new AuthorizationMiddleware($this, $this->authorizationApi),
			new MapResponseMiddleware(),
		];

		return $middlewares;
	}

	public function getAppId(): string {
		return $this->app->getAppId();
	}

	public function getAppSecret(): string {
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
