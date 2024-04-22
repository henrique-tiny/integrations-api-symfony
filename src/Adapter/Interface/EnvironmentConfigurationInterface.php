<?php

namespace App\Adapter\Interface;

interface EnvironmentConfigurationInterface {

	public function get(string $key): ?string;

	public function isDev(): bool;

	public function isTest(): bool;

	public function isProd(): bool;
	
}
