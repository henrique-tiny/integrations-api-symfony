<?php

use App\Adapter\Controller\AuthController;
use App\Adapter\DTO\Authentication\ConfigurationDTO;
use App\Adapter\DTO\Authentication\ConfigurationInterface;
use App\Adapter\DTO\Authentication\PlatformRef;
use App\Adapter\Enum\IntegrationEnum;
use App\Adapter\Interface\EnvironmentConfigurationInterface;
use App\Adapter\Provider\MercadoLivre\Client\Api\AuthorizationApi;
use App\Adapter\Provider\MercadoLivre\Client\Api\UsersApi;
use App\Adapter\Provider\MercadoLivre\Client\App;
use App\Adapter\Provider\MercadoLivre\Client\Auth;
use App\Adapter\Provider\MercadoLivre\Client\AuthorizationClient;
use App\Adapter\Provider\MercadoLivre\Client\RestClient;
use App\Application\Interface\Authentication\AuthFactoryInterface;
use App\Application\Interface\Authentication\AuthTestInterface;
use App\Application\UseCase\Authentication\VerifyConnectionUseCase;

it('should verify connection with Mercado Livre', function () {
	$configuration = new class implements ConfigurationInterface {
		public function getConfig(): ConfigurationDTO {
			return ConfigurationDTO::fromArray([
				"app" => "MERCADOLIVRE_APP_PADRAO_ID",
				"token" => "APP_USR-6963932030962812-041009-0f6e8385abb590051094d5bf10c6d351-216380518",
				"refreshToken" => "TG-66168dabe200460001a6197c-216380518",
				"expiresIn" => 1712775691,
				"userId" => 216380518
			]);
		}
	};

	$environment = new class implements EnvironmentConfigurationInterface {
		public function get(string $key): ?string {
			return $key;
		}
		
		public function isDev(): bool {
			return false;
		}

		public function isTest(): bool {
			return true;
		}

		public function isProd(): bool {
			return false;
		}
	};

	$userApi = new class($configuration, $environment) extends UsersApi {

		public int $userId = 0;

		public function __construct(private ConfigurationInterface $configuration, private EnvironmentConfigurationInterface $environment)
		{
			$app = new App($this->configuration, $this->environment);
			parent::__construct(new RestClient(
				new Auth($this->configuration),
				new AuthorizationApi(new AuthorizationClient($app)),
				$app
			));
		}

		public function getUserById(int $userId): object {
			$this->userId = $userId;
			return (object) [
				"id" => 1,
				"nickname" => "test"
			];
		}

	};

	$factory = new class($userApi, $configuration) implements AuthFactoryInterface {
		public function __construct(
			private UsersApi $userApi,
			private ConfigurationInterface $configuration
		) {}

		public function getTestAuthInstance(PlatformRef $platform): AuthTestInterface {

			return new \App\Adapter\Provider\MercadoLivre\AuthProvider(
				$this->userApi,
				$this->configuration
			);
		
		}
	};

	$authController = new AuthController(
		new VerifyConnectionUseCase($factory)
	);

	$authController->verify(
		new \App\Adapter\DTO\Authentication\RequestVerifyConnectionDTO(
			new PlatformRef(IntegrationEnum::MERCADOLIVRE)
		)
	);	

	expect($userApi->userId)->toBe(216380518);
});
