<?php

namespace App\Adapter\Provider\Shopify\Client\Api;

class ProductsApi extends Api {

	public function getProducts(int $limit = 20): object {
		$response = $this->client->get("/admin/api/" . self::API_VERSION . "/products.json", ["limit" => $limit]);

		return $response->getContents();
	}

}
