<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Like;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_picture',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function recipes(): HasMany
    {
        return $this->hasMany(Recipe::class);
    }
    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class, 'likes', 'user_id', 'recipe_id')->withTimestamps();
    }


    public function getFullImageUrlAttribute()
    {
        $localPath = 'storage/' . $this->profile_picture;
        $apiPath = 'http://127.0.0.1:8001/storage/' . $this->profile_picture;

        if ($this->profile_picture && file_exists(public_path($localPath))) {
            return asset($localPath);
        } elseif ($this->profile_picture) {
            return $apiPath;
        } else {
            return null;
        }
    }


}
