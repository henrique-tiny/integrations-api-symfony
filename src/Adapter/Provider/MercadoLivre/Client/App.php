<?php

namespace App\Adapter\Provider\MercadoLivre\Client;

use App\Adapter\DTO\Authentication\ConfigurationInterface;
use App\Adapter\Interface\EnvironmentConfigurationInterface;
use Symfony\Component\DependencyInjection\Exception\EnvNotFoundException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class App {

	private string $appId;
	private string $appSecret;

	public function __construct(
		ConfigurationInterface $configuration,
		EnvironmentConfigurationInterface $environment
	) {
		$keys = [
			"MERCADOLIVRE_APP_INVOICES_ID",
			"MERCADOLIVRE_APP_INVOICES_SECRET",
			"MERCADOLIVRE_APP_AUTOMATICO_ID",
			"MERCADOLIVRE_APP_AUTOMATICO_SECRET",
			"MERCADOLIVRE_APP_PADRAO_ID",
			"MERCADOLIVRE_APP_PADRAO_SECRET",
		];

		foreach ($keys as $key) {
			if ($environment->get($key) === null) {
				throw new EnvNotFoundException("Chave de configuração não encontrada: $key");
			}
		}

		$apps = [
			$environment->get("MERCADOLIVRE_APP_INVOICES_ID") => [
				"appSecret" => $environment->get("MERCADOLIVRE_APP_INVOICES_SECRET"),
			],
			$environment->get("MERCADOLIVRE_APP_AUTOMATICO_ID") => [
				"appSecret" => $environment->get("MERCADOLIVRE_APP_AUTOMATICO_SECRET"),
			],
			$environment->get("MERCADOLIVRE_APP_PADRAO_ID") => [
				"appSecret" => $environment->get("MERCADOLIVRE_APP_PADRAO_SECRET"),
			],
		];

		$configurationDTO = $configuration->getConfig();
		$app = $apps[$configurationDTO->get("app")];
		if ($app === null) {
			throw new BadRequestHttpException("MercadoLivre App não encontrado.");
		}

		$this->appId = $configurationDTO->get("app");
		$this->appSecret = $app["appSecret"];
	}

	public function getAppId(): string {
		return $this->appId;
	}

	public function getAppSecret(): string {
		return $this->appSecret;
	}
}
