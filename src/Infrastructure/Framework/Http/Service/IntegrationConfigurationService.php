<?php

namespace App\Infrastructure\Framework\Http\Service;

use App\Adapter\DTO\Authentication\ConfigurationDTO;
use App\Adapter\DTO\Authentication\ConfigurationInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class IntegrationConfigurationService implements ConfigurationInterface {

	private ConfigurationDTO $config;

	public function __construct(RequestStack $requestStack)
	{
		$this->config = ConfigurationDTO::fromArray((array) json_decode($requestStack->getCurrentRequest()->getContent()));	
	}

	public function getConfig(): ConfigurationDTO
	{
		return $this->config;
	}
}
