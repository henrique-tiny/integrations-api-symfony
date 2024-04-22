<?php

namespace App\Adapter\DTO\Authentication;

readonly class ResponseVerifyConnectionDTO {

	public function __construct(
		public bool $success
	) {}

}
