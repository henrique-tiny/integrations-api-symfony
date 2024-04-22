<?php

namespace App\Adapter\Provider\MercadoLivre\Client;

use App\Adapter\DTO\Authentication\ConfigurationDTO;
use App\Adapter\DTO\Authentication\ConfigurationInterface;

class Auth {

	private string $accessToken;
	private string $refreshToken;
	private int $expiresIn;

	public function __construct(ConfigurationInterface $configurationService) {
		$config = $configurationService->getConfig();
		$this->accessToken = (string) $config->get("token");
		$this->refreshToken = (string) $config->get("refreshToken");
		$this->expiresIn = (int) $config->get("expiresIn");
	}

	public function getAccessToken(): ?string {
		return $this->accessToken;
	}

	public function getRefreshToken(): ?string {
		return $this->refreshToken;
	}

	public function getExpiresIn(): ?int {
		return $this->expiresIn;
	}

	public function isExpired(): bool {
		return !$this->accessToken || !$this->refreshToken || $this->expiresIn < (time() + 5 * 60);
	}

	public static function fromParams(string $accessToken, string $refreshToken, int $expiresIn): Auth {
		$adapter = new class($accessToken, $refreshToken, $expiresIn) implements ConfigurationInterface {

			private ConfigurationDTO $config;

			public function __construct(string $token, string $refreshToken, int $expiresIn) {
				$this->config = ConfigurationDTO::fromArray([
					"token" => $token,
					"refreshToken" => $refreshToken,
					"expiresIn" => $expiresIn
				]);
				
			}

			public function getConfig(): ConfigurationDTO
			{
				return $this->config;
			}
		};

		return new static($adapter);
	}

}
