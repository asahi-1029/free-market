<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'payment_method_id',
        'price',
        'postal_code',
        'address',
        'building',
    ];

    // 購入者
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 購入商品
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // 支払い方法
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
