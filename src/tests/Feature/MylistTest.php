<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Purchase;
use App\Models\PaymentMethod;

class MylistTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_いいねした商品がマイリストに表示される()
    {
        $user = User::factory()->create();

        $otherUser = User::factory()->create();
        $item1 = Item::factory()->create(['user_id' => $otherUser->id]);
        $item2 = Item::factory()->create(['user_id' => $otherUser->id]);

        //いいね登録
        $user->favoriteItems()->attach($item1->id);

        $this->actingAs($user);
        $response = $this->get('/?tab=mylist');
        $response->assertStatus(200);

        $response->assertSee($item1->name);
        $response->assertDontSee($item2->name);
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

        $user->favoriteItems()->attach($item->id);

        $response = $this->actingAs($user)->get('/?tab=mylist');
        $response->assertStatus(200);

        $response->assertSee('SOLD');
    }

    public function test_未認証ユーザーのマイリストは空()
    {
        $response = $this->get('/?tab=mylist');
        $response->assertStatus(200);

        $response->assertDontSee('product-card');
    }
}
