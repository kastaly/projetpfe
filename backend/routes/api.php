<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\HighlightIngredientController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();

});


Route::middleware(['auth:sanctum'])->group(function () {
   Route::post('/recipes/store', [RecipeController::class, 'store']);
    Route::get('/myrecipes', [RecipeController::class, 'myRecipes']);
    Route::put('/recipes/{id}/update', [RecipeController::class, 'update']);
    Route::delete('/recipes/{recipe}', [RecipeController::class, 'destroy']);
    Route::delete('/recipes/delete-multiple', [RecipeController::class, 'deleteMultiple']);
    Route::get('/user/recipes', [RecipeController::class, 'showData']);
    Route::get('/admin-dashboard', [AdminController::class, 'index']);
    Route::get('/dashboard', [UserController::class, 'index']);

    //for profile
    Route::get('/user/{userId}', [UserController::class, 'show']);
    Route::post('/profile/update',[ProfileController::class, 'update']);
//for comments
Route::get('/comments/recipe/{recipeId}', [CommentController::class, 'showCommentsForRecipe']);
    Route::post('/comments', [CommentController::class, 'store']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);


});

Route::post('/recipes/{recipe}/like', [LikeController::class, 'like'])->middleware('auth:sanctum');
Route::delete('/recipes/{recipe}/unlike', [LikeController::class, 'unlike'])->middleware('auth:sanctum');


    Route::get('/recipes/{recipe}/like-status', [LikeController::class, 'getLikeStatus']);


    Route::post('/highlighted-ingredients', [HighlightIngredientController::class, 'store']);
    Route::get('/highlighted-ingredients', [HighlightIngredientController::class, 'index']);



    Route::get('/recipes', [RecipeController::class, 'index']);
    Route::get('/recipes/{id}', [RecipeController::class, 'show']);

    Route::get('/show-Comments', [CommentController::class, 'index']);

Route::get('/categories', [RecipeController::class, 'getCategories']);

require __DIR__.'/auth.php';
