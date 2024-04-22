<?php

namespace App\Application\Interface\Authentication;

use App\Adapter\DTO\Authentication\PlatformRef;

interface AuthFactoryInterface {
	public function getTestAuthInstance(PlatformRef $platform): AuthTestInterface;
}
