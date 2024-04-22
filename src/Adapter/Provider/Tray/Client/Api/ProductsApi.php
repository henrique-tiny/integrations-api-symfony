<?php

namespace App\Adapter\Provider\Tray\Client\Api;

class ProductsApi extends Api {

	public function list(int $limit = 20, string $sort = "id_desc"): object {
		error_log("debug: " . __CLASS__ . ' - ' . __FUNCTION__ . PHP_EOL);
		$response = $this->client->get("/web_api/products", ["limit" => $limit, "sort" => $sort]);

		return $response->getContents();
	}

}
