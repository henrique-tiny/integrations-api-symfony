<?php

namespace App\Adapter\Provider\Shopify\Client\Middleware;

use Exception;
use TinyERP\Client\Response\Response;

class MapResponseMiddleware {

	public function __invoke(callable $handler) {
		return function ($request, array $options) use ($handler) {
			return $handler($request, $options)->then(function (Response $response) {
				return $this->mapResponse($response);
			});
		};
	}

	private function mapResponse(Response $response) {
		if ($response->getStatusCode() >= 300) {
			$error = $response->getContents();
			$message = "Ocorreu um erro desconhecido.";
			if (is_object($error) && is_string($error->errors)) {
				$message = $error->errors;
			} elseif (is_string($error)) {
				$message = $error;
			}

			throw new Exception($message, $response->getStatusCode());
		}

		return $response;
	}

}
