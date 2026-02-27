<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileRequest;

class ProfileController extends Controller
{
    //初回ログインプロフィール設定画面の表示
    public function create()
    {
        $user = Auth::user();
        return view('setup', compact('user'));
    }

    //プロフィール設定
    public function update(ProfileRequest $request)
    {
        //今ログインしてる人を取得
        $user = Auth::user();

        // 名前更新
        $user->name = $request->name;

        // 画像がある場合のみ保存
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profiles','public');
            //profilesフォルダに保存して、publicディスクを使う
            $user->profile_image = $path;
        }

        $user->save();

        // 住所保存（住所がなければ作るしあれば更新）
        // 第1引数は検索条件
        // 第2引数は保存内容
        $user->address()->updateOrCreate(
            ['user_id' => $user->id],
            $request->only(['postal_code','address','building'])
        );

        return redirect('/');
    }

    //プロフィール画面の表示
    public function index(Request $request)
    {
        //今ログインしてる人を取得
        $user = Auth::user();
        $page = $request->query('page','sell');
        //pageがキー、値がbuy。なければsell

        if ($page === 'buy') {
            $items = $user->purchasedItems()->withExists('purchase')->get();
        } else {
            $items = $user->items()->withExists('purchase')->get();
        }
        return view('profile',compact('user','items','page'));
    }

    //プロフィール編集画面の表示
    public function edit()
    {
        //今ログインしてる人を取得
        $user = Auth::user();
        $address = $user->address;
        return view('profileedit',compact('user','address'));
    }
}
