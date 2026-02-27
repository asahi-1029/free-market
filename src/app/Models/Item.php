<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'condition',
        'price',
        'brand',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function favoriteBy()
    {
        return $this->belongsToMany(
            User::class,
            'favorites',
            'item_id',
            'user_id'
        );
    }

    public function isFavoriteBy($user): bool
    {
        //ログインしてない人は、絶対に『いいね』してない」というルール
        if(!$user) return false;
        //中間テーブルに現在のユーザーIDが存在するかチェック
        return $this->favoriteBy()->where('user_id',$user->id)->exists();
    }

    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'category_items',
            'item_id',
            'category_id'
        );
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }

    public function getImageUrlAttribute()
    {
        if (str_starts_with($this->image,'http')) {
            return $this->image;
        }
        return asset('storage/' . $this->image);
    }

    public function getFormattedPriceAttribute()
    {
        return '￥' . number_format($this->price);
    }

    public function getConditionLabelAttribute()
    {
        return match($this->condition) {
            1 => '良好',
            2 => '目立った傷や汚れなし',
            3 => 'やや傷や汚れあり',
            4 => '状態が悪い',
            default => '不明',
        };
    }

    public function scopeKeywordSearch($query,$keyword)
    {
        if(!empty($keyword)) {
            $query->where('name','like', '%' . $keyword . '%');
        }
        return $query;
    }
}
