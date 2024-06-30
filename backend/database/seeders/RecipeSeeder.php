<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recipes = [
            [
                'user_id' => 15,
                'category_id' => 6,
                'title' => 'Classic Margherita Pizza',
                'steps' => "Preheat your oven to 475°F (245°C).\nRoll out the pizza dough.\nSpread the tomato sauce.\nSprinkle mozzarella and Parmesan cheeses.\nAdd basil leaves.\nBake for 12-15 minutes.\nCool and serve.",
                'ingredients' => json_encode([2, 3, 7]),
                'total_time' => 20,
                'servings' => 4,
                'image' => 'pizza.jpg',
                'video' => null
            ],
            [
                'user_id' => 15,
                'category_id' => 7,
                'title' => 'Chicken Risotto',
                'steps' => "Melt butter and sauté onion.\nAdd garlic.\nStir in Arborio rice.\nAdd white wine.\nAdd warm broth gradually.\nStir in butter and Parmesan.\nSeason with salt and pepper.\nGarnish with parsley.",
                'ingredients' => json_encode([3, 6, 7]),
                'total_time' => 30,
                'servings' => 4,
                'image' => 'risotto.jpg',
                'video' => null
            ],
            // Add more recipes here...
        ];

        // Fill up to 15 recipes
        for ($i = 2; $i < 15; $i++) {
            $recipes[] = [
                'user_id' => 15,
                'category_id' => 7,
                'title' => 'Recipe ' . $i,
                'steps' => "Step 1.\nStep 2.\nStep 3.",
                'ingredients' => json_encode([rand(1, 7), rand(1, 7), rand(1, 7)]),
                'total_time' => rand(20, 60),
                'servings' => rand(2, 6),
                'image' => 'recipe' . $i . '.jpg',
                'video' => null
            ];
        }

        DB::table('recipes')->insert($recipes);

    }
}
