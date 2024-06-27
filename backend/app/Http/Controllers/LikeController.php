<?php
// app/Http/Controllers/LikeController.php

// app/Http/Controllers/LikeController.php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Recipe;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function getLikeStatus(Recipe $recipe)
    {
        $user_id = auth()->id(); // Assuming authentication

        // Check if the user has liked the recipe
        $liked = $recipe->likes()->where('user_id', $user_id)->exists();

        // Get the like count
        $likeCount = $recipe->likes()->count();

        return response()->json([
            'liked' => $liked,
            'likeCount' => $likeCount
        ]);
    }

    public function like(Recipe $recipe)
    {
        $user_id = auth()->id(); // Assuming you're using authentication

        // Check if the user already liked the recipe
        if ($recipe->likes()->where('user_id', $user_id)->exists()) {
            return response()->json(['message' => 'You already liked this recipe.'], 400);
        }

        // Create a new like
        $like = new Like();
        $like->user_id = $user_id;
        $recipe->likes()->save($like);

        return response()->json(['message' => 'Recipe liked successfully.']);
    }

    public function unlike(Recipe $recipe)
    {
        $user_id = auth()->id(); // Assuming you're using authentication

        // Find and delete the like
        $like = $recipe->likes()->where('user_id', $user_id)->first();

        if (!$like) {
            return response()->json(['message' => 'You have not liked this recipe yet.'], 400);
        }

        $like->delete();

        return response()->json(['message' => 'Recipe unliked successfully.']);
    }
}
