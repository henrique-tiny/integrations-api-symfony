<?php

namespace App\Adapter\DTO\Authentication;

class ConfigurationDTO {

    private array $config = [];

	public function get(string $key): mixed {
		return $this->config[$key];
	}

	public function set(string $key, mixed $value): void {
		$this->config[$key] = $value;
	}

	public static function fromArray(array $config): static {
		$adapter = new static();
		$adapter->config = $config;
		return $adapter;
	}
    
}
