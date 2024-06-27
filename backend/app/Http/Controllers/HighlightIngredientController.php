<?php
namespace App\Http\Controllers;

use App\Models\HighlightedIngredient;
use Illuminate\Http\Request;

class HighlightIngredientController extends Controller
{
    public function store(Request $request)
    {
        // Validate request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|max:2048',
            'type' => 'required|string|max:255',

        ]);

        // Store the image file
        $imagePath = $request->file('image')->store('highlighted-ingredient-images', 'public');

        // Create a new highlighted ingredient instance
        $highlightedIngredient = new HighlightedIngredient();
        $highlightedIngredient->name = $validatedData['name'];
        $highlightedIngredient->type = $validatedData['type']; // Set type field
        $highlightedIngredient->image = $imagePath;
        $highlightedIngredient->save();

        // Return a success response
        return response()->json(['message' => 'Highlighted ingredient created successfully', 'ingredient' => $highlightedIngredient], 201);
    }

    public function index()
    {
        $highlightedIngredients = HighlightedIngredient::all();
        $highlightedIngredients->each(function($highlightedIngredient) {
            $highlightedIngredient->image = $highlightedIngredient->image ? url('storage/' . $highlightedIngredient->image) : null;
        });

        return response()->json($highlightedIngredients);
    }

}
