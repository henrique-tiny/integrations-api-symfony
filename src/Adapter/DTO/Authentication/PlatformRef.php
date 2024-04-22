<?php

namespace App\Adapter\DTO\Authentication;

use App\Adapter\Enum\IntegrationEnum;

class PlatformRef {

	public function __construct(
		private IntegrationEnum $platformRef
	)
	{
		$this->getReferenceId(); // apenas para validar se a referência é válida
	}

	public function get(): IntegrationEnum {
		return $this->platformRef;
	}

	public function getReferenceId(): string {
		return match ($this->platformRef) {
			IntegrationEnum::MERCADOLIVRE => "MercadoLivre",
			IntegrationEnum::SHOPIFY => "Shopify",
			IntegrationEnum::TRAY => "Tray",
			IntegrationEnum::SHOPEE => "Shopee",
			default => throw new \Exception("Platform reference not found")
		};
	}
    
}
