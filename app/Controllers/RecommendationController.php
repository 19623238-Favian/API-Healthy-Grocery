<?php

namespace App\Controllers;

use App\Services\FoodRecommendationService;
use App\Models\FoodModel;
use CodeIgniter\RESTful\ResourceController;

class RecommendationController extends ResourceController
{
    public function index()
    {
        $foodModel = new FoodModel();

        $foods = $foodModel->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'total'  => count($foods),
            'data'   => $foods
        ]);
    }

    public function recommend()
    {
        $payload = $this->request->getJSON(true);

        $constraints = $payload['constraints'] ?? [];
        $dietFlags   = $payload['diet_flags'] ?? [];
        $meta        = $payload['meta'] ?? [];

        $service = new FoodRecommendationService();
        $result = $service->recommend($constraints,$dietFlags,$meta);

        if (!isset($payload['constraints']) && !isset($payload['diet_flags'])) {
            return $this->fail('No recommendation parameters provided', 422);
        }


        return $this->response->setJSON([
            'status' => 'success',
            'count'  => count($result),
            'data' => $result
        ]);
    }

    // public function recommend()
    // {
    //     return [
    //         'debug' => 'service reached',
    //         'constraints' => $constraints
    //     ];
    // }

}