<?php

namespace App\Infrastructure\Framework\DependecyInjection;

use Psr\Log\LoggerInterface;
use TinyERP\Client\Request\RequestOptions;

abstract class AbstractRestClient extends \TinyERP\Client\Client {
	
	public function __construct(
		private LoggerInterface $logger,
		private array $middlewares = [],
		?string $baseUri = null, 
		?array $headers = [], 
		?RequestOptions $defaultRequestOptions = null
	) {
		parent::__construct($baseUri, $headers, $defaultRequestOptions);
	}

	
	final protected function getAdditionalMiddlewares() {
		return [
			...$this->middlewares,
			// new class($this->logger) extends LogMiddleware {
			// 	private LoggerInterface $logger;
				
			// 	protected bool $logRequests = true;

			// 	public function __construct(LoggerInterface $logger) {
			// 		$this->logger = $logger;
			// 		$this->logTemplate = '{date_iso_8601} - {method} - {uri} - {code} - {error} - {req_body} - {res_body}';
			// 	}

			// 	protected function log(string $message, array $context): void {
			// 		$this->logger->info($message, $context);
			// 	}

			// 	protected function decide(RequestInterface $request, ResponseInterface $response = null, $reason = null, ?array $options = null): bool {
			// 		return true;
			// 	}
			// }
		];
	}
}
