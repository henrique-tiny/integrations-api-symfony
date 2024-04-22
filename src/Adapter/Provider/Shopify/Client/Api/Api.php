<?php

namespace App\Adapter\Provider\Shopify\Client\Api;

use App\Adapter\Provider\Shopify\Client\RestClient;

class Api {

    const API_VERSION = "2024-01";

	public function __construct(public readonly RestClient $client) {}

}
