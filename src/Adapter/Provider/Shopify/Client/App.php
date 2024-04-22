<?php

namespace App\Adapter\Provider\Shopify\Client;

class App {

	private string $clientId;
	private string $clientSecret;

	public function __construct() {
        $this->clientId = $_ENV["SHOPIFY_CLIENT_ID"];
		$this->clientSecret = $_ENV["SHOPIFY_CLIENT_SECRET"];
	}

	public function getClientId(): string {
		return $this->clientId;
	}

	public function getClientSecret(): string {
		return $this->clientSecret;
	}
}
