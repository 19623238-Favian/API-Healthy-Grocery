<?php

namespace App\Services;

use App\Models\FoodModel;

class FoodRecommendationService
{
    public function recommend(array $constraints)
    {
        $model = new FoodModel();

        $query = $model->builder();

        if (isset($constraints['max_calories'])) {
            $query->where('calories_kcal <=', $constraints['max_calories']);
        }

        if (!empty($constraints['low_sugar'])) {
            $query->where('sugars_g <=', 10);
        }

        if (!empty($constraints['high_fiber'])) {
            $query->orderBy('fibers_g', 'DESC');
        }
        
        $query->select("*,((fibers_g * 4)+(protein_g * 3)+(vitamin_c_mg * 0.05)+(calcium_mg * 0.02)+(iron_mg * 1.5)+(vitamin_b11_mg * 2)-(sugars_g * 3)-(fat_g * 2)-(sodium_mg * 0.02)-(calories_kcal * 0.03)) AS health_score");
        $query
            ->orderBy('health_score', 'DESC')
            ->limit(5);

        return $query->get()->getResultArray();
        // return $query
        //     // ->orderBy('health_score', 'DESC')
        //     ->limit(5)
        //     ->get()
        //     ->getResultArray();
    }
}