<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;

class SearchTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_商品名で部分一致検索ができる()
    {
        $item1 = Item::factory()->create(['name' => '赤いシャツ']);
        $item2 = Item::factory()->create(['name' => '青いシャツ']);
        $item3 = Item::factory()->create(['name' => '赤い靴']);

        $keyword = '赤い';

        $response = $this->get('/?keyword=' . urlencode($keyword));
        $response->assertStatus(200);

        $response->assertSee('赤いシャツ');
        $response->assertSee('赤い靴');
        $response->assertDontSee('青いシャツ');
    }
    
    public function test_検索キーワードがマイリストでも保持される()
    {
        $user = User::factory()->create();
        $item1 = Item::factory()->create(['name' => '赤いシャツ']);
        $item2 = Item::factory()->create(['name' => '青いシャツ']);

        $keyword = '赤い';

        // 1. ホームページで検索
        $response = $this->actingAs($user)
                        ->get('/?keyword=' . urlencode($keyword));

        $response->assertStatus(200);

        // 検索結果ページにキーワードがフォームに保持されている
        $response->assertSee('value="' . $keyword . '"', false);

        // 2. マイリストタブに遷移
        // お気に入り登録
        $user->favoriteItems()->attach($item1->id);
        $response2 = $this->actingAs($user)
                        ->get('/?tab=mylist&keyword=' . urlencode($keyword));

        $response2->assertStatus(200);

        // マイリストでもキーワードがフォームに保持されている
        $response2->assertSee('value="' . $keyword . '"', false);

        // 検索結果として赤いシャツだけが表示される
        $response2->assertSee('赤いシャツ');
        $response2->assertDontSee('青いシャツ');
    }
}
