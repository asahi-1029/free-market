<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Purchase;
use App\Models\PaymentMethod;

class ItemTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_全商品が表示される()
    {
        $user = User::factory()->create();
        $items = Item::factory()->count(3)->create();
        //商品一覧画面にアクセス
        $response = $this->get('/');
        $response->assertStatus(200);

        // ④ 商品名が表示されているか
        foreach($items as $item) {
            $response->assertSee($item->name);
        }
    }

    public function test_購入済み商品に「Sold」のラベルが表示される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
         // --- PaymentMethod を手動で作成 ---
        $paymentMethod = PaymentMethod::create([
            'name' => 'コンビニ払い',
        ]);

        // --- 購入済みにする ---
        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method_id' => $paymentMethod->id,
            'price' => $item->price,
            'postal_code' => '1234567',
            'address' => '東京都新宿区西新宿1-1-1',
            'building' => null,
        ]);

        //商品一覧画面にアクセス
        $response = $this->get('/');
        $response->assertStatus(200);

        $response->assertSee('SOLD');
    }

    public function test_自分が出品した商品は一覧に表示されない()
    {
        $user = User::factory()->create();
        $myItem = Item::factory()->create([
            'user_id' => $user->id,
        ]);

        $otherUser = User::factory()->create();
        $otherItem = Item::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $this->actingAs($user);
        $response = $this->get('/');
        $response->assertStatus(200);

        $response->assertDontSee($myItem->name);
        $response->assertSee($otherItem->name);
    }
    
}
