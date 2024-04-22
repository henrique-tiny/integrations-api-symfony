<?php

namespace App\Adapter\Provider\Shopify\Client;

use App\Adapter\DTO\Authentication\ConfigurationInterface;

class Auth {

	private ?string $token = null;
    private ?string $url = null;

	public function __construct(ConfigurationInterface $configurationService) {
		$config = $configurationService->getConfig();

        $this->token = (string) $config->get("token");
        $this->url = (string) $config->get("url");
	}

	public function getToken(): ?string {
		return $this->token;
	}

    public function getUrl(): ?string {
        return $this->url;
    }

}
