<?php

namespace App\Adapter\Provider\Tray\Client;

use App\Adapter\Interface\EnvironmentConfigurationInterface;
use App\Adapter\Provider\Tray\ConfigurationService;
use Symfony\Component\DependencyInjection\Exception\EnvNotFoundException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class App {

	private string $appId;
	private string $appSecret;

	public function __construct(
		ConfigurationService $configuration,
		EnvironmentConfigurationInterface $environment
	) {
		$requiredKeys = [
			"TRAY_APP_TRAY_REST_PADRAO_CONSUMER_KEY",
			"TRAY_APP_TRAY_REST_FATURADOR_CONSUMER_KEY",
			"TRAY_APP_TRAY_REST_PADRAO_CONSUMER_SECRET",
			"TRAY_APP_TRAY_REST_FATURADOR_CONSUMER_SECRET",
		];

		foreach ($requiredKeys as $key) {
			if ((string) $environment->get($key) === "") {
				throw new EnvNotFoundException("Chave de configuração não encontrada: $key");
			}
		}

		$apps = [
			$environment->get("TRAY_APP_TRAY_REST_PADRAO_CONSUMER_KEY") => [
				"appSecret" => $environment->get("TRAY_APP_TRAY_REST_PADRAO_CONSUMER_SECRET"),
			],
			$environment->get("TRAY_APP_TRAY_REST_FATURADOR_CONSUMER_KEY") => [
				"appSecret" => $environment->get("TRAY_APP_TRAY_REST_FATURADOR_CONSUMER_SECRET"),
			],
		];

		$clientId = $configuration->getClientId();
		$app = $apps[$clientId];
		if ($app === null) {
			throw new BadRequestHttpException("Tray App não encontrado.");
		}

		$this->appId = $clientId;
		$this->appSecret = $app["appSecret"];
	}

	public function getAppId(): string {
		return $this->appId;
	}

	public function getAppSecret(): string {
		return $this->appSecret;
	}
}
