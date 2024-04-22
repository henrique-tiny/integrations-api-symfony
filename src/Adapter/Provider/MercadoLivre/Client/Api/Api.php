<?php

namespace App\Adapter\Provider\MercadoLivre\Client\Api;

use App\Adapter\Provider\MercadoLivre\Client\RestClient;

class Api {

	public function __construct(public readonly RestClient $client) {
	}

}
