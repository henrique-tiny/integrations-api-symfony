<?php

namespace App\Adapter\Provider\MercadoLivre\Client\Middleware;

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
			$erroDesconhecido = true;
			if (is_object($error) && is_string($error->message)) {
				$message = $error->message;
				$erroDesconhecido = false;
			} elseif (is_string($error)) {
				$message = $error;
				$erroDesconhecido = false;
			}

			if ($erroDesconhecido) {
			}

			throw new Exception($message, $response->getStatusCode());
		}

		return $response;
	}

}
