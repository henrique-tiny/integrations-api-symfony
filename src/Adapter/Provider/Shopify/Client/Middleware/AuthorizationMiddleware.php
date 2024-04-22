<?php

namespace App\Adapter\Provider\Shopify\Client\Middleware;

use App\Adapter\Provider\Shopify\Client\RestClient;
use Psr\Http\Message\RequestInterface;

class AuthorizationMiddleware {

	public function __construct(
		private RestClient $client, 
    ) {}

	public function __invoke(callable $handler) {
		return function ($request, array $options) use ($handler) {
			return $handler((function (RequestInterface $request) {
				return $this->verifyAuthentication($request);
			})($request), $options);
		};
	}

	private function verifyAuthentication(RequestInterface $request) {
		return $request->withHeader("X-Shopify-Access-Token", $this->client->getAuth()->getToken());
	}

}
