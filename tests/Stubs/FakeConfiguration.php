<?php

namespace Tests\Stubs;

use App\Adapter\DTO\Authentication\ConfigurationDTO;
use App\Adapter\DTO\Authentication\ConfigurationInterface;

class FakeConfiguration implements ConfigurationInterface {

	public function __construct(private array $data) {}

	public function getConfig(): ConfigurationDTO {
		return ConfigurationDTO::fromArray($this->data);
	}
}
