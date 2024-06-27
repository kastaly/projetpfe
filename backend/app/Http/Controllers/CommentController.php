<?php
namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{

    public function index(){
        $comments=Comment::all();
        $comments->each(function ($comment) {
            if ($comment->user) {
                $comment->user->profile_picture_url = $this->getProfilePictureUrl($comment->user);
            }
        });
        return response()->json(['comments' => $comments]);
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
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'recipe_id' => 'required|exists:recipes,id',
            'content' => 'required|string',
            'parent_comment_id' => 'nullable|exists:comments,id',
        ]);

        // Create a new comment
        $comment = Comment::create($request->all());

        // Load the user relationship for the newly created comment
        $comment->load('user');

        return response()->json(['comment' => $comment], 201);
    }

    public function showCommentsForRecipe($recipeId)
    {
        // Fetch comments related to the specified recipe
        $comments = Comment::where('recipe_id', $recipeId)->with('user')->get();

        // Ensure user profile picture URLs are included
        $comments->each(function ($comment) {
            $comment->user->profile_picture_url = $this->getProfilePictureUrl($comment->user);
        });

        return response()->json(['comments' => $comments]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        // Return a response
        return response()->json(['message' => 'Comment deleted successfully'], 200);
    }
}
