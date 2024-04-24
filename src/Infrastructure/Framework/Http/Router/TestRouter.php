<?php

namespace App\Infrastructure\Framework\Http\Router;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class TestRouter extends AbstractController {
    
    #[Route(path: "/phpinfo", methods: ["GET"], env: 'dev')]
	public function phpinfo() {
		phpinfo();
	}

    #[Route(path: "/healthcheck", methods: ["GET"])]
	public function healthcheck() {
		return new JsonResponse([
			"ok" => true,
			"datetime" => date("Y-m-d H:i:s"),
		]);
	}

    #[Route(path: "/async", methods: ["GET"])]
	public function async() {
        $result = [];

        \Swoole\Coroutine::join([
            go(function() use (&$result) {
                error_log("start aws..");
                file_get_contents("https://www.aws.com");
                error_log("finish aws..");

                $result["aws"] = date("Y-m-d H:i:s");
            }),

            go(function() use (&$result) {
                error_log("start google..");
                file_get_contents("https://www.google.com");
                error_log("finish google..");

                $result["google"] = date("Y-m-d H:i:s");
            }),

            go(function() use (&$result) {
                error_log("start tiny..");
                file_get_contents("https://www.tiny.com.br");
                error_log("finish tiny..");

                $result["tiny"] = date("Y-m-d H:i:s");
            }),
        ]);

		return new JsonResponse([
			"ok" => true,
			"datetime" => date("Y-m-d H:i:s"),
            "result" => $result,
		]);
	}

}