<?php

namespace App\Infrastructure\Framework\Listeners;

use App\Adapter\Interface\EnvironmentConfigurationInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionListener implements EventSubscriberInterface {

	public function __construct(private EnvironmentConfigurationInterface $environment) {}

	public static function getSubscribedEvents(): array {
		return [
			KernelEvents::EXCEPTION => ['onKernelException', 200],
		];
	}

	public function onKernelException(ExceptionEvent $event) {
		if (!$this->environment->isProd() && $this->environment->get("ERROR_FORMAT_ON_DEV") === "html") {
			return;
		}

		$exception = $event->getThrowable();
		$content = [
            'code' => $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : 500,
            'message' => $exception instanceof HttpExceptionInterface || !$this->environment->isProd() ? $exception->getMessage() : "Internal Server Error",
            'trace' => !$this->environment->isProd()
                ? $exception->getTrace()
                : [],
        ];

        $event->setResponse(
            new JsonResponse($content, $content['code'])
        );
	}
}
