<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'ingredients',
        'highlighted_ingredient_ids',
        'servings',
        'steps',
        'total_time',
        'image',
        'user_id',
    ];

    protected $casts = [
        'highlighted_ingredient_ids' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function likes()
{
    return $this->hasMany(Like::class);
}


    public function highlightedIngredients()
    {
        return $this->hasMany(HighlightedIngredient::class, 'id', 'highlighted_ingredient_ids');
    }
}
