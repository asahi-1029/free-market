<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
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
        'profile_image'
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
    ];

    public function address()
    {
        return $this->hasOne(Address::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function favoriteItems()
    {
        return $this->belongsToMany(
            Item::class,
            'favorites',
            'user_id',
            'item_id',
        );
    }

    public function purchasedItems()
    {
        return $this->belongsToMany(
            Item::class,
            'purchases',   // 中間テーブル
            'user_id',     // purchasesテーブルのuser側カラム
            'item_id'      // purchasesテーブルのitem側カラム
        );
    }

    public function getProfileImageUrlAttribute()
    {
        if (str_starts_with($this->profile_image, 'http')) {
            return $this->profile_image;
        }
        return asset('storage/' . $this->profile_image);
    }
}
