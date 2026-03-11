<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\PaymentMethod;
use App\Models\Purchase;
use App\Http\Requests\PurchaseRequest;

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
        $item = Item::findOrFail($item_id);

        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method_id' => $request->payment_method_id,
            'price' => $item->price,
            'address' => $request->address,
            'postal_code' => $request->postal_code,
            'building' => $request->building,
        ]);

        return redirect('/');
    }
    
}
