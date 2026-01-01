<?php

namespace App\Controllers;

use App\Services\FoodRecommendationService;
use CodeIgniter\RESTful\ResourceController;

class RecommendationController extends ResourceController
{
    public function recommend()
    {
        $payload = $this->request->getJSON(true);

        $constraints = $payload['constraints'] ?? [];

        $service = new FoodRecommendationService();
        $result = $service->recommend($constraints);

        return $this->response->setJSON([
            'status' => 'success',
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