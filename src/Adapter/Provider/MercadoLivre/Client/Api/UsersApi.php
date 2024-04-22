<?php

namespace App\Adapter\Provider\MercadoLivre\Client\Api;

class UsersApi extends Api {

	public function getUserById(int $userId): object {
		$response = $this->client->get("/users/$userId");

		return $response->getContents();
	}

}
