<?php
use Tests\TestCase;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user and authenticate
        $this->actingAs(User::factory()->create());
    }

    public function testLikeRecipe()
    {
        $recipe = Recipe::factory()->create();

        //  like the recipe
        $response = $this->post("/api/recipes/{$recipe->id}/like");

        // Assert response status 
        $response->assertStatus(200)
                 ->assertJson(['message' => 'Recipe liked successfully.']);
    }

    public function testGetLikeStatus()
    {
        $recipe = Recipe::factory()->create();

        // get like status
        $response = $this->get("/api/recipes/{$recipe->id}/like-status");

        // Assert response status
        $response->assertStatus(200)
                 ->assertJsonStructure(['liked', 'likeCount']);
    }
}
