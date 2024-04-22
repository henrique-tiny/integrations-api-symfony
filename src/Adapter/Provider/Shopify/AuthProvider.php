<?php

namespace App\Adapter\Provider\Shopify;

use App\Adapter\DTO\Authentication\ConfigurationInterface;
use App\Adapter\Provider\Shopify\Client\Api\ProductsApi;
use App\Application\Interface\Authentication\AuthTestInterface;

class AuthProvider implements AuthTestInterface {

    public function __construct(
		private ProductsApi $productsApi,
		private ConfigurationInterface $configuration
    ) {}

    public function test(): bool {
        try {
			$content = $this->productsApi->getProducts(1);
			return true;
		} catch (\Throwable $th) {
			return false;
		}
    }

}
