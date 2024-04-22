<?php

namespace App\Adapter\Provider\MercadoLivre\Client\Middleware;

use App\Adapter\Provider\MercadoLivre\Client\Api\AuthorizationApi;
use App\Adapter\Provider\MercadoLivre\Client\Auth;
use App\Adapter\Provider\MercadoLivre\Client\RestClient;
use Psr\Http\Message\RequestInterface;

class AuthorizationMiddleware {

	public function __construct(
		private RestClient $client, 
		private AuthorizationApi $authorizationApi) {
	}

	public function __invoke(callable $handler) {
		return function ($request, array $options) use ($handler) {
			return $handler((function (RequestInterface $request) {
				return $this->verifyAuthentication($request);
			})($request), $options);
		};
	}

	private function verifyAuthentication(RequestInterface $request) {
		$auth = $this->client->getAuth();

		if (!$auth || $auth->isExpired()) {
			$this->client->setAuth($this->refreshToken());
		}

		return $request->withHeader("Authorization", "Bearer " . $this->client->getAuth()->getAccessToken());
	}

	private function refreshToken(): Auth {
		return $this->authorizationApi->refreshToken($this->client->getAuth()->getRefreshToken());
	}

}
