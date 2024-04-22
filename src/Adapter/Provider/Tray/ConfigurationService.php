<?php

namespace App\Adapter\Provider\Tray;

use App\Adapter\DTO\Authentication\ConfigurationInterface;
use DateTime;
use DateTimeZone;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ConfigurationService {

	public function __construct(
		private ConfigurationInterface $configuration
	) {
		$config = $this->configuration->getConfig();
		error_log("token: " . $config->get("token"));
		$required = [
			"url", "token", "refreshToken", "expiresIn", "refreshTokenExpiresIn", "clientId"
		];

		$requiredAdditional = [
			"code"
		];

		foreach ($required as $key) {
			if (empty($config->get($key))) {
				throw new UnprocessableEntityHttpException("Invalid configuration key: {$key}");
			}
		}

		if (!is_object($config->get("additional"))) {
			throw new UnprocessableEntityHttpException("Invalid configuration key: additional");
		}

		$additional = $config->get("additional");

		foreach ($requiredAdditional as $key) {
			if (!property_exists($additional, $key)) {
				throw new UnprocessableEntityHttpException("Missing configuration key: additional.{$key}");
			}

			if (empty($additional->$key)) {
				throw new UnprocessableEntityHttpException("Invalid configuration key: additional.{$key}");
			}
		}

		$expiresIn = DateTime::createFromFormat("Y-m-d H:i:s", $config->get("expiresIn"));
		if ($expiresIn === false) {
			throw new UnprocessableEntityHttpException("Invalid configuration key: expiresIn");
		}

		$refreshTokenExpiresIn = DateTime::createFromFormat("Y-m-d H:i:s", $config->get("refreshTokenExpiresIn"));
		if ($refreshTokenExpiresIn === false) {
			throw new UnprocessableEntityHttpException("Invalid configuration key: refreshTokenExpiresIn");
		}
	}

	public function updateAuth(
		string $token,
		string $refreshToken,
		string $expiresIn,
		string $refreshTokenExpiresIn
	): void {
		$this->configuration->getConfig()->set("token", $token);
		$this->configuration->getConfig()->set("refreshToken", $refreshToken);
		$this->configuration->getConfig()->set("expiresIn", $expiresIn);
		$this->configuration->getConfig()->set("refreshTokenExpiresIn", $refreshTokenExpiresIn);
	}

	public function getUrl(): string {
		return $this->configuration->getConfig()->get("url");
	}

	public function getToken(): string {
		return $this->configuration->getConfig()->get("token");
	}

	public function getRefreshToken(): string {
		return $this->configuration->getConfig()->get("refreshToken");
	}

	public function getClientId(): string {
		return $this->configuration->getConfig()->get("clientId");
	}

	public function getCode(): string {
		return $this->configuration->getConfig()->get("additional")->code;
	}

	private function parseDate(string $date): DateTime {
		return DateTime::createFromFormat("Y-m-d H:i:s", $date, new DateTimeZone("UTC"));
	}	

	public function getExpiresIn(): DateTime {
		return $this->parseDate(
			$this->configuration->getConfig()->get("expiresIn")
		);
	}

	public function getRefreshTokenExpiresIn(): DateTime {
		return $this->parseDate(
			$this->configuration->getConfig()->get("refreshTokenExpiresIn")
		);
	}
}
