<?php

namespace App\Models;

use CodeIgniter\Model;

class FoodModel extends Model
{
    protected $table = 'cleaned_nutrition_dataset_per100g';
    protected $allowedFields = [
        'vitamin_c_mg','vitamin_b11_mg','sodium_mg','calcium_mg','carbohydrates_g','food','iron_mg','calories_kcal','sugars_g','fibers_g','fat_g','protein_g'
    ];
}
