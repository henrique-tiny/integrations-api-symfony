<?php

namespace App\Adapter\Provider\MercadoLivre;

use App\Adapter\DTO\Authentication\ConfigurationInterface;
use App\Application\Interface\Authentication\AuthTestInterface;
use App\Adapter\Provider\MercadoLivre\Client\Api\UsersApi;

class AuthProvider implements AuthTestInterface {

    public function __construct(
		private UsersApi $usersApi,
		private ConfigurationInterface $configuration
    ) {


    }

    public function test(): bool {
        try {
			$userId = $this->configuration->getConfig()->get("userId");
			$content = $this->usersApi->getUserById($userId);
			return true;
		} catch (\Throwable $th) {
			return false;
		}
    }

}
