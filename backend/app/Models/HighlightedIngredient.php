<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HighlightedIngredient extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'image','type'];

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
