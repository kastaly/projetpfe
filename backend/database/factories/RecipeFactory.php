<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Models\Recipe>
 */
class RecipeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Recipe::class;

    public function definition()
    {
        return [
            'category_id' => Category::factory(),
            'title' => $this->faker->sentence,
            'ingredients' => $this->faker->paragraph,
            'highlighted_ingredient_ids' => [],
            'servings' => $this->faker->numberBetween(1, 10),
            'steps' => $this->faker->paragraphs(3, true),
            'total_time' => 30,
            'image' => $this->faker->imageUrl(),
            'user_id' => User::factory(), // Assuming a default user ID
            'video' => $this->faker->url,
        ];
    }

}
