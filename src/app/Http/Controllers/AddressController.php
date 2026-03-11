<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AddressRequest;
use App\Models\Item;

class AddressController extends Controller
{
    public function index($item_id)
    {
        $item = Item::findOrFail($item_id);
        return view('address',compact('item'));
    }

    public function change(AddressRequest $request,$item_id)
    {
        $user = Auth::user();

        $user->address()->updateOrCreate(
            ['user_id' => $user->id],
            $request->only(['postal_code','address','building'])
        );

        return redirect("/purchase/{$item_id}");
    }
}
