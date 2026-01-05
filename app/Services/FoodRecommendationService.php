<?php

namespace App\Services;

use App\Models\FoodModel;

class FoodRecommendationService
{
    public function recommend(array $constraints, array $flags)
    {
        $model = new FoodModel();
        $builder = $model->builder();
        $servingFactor = 1.0;

        // Ambil nilai target dari input
        $maxCal = $constraints['max_calories_per_serving'] ?? 300;
        $maxCarb = $constraints['macros']['carbohydrates']['max_g'] ?? 37;
        $minProt = $constraints['macros']['protein']['min_g'] ?? 18;
        $maxFat = $constraints['macros']['fat']['max_g'] ?? 8;
        $maxSugar = $constraints['micros']['sugars_g_max'] ?? 5;
        $maxSod = $constraints['micros']['sodium_mg_max'] ?? 300;
        $minFiber = $constraints['micros']['dietary_fiber_g_min'] ?? 6;

        $builder->select("
            *,
            (calories_kcal * {$servingFactor}) as serving_cal,
            (protein_g * {$servingFactor}) as serving_prot,
            
            (
                -- 1. BASE NUTRITION SCORE (Logarithmic)
                (LOG(1 + (protein_g * {$servingFactor})) * 1.5) + 
                (LOG(1 + (fibers_g * {$servingFactor})) * 2.0) +

                -- 2. PENALTY SYSTEM (Mengurangi skor jika melanggar batas)
                -- Jika kalori > target, kurangi skor secara eksponensial
                CASE WHEN (calories_kcal * {$servingFactor}) > {$maxCal} 
                     THEN -POWER((calories_kcal * {$servingFactor}) - {$maxCal}, 1.2) ELSE 0 END +
                
                -- Jika protein < target, beri penalti besar
                CASE WHEN (protein_g * {$servingFactor}) < {$minProt} 
                     THEN -(({$minProt} - (protein_g * {$servingFactor})) * 2) ELSE 0 END +

                -- Jika lemak > target, kurangi skor
                CASE WHEN (fat_g * {$servingFactor}) > {$maxFat} 
                     THEN -( (fat_g * {$servingFactor}) - {$maxFat} ) ELSE 0 END +

                -- Jika gula > target
                CASE WHEN (sugars_g * {$servingFactor}) > {$maxSugar} 
                     THEN -( (sugars_g * {$servingFactor}) - {$maxSugar} * 3) ELSE 0 END
                
            ) AS health_score
        ");

        // Hapus semua WHERE clause yang kaku agar hasil tidak 0
        // Kita hanya mengandalkan urutan skor terbaik
        return $builder
            ->orderBy('health_score', 'DESC')
            ->limit(15)
            ->get()
            ->getResultArray();
    }
}