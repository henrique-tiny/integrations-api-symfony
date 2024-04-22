<?php

namespace App\Adapter\Interface;

interface AuthProviderLocatorInterface {

	public function getProvider(string $class): object;
	
}
