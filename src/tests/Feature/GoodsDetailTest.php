<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;
use App\Models\Category;

class GoodsDetailTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_商品詳細ページに必要な情報が表示される()
    {
        //ユーザーと商品を作成
        $user = User::factory()->create();
        $item = Item::factory()->create();

        //コメントを作成
        $commentUser = User::factory()->create();
        $comment = Comment::factory()->create([
            'user_id' => $commentUser->id,
            'item_id' => $item->id,
        ]);

        //いいね登録
        $user->favoriteItems()->attach($item->id);

        // 複数カテゴリ作成して商品に紐付け
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();
        $item->categories()->attach([$category1->id, $category2->id]);

        //商品詳細ページを取得
        $response = $this->actingAs($user)->get("/item/{$item->id}");
        $response->assertStatus(200);

        $response->assertSee($item->name);
        $response->assertSee($item->brand);
        $response->assertSee($item->formatted_price);
        $response->assertSee($item->comments()->count());
        $response->assertSee($item->favoriteBy()->count());
        $response->assertSee($item->description);

        $response->assertSee($item->condition_label);

        $response->assertSee($commentUser->name);
        $response->assertSee($comment->content);

        $response->assertSee($item->image_url);

        // カテゴリ情報（複数）
        foreach ($item->categories as $category) {
            $response->assertSee($category->name);
        }
    }

}
