<?php

namespace App\Adapter\Provider\Tray\Client\Middleware;

use App\Adapter\Provider\Tray\Client\RestClient;
use App\Adapter\Provider\Tray\Client\Api\AuthorizationApi;
use App\Adapter\Provider\Tray\Client\Auth;
use Psr\Http\Message\RequestInterface;

class AuthorizationMiddleware {

	public function __construct(
		private RestClient $client, 
		private AuthorizationApi $authorizationApi,
    ) {}

	public function __invoke(callable $handler) {
		error_log("debug: " . __CLASS__ . ' - ' . __FUNCTION__ . PHP_EOL);
		return function ($request, array $options) use ($handler) {
			return $handler((function (RequestInterface $request) {
				return $this->verifyAuthentication($request);
			})($request), $options);
		};
	}

	private function verifyAuthentication(RequestInterface $request) {
		error_log("debug: " . __CLASS__ . ' - ' . __FUNCTION__ . PHP_EOL);
		$auth = $this->client->getAuth();
		if ($auth?->isTokenExpired() && !$auth?->isRefreshTokenExpired()) {
			$this->client->setAuth($this->refreshToken());
		} elseif (!$auth || $auth?->isRefreshTokenExpired()) {
			$this->client->setAuth($this->generateToken());	
		}

		$uri = $request->getUri();
		parse_str($uri->getQuery(), $query);
		$query['access_token'] = $this->client->getAuth()->getToken();
		$uri = $uri->withQuery(http_build_query($query));

		return $request->withUri($uri)->withHeader("Content-Type", "application/json");
	}

	private function refreshToken(): Auth {
		return $this->authorizationApi->refreshToken($this->client->getAuth()->getRefreshToken());
	}

	private function generateToken(): Auth {
		return $this->authorizationApi->generateToken();
	}
}
