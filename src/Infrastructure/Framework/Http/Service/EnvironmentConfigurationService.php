<?php

namespace App\Infrastructure\Framework\Http\Service;

use App\Adapter\Interface\EnvironmentConfigurationInterface;

class EnvironmentConfigurationService implements EnvironmentConfigurationInterface {
	private array $config;
	public function __construct(?array $config = null) {
		$this->config = $config ?? $_ENV;
	}

	public function get(string $key): ?string {
		if (!isset($this->config[$key])) {
			return null;
		}

		return $this->config[$key];
	}

	public function isDev(): bool {
		return $this->get("APP_ENV") === 'dev';
	}

	public function isTest(): bool {
		return $this->get("APP_ENV") === 'test';
	}

	public function isProd(): bool {
		return $this->get("APP_ENV") === 'prod';
	}
}
