<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\HighlightedIngredient;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Recipe::query();
        // Check if there's a search query
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'LIKE', "%$search%")
                  ->orWhere('steps', 'LIKE', "%$search%")
                  ->orWhere('ingredients', 'LIKE', "%$search%")
                  ->orWhereHas('category', function ($query) use ($search) {
                      $query->where('name', 'LIKE', "%$search%");
                  });
        }

    // Check if there's a category filter
    if ($request->filled('category')) {
        $category = $request->input('category');
        $query->where('category_id', $category);
    }

    // Check if there's a sort option
    if ($request->filled('sort')) {
        $sort = $request->input('sort');
        if ($sort === 'latest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($sort === 'likes') {
            $query->withCount('likes')->orderBy('likes_count', 'desc');
        }
    }

        $recipes = $query->with('category', 'user','comments','likes','likes.user')
            ->orderBy('created_at', 'desc')
            ->get();

        $categories = Category::all();
        $users =User::where('role', 'user')
        ->latest()
        ->get();
        $user = Auth::check() ? Auth::user() : null;

        if ($user) {
            $user->profile_picture_url = $this->getProfilePictureUrl($user);
        }

        $users->each(function($user) {
            $user->profile_picture_url = $this->getProfilePictureUrl($user);
        });

        // Append image URL to recipes
        $recipes->each(function ($recipe) {
            $recipe->image_url = $recipe->image ? url('storage/' . $recipe->image) : null;
            $recipe->user->profile_picture_url = $this->getProfilePictureUrl($recipe->user);

            // Fetch users who liked the recipe
            $recipe->likes->each(function ($like) {
                $like->user->profile_picture_url = $this->getProfilePictureUrl($like->user);
            });
        });



        // Append image URL to recipes
        $recipes->each(function ($recipe) {
            $recipe->image_url = $recipe->image ? url('storage/' . $recipe->image) : null;
            $recipe->user->profile_picture_url = $this->getProfilePictureUrl($recipe->user);
             $recipe->video_url = $recipe->video ? url('storage/' . $recipe->video) : null;
            // Fetch users who liked the recipe
            $recipe->likes->each(function ($like) {
                $like->user->profile_picture_url = $this->getProfilePictureUrl($like->user);
            });
        });

        //video of recipe




        return response()->json([
            'recipes' => $recipes,
            'categories' => $categories,
            'users' => $users,
            'user' => $user,
        ]);
    }

    private function getProfilePictureUrl($user)
    {
        if ($user->profile_picture) {
            $localPath = 'storage/' . $user->profile_picture;
            $apiPath = 'http://127.0.0.1:8001/storage/' . $user->profile_picture;

            if (file_exists(public_path($localPath))) {
                return url($localPath);
            } else {
                return $apiPath;
            }
        } else {
            return 'path/to/default/profile/picture.png'; // Default profile picture
        }
    }

    public function getCategories()
    {
        $categories = Category::all();
        return response()->json(['categories' => $categories]);
    }

    public function myrecipes()
    {
        $user = Auth::user()->id;
        $recipes = Recipe::where('user_id', $user)->paginate(18);
        return response()->json([
            'recipes' => $recipes,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'ingredients' => 'required|string',
            'highlighted_ingredient_ids' => 'nullable|array',
            'highlighted_ingredient_ids.*' => 'integer|exists:highlighted_ingredients,id',
            'servings' => 'required|integer',
            'steps' => 'required|string',
            'total_time' => 'required|integer',
            'image' => 'required|image',
            'video' => 'nullable|mimes:mp4|max:15000', // Max size 15MB and only mp4 format allowed

        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validatedData['image'] = $request->file('image')->store('recipes', 'public');
        }
   // Handle video upload
     if ($request->hasFile('video')) {
    $validatedData['video'] = $request->file('video')->store('videos', 'public');
    }
        // Add the authenticated user ID
        $validatedData['user_id'] = Auth::id();

        // Convert highlighted_ingredient_ids to JSON before saving
        $validatedData['highlighted_ingredient_ids'] = json_encode($request->highlighted_ingredient_ids ?? []);

        // Store the recipe
        $recipe = Recipe::create($validatedData);

        return response()->json(['message' => 'Recipe added successfully', 'recipe' => $recipe], 201);
    }

    public function show($id)
    {
        $user = Auth::user();
        $recipe = Recipe::with(['category', 'user'])->find($id);

        if (!$recipe) {
            return response()->json(['error' => 'Recipe not found'], 404);
        }

        $highlightedIngredientIds = json_decode($recipe->highlighted_ingredient_ids, true);

        if (!is_array($highlightedIngredientIds)) {
            return response()->json(['error' => 'Invalid highlighted ingredient IDs'], 400);
        }

        $highlightedIngredients = HighlightedIngredient::whereIn('id', $highlightedIngredientIds)->get();

        $recipe->image_url = $recipe->image ? url('storage/' . $recipe->image) : null;
        $recipe->video_url = $recipe->video ? url('storage/' . $recipe->video) : null;


        // Check if recipe has a user assigned
        if ($recipe->user) {
            $recipe->user->profile_picture_url = $this->getProfilePictureUrl($recipe->user);
        }

        // Set profile picture URL for authenticated user
        if ($user) {
            $user->profile_picture_url = $this->getProfilePictureUrl($user);
        }

        // Set image URLs for each highlighted ingredient
        $highlightedIngredients->each(function ($highlightedIngredient) {
            $highlightedIngredient->image_url = $highlightedIngredient->image ? url('storage/' . $highlightedIngredient->image) : null;
        });

        // Retrieve comments associated with the recipe
    $comments = Comment::with('user')->where('recipe_id', $id)->get();

    // Set profile picture URL for each comment's user
    $comments->each(function ($comment) {
        if ($comment->user) {
            $comment->user->profile_picture_url = $this->getProfilePictureUrl($comment->user);
        }
    });

        return response()->json([
            'recipe' => $recipe,
            'user' => $user,
            'highlighted_ingredients' => $highlightedIngredients,
            'comments' => $comments,
        ]);
    }


    public function update(Request $request, $id)
    {
        $recipe = Recipe::findOrFail($id);

        // Gather the data to be updated
        $data = $request->only(['title', 'category_id', 'ingredients', 'servings', 'steps', 'total_time']);
        $data['user_id'] = Auth::id();

        // Handle image upload if a new image is provided
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('recipes', 'public');
            $data['image_url'] = $imagePath;
        }

        // Update the recipe with the gathered data
        $recipe->update($data);

        return response()->json(['recipe' => $recipe]);
    }






    public function destroy(Recipe $recipe)
    {
        // Check if the authenticated user owns the recipe or is an admin
        if (Auth::user()->id === $recipe->user_id || Auth::user()->role === 'admin') {
            $recipe->delete();
            return response()->json(['message' => 'Recipe deleted successfully.']);
        } else {
            // Return an error response if the user doesn't have permission
            return response()->json(['error' => 'You do not have permission to delete this recipe.'], 403);
        }
    }

    public function deleteMultiple(Request $request)
    {
        $recipeIds = $request->input('ids');
        Recipe::whereIn('id', $recipeIds)->delete();

        return response()->json(['success' => true]);
    }
}
