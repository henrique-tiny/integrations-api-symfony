<?php

namespace App\Application\UseCase\Authentication;

use App\Adapter\DTO\Authentication\PlatformRef;
use App\Application\Interface\Authentication\AuthFactoryInterface;

class VerifyConnectionUseCase {

    public function __construct(
		private AuthFactoryInterface $factory
    ) {}

    public function run(PlatformRef $platformRef): bool {
		$provider = $this->factory->getTestAuthInstance($platformRef);

		return $provider->test();
    }

}
