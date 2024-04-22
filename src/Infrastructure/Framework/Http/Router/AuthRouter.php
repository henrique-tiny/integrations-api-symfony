<?php

namespace App\Infrastructure\Framework\Http\Router;

use App\Adapter\Controller\AuthController;
use App\Adapter\DTO\Authentication\PlatformRef;
use App\Adapter\DTO\Authentication\RequestVerifyConnectionDTO;
use App\Adapter\Enum\IntegrationEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AuthRouter extends AbstractController {

    #[Route(path: "/phpinfo", methods: ["GET"], env: 'dev')]
	public function phpinfo() {
		phpinfo();
	}
    
	#[Route(path: "/api/v1/{platformRef}/auth/verify", methods: ["POST"], format: "json")]
    public function verify(
        AuthController $authController,
		IntegrationEnum $platformRef
    ) {
		$requestVerifyConnectionDTO = new RequestVerifyConnectionDTO(
			new PlatformRef($platformRef)
		);

		$response = $authController->verify($requestVerifyConnectionDTO);

        return new JsonResponse([
            "successfull" => $response->success,
        ]);
    }
}
