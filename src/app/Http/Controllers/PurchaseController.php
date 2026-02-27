<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\PaymentMethod;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Http\Requests\PurchaseRequest;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PurchaseController extends Controller
{
    public function index($item_id)
    {
        $item = Item::findOrFail($item_id);
        $address = Auth::user()->address;
        $methods = PaymentMethod::all();
        return view('purchase',compact('item','address','methods'));
    }

    public function purchase(PurchaseRequest $request,$item_id)
    {
        //ログインユーザー取得
        $user = Auth::user();

        //商品取得
        $item = Item::findOrFail($request->item_id);
    
        $address = $user->address;

        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method_id' => $request->payment_method,
            'price' => $item->price,
            'address' => $address->address,
            'postal_code' => $address->postal_code,
            'building' => $address->building,
        ]);

        return redirect('/');
    }
    
}
