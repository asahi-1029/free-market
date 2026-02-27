<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Comment;
use App\Models\Category;
use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\CommentRequest;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    //商品一覧ページの表示と商品検索処理
    public function index(Request $request)
    {
        if ($request->tab === 'mylist') {
            if(!Auth::check())
            {
                $items = collect();
                return view('top',compact('items'));
            }
            //まだ実行してない
            $query = Auth::user()->favoriteItems()->withExists('purchase');
        } else {
            //まだ実行してない
            $query = Item::withExists('purchase');
        }

        $query->keywordSearch($request->keyword);

        $items = $query->get();
        return view('top', compact('items'));
    }

    //商品詳細ページの表示
    public function show($item_id)
    {
        $item = Item::with(['categories','comments.user'])->findOrFail($item_id);
        //// findOrFail は、もしIDが見つからなかったら「404 Not Found」を出してくれる親切なメソッド
        return view('goodsdetail',compact('item'));
    }

    public function comment(CommentRequest $request, $item_id)
    {
        Comment::create([
            'content' => $request->content,
            'user_id' => auth()->id(), // ログインユーザー
            'item_id' => $item_id,
        ]);

        return redirect("/item/{$item_id}");
    }

    //出品画面の表示
    public function create()
    {
        $categories = Category::all();

        //itemsテーブルからconditionの重複なしリストを取得
        //distinct()は同じ状態は一回だけ取得
        //orderBy('condition')は昇順でソート
        $conditions = Item::select('condition')->distinct()->orderBy('condition')->get();
        return view('sell',compact('categories','conditions'));
    }

    //出品機能
    public function sell(ExhibitionRequest $request)
    {
        $data = $request->validated();

        $data['user_id'] = Auth::id();

        // ① 画像保存
        $path = $request->file('image')->store('items','public');
        $data['image'] = $path;

        // ② category_idsはitemsに不要なので外す
        $categoryIds = $data['category_ids'];
        unset($data['category_ids']);

        // ③ item保存
        $item = Item::create($data);

        // ④ 中間テーブル保存
        $item->categories()->sync($categoryIds);

        return redirect('/');
    }

    //いいね機能
    public function toggle($item_id)
    {
        $user = Auth::user();

        //toggle()でデータがなければ保存し、あれば削除する
        $user->favoriteItems()->toggle($item_id);

        return redirect("item/{$item_id}");
    }

}
