<?php

namespace App\Adapter\Controller;

use App\Adapter\DTO\Authentication\RequestVerifyConnectionDTO;
use App\Adapter\DTO\Authentication\ResponseVerifyConnectionDTO;
use App\Application\UseCase\Authentication\VerifyConnectionUseCase;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthController {

	public function __construct(
		private VerifyConnectionUseCase $verifyConnectionUseCase,
	) {}

    public function verify(RequestVerifyConnectionDTO $payload): ResponseVerifyConnectionDTO {
		$test = $this->verifyConnectionUseCase->run($payload->getPlatformRef());
		if (!$test) {
			throw new UnauthorizedHttpException('Unauthorized');
		}

        return new ResponseVerifyConnectionDTO($test);
    }
}
