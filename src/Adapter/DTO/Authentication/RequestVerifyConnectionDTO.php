<?php

namespace App\Adapter\DTO\Authentication;

class RequestVerifyConnectionDTO {

	public function __construct(
		protected PlatformRef $platformRef
	) {}

	public function getPlatformRef(): PlatformRef {
		return $this->platformRef;
	}

}
