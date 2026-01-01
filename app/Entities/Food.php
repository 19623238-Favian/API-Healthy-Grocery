<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Food extends Entity
{
    protected $attributes = [
        'vitamin_c_mg' => null,
        'vitamin_b11' => null,
        'sodium_mg' => null,
        'calcium_mg' => null,
        'carbohydrates_g' => null,
        'food' => null,
        'iron_mg' => null,
        'calories_kcal' => null,
        'sugars_g' => null,
        'fibers_g' => null,
        'fat_g' => null,
        'protein_g' => null
        // 'health_score' => null,
    ];
}
