<?php

namespace App\Adapter\Provider\Tray;

use App\Adapter\Provider\Tray\Client\Api\ProductsApi;
use App\Application\Interface\Authentication\AuthTestInterface;

class AuthProvider implements AuthTestInterface {

    public function __construct(
		private ProductsApi $productsApi
    ) {}

    public function test(): bool {
        try {
			error_log("debug: " . __CLASS__ . ' - ' . __FUNCTION__ . PHP_EOL);
			$content = $this->productsApi->list(limit: 1);
			return true;
		} catch (\Throwable $th) {
			return false;
		}
    }

}
