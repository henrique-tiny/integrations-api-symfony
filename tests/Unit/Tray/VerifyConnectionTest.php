<?php

namespace Tests\Unit\Tray;

use App\Adapter\Provider\Tray\AuthProvider;
use App\Adapter\Provider\Tray\Client\Api\ProductsApi;
use TinyERP\Client\Request\RequestOptions;
use TinyERP\Client\Response\Response;

it('CLIENT - should call /web_api/products when verifying the connection', function () {
	$client = \Tests\Unit\Tray\TrayStubs::client();
	$client->getHandler = function (string $endpoint, array $parameters = [], array $headers = [], ?RequestOptions $options = null): Response {
		expect($endpoint)->toBe("/web_api/products");
		expect($parameters)->toBe(["limit" => 1]);
		return new Response();
	};

	$productsApi = new ProductsApi($client);
	$authProvider = new AuthProvider($productsApi);

	$authProvider->test();
});
