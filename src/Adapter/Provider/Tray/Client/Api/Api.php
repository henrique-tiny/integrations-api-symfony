<?php

namespace App\Adapter\Provider\Tray\Client\Api;

use App\Adapter\Provider\Tray\Client\RestClient;

class Api {

	public function __construct(public readonly RestClient $client) {}

}
