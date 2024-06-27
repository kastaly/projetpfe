<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
   

    /**
     * Update the user's profile picture.
     */
    public function updateProfilePicture(Request $request): JsonResponse
    {
        // Validate the request
        $request->validate([
            'profile_picture' => ['required', 'image', 'max:2048'],
        ]);

        // Get the authenticated user
        $user = $request->user();

        // Process user-uploaded image as before
        $imagePath = $request->file('profile_picture')->storePublicly('profile-images', 'public');

        // Update the user's profile picture
        $user->profile_picture = $imagePath;
        $user->save();

        return response()->json(['message' => 'Profile picture updated successfully']);
    }

    /**
     * Update the user's profile.
     */
    public function update(ProfileUpdateRequest $request): JsonResponse
    {
        // Update the user's profile
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return response()->json(['message' => 'Profile updated successfully']);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): JsonResponse
    {
        // Validate the request
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        // Get the authenticated user
        $user = $request->user();

        // Check if the authenticated user has the 'admin' role
        if ($user->role !== 'admin') {
            // Return an error message indicating that the user doesn't have permission
            return response()->json(['error' => 'You do not have permission to delete users.'], 403);
        }

        // Attempt to logout the user
        Auth::logout();

        // Delete the user
        if ($user && $user->role == 'admin') {
            $user->delete();
        }

        // Invalidate the session and regenerate the token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Return a success message after successful deletion
        return response()->json(['message' => 'User deleted successfully']);
    }
}
