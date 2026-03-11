<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class GoodTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_いいねすると商品に登録され合計値が増える()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // ログイン
        $this->actingAs($user);
        $this->post("/favorite/{$item->id}");

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        //商品詳細ページ確認
        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);

        //いいね数が1になってるか確認
        $response->assertSee(1);
    }

    public function test_追加済みのアイコンは色が変化する()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        //ログイン
        $this->actingAs($user);

        //商品詳細ページ
        $this->get("/item/{$item->id}");

        //いいね登録
        $this->post("/favorite/{$item->id}");

        // 再度ページ取得
        $response = $this->get("/item/{$item->id}");
        $response->assertSee('ハートロゴ_ピンク.png');
    }

    public function test_いいねを再度押すと解除される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        //ログイン
        $this->actingAs($user);
        //いいね登録
        $this->post("/favorite/{$item->id}");
        
        //DBに保存されているか確認
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        //いいね解除
        $this->post("/favorite/{$item->id}");
        //ページ取得
        $response = $this->get("/item/{$item->id}");
        $response->assertSee(0);
        $response->assertSee('ハートロゴ_デフォルト.png');
    }
}
