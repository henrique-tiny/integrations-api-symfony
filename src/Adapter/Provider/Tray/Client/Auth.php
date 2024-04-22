<?php

namespace App\Adapter\Provider\Tray\Client;

use App\Adapter\Provider\Tray\ConfigurationService;
use DateInterval;
use DateTime;

class Auth {

	public function __construct(private ConfigurationService $configurationService) {}

	public function getToken(): ?string {
		return $this->configurationService->getToken();
	}

    public function getUrl(): ?string {
        return $this->configurationService->getUrl();
    }

	public function getRefreshToken(): ?string {
		return $this->configurationService->getRefreshToken();
	}

	public function isTokenExpired(): bool {
		$date = new DateTime();
		$date->add(new DateInterval("PT5M"));
		return $this->configurationService->getExpiresIn() < $date;
	}
	
	public function isRefreshTokenExpired(): bool {
		$date = new DateTime();
		return $this->configurationService->getRefreshTokenExpiresIn() < $date;
	}

}
