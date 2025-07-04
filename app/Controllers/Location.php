<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class Location extends ResourceController
{

    public function getLocation()
    {
        $search = $this->request->getGet('search');
        $limit = $this->request->getGet('limit') ?? 50;

        $client = \Config\Services::curlrequest();
        $response = $client->get('https://rajaongkir.komerce.id/api/v1/destination/domestic-destination', [
            'query' => [
                'search' => $search,
                'limit' => $limit
            ],
            'headers' => [
                'key' => 'nQ5GUqIE1bc297b7846e2705VEaeNUGu'
            ]
        ]);
        $result = json_decode($response->getBody(), true);

        // Untuk debug, tampilkan response mentah
        return $this->response->setJSON($result);
    }

    public function getKelurahan()
    {
        $search = $this->request->getGet('search');
        $limit = $this->request->getGet('limit') ?? 50;

        $client = \Config\Services::curlrequest();
        try {
            $response = $client->get('https://rajaongkir.komerce.id/api/v1/destination/domestic-destination', [
                'query' => [
                    'search' => $search,
                    'limit' => $limit
                ],
                'headers' => [
                    'key' => 'nQ5GUqIE1bc297b7846e2705VEaeNUGu'
                ]
            ]);
            $result = json_decode($response->getBody(), true);

            // Pastikan response ada key 'data'
            if (isset($result['data'])) {
                return $this->response->setJSON(['data' => $result['data']]);
            } else {
                return $this->response->setJSON(['data' => []]);
            }
        } catch (\Exception $e) {
            // Untuk debug error
            return $this->response->setStatusCode(500)->setJSON([
                'error' => $e->getMessage()
            ]);
        }
    }
}