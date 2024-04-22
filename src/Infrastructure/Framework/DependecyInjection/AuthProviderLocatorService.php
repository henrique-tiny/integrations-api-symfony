<?php

namespace App\Infrastructure\Framework\DependecyInjection;

use App\Adapter\Interface\AuthProviderLocatorInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;

class AuthProviderLocatorService implements AuthProviderLocatorInterface {

	public function __construct(
		#[AutowireLocator([
			\App\Adapter\Provider\MercadoLivre\AuthProvider::class,
			\App\Adapter\Provider\Tray\AuthProvider::class,
			\App\Adapter\Provider\Shopify\AuthProvider::class,
		])]
		private ContainerInterface $locator,
	) {}

	public function getProvider(string $class): object {
		$provider = $this->locator->get($class);

		return $provider;
	}

}
