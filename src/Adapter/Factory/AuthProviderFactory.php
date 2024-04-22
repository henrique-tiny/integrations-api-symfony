<?php

namespace App\Adapter\Factory;

use App\Adapter\DTO\Authentication\PlatformRef;
use App\Adapter\Interface\AuthProviderLocatorInterface;
use App\Application\Interface\Authentication\AuthFactoryInterface;
use App\Application\Interface\Authentication\AuthTestInterface;

class AuthProviderFactory implements AuthFactoryInterface {

    public function __construct(
		private AuthProviderLocatorInterface $locator,
    ) {}

    public function getTestAuthInstance(PlatformRef $platformRef): AuthTestInterface {
		$class = "App\Adapter\Provider\\" . $platformRef->getReferenceId() . "\AuthProvider";
		$provider = $this->locator->getProvider($class);

		if (!$provider instanceof AuthTestInterface) {
			throw new \Exception("Invalid provider");
		}

		return $provider;
    }

}
