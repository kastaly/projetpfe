<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {

        $query = User::query();

        // Check if there's a search query
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'LIKE', "%$search%");
        };

        $users = $query->get();
        $users->each(function($user) {
            $user->profile_picture_url = $this->getProfilePictureUrl($user);
        });

        $user = Auth::user();
        $user->profile_picture_url = $this->getProfilePictureUrl($user);

        $recipes = Recipe::where('user_id', $user->id)->with(['category', 'user', 'comments','likes'])
        ->orderBy('created_at', 'desc')
        ->get();
        $recipes->each(function($recipe) {
            $recipe->image_url = $recipe->image ? url('storage/' . $recipe->image) : null;
        });

        $usedCategories = Category::whereHas('recipes', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        $totalComments = $recipes->sum(function($recipe) {
            return $recipe->comments->count();
        });
        $totalLikes = $recipes->sum(function($recipe) {
            return $recipe->likes->count();
        });

        return response()->json([
            'user' => $user,
            'users'=>$users,
            'usedCategories' => $usedCategories,
            'recipes' => $recipes,
            'totalComments' => $totalComments,
            'totalLikes'=>$totalLikes,
            'message' => 'Welcome to User Dashboard'
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
    public function show($userId): JsonResponse
    {
        $user = User::with('recipes.category')->findOrFail($userId);
        $recipes = $user->recipes;
        $recipes->each(function($recipe) {
            $recipe->image_url = $recipe->image ? url('storage/' . $recipe->image) : null;
        });
        $user->profile_picture_url = $this->getProfilePictureUrl($user);
        $totalComments = $recipes->sum(function($recipe) {
            return $recipe->comments->count();
        });
        $totalLikes = $recipes->sum(function($recipe) {
            return $recipe->likes->count();
        });
        return response()->json([
            'user' => $user,
            'recipes' => $recipes,
            'totalComments' => $totalComments,
            'totalLikes' =>$totalLikes,

        ]);
    }

}
