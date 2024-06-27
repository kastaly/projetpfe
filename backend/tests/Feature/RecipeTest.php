<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Recipe;
use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RecipeTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Additional setup code specific to your application, if any
    }

    /** @test */
    public function it_can_store_a_recipe()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/api/recipes/store', [
                'category_id' => $category->id,
                'title' => 'Test Recipe',
                'ingredients' => 'Ingredient 1, Ingredient 2',
                'servings' => 4,
                'steps' => 'Step 1, Step 2',
                'total_time' => 30,
                'image' => UploadedFile::fake()->image('recipe.jpg'),
            ]);

        $response->assertStatus(201)
            ->assertJson(['message' => 'Recipe added successfully']);

        // Additional assertions if needed
        $this->assertDatabaseHas('recipes', ['title' => 'Test Recipe']);
    }

    /** @test */
    public function it_can_update_a_recipe()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $recipe = Recipe::factory()->create(['user_id' => $user->id]);
        $category = Category::factory()->create();

        $response = $this->actingAs($user)
            ->putJson("/api/recipes/{$recipe->id}/update", [
                'title' => 'Updated Recipe',
                'category_id' => $category->id,
                'ingredients' => 'Updated Ingredient 1, Updated Ingredient 2',
                'servings' => 6,
                'steps' => 'Updated Step 1, Updated Step 2',
                'total_time' => 45,
                'image' => UploadedFile::fake()->image('updated_recipe.jpg'),
            ]);

        $response->assertStatus(200)
            ->assertJson(['recipe' => ['title' => 'Updated Recipe']]);

        // Additional assertions if needed
        $this->assertDatabaseHas('recipes', ['title' => 'Updated Recipe']);
    }

}
