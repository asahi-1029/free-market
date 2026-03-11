<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\PaymentMethod;

class ProfileTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_プロフィールページに必要な情報が表示される()
    {
        $user = User::factory()->create([
            'name' => 'テスト',
            'profile_image' => 'test_image.png',
        ]);
        $this->actingAs($user);

        //  出品した商品を作成
        $item1 = Item::factory()->create([
            'user_id' => $user->id,
            'name' => '出品商品1',
        ]);
        $item2 = Item::factory()->create([
            'user_id' => $user->id,
            'name' => '出品商品2',
        ]);

        $payment = PaymentMethod::factory()->create();

        $purchaseItem = Item::factory()->create();
        // 現在のユーザーが $purchaseItem を購入
        $user->purchasedItems()->attach($purchaseItem->id, [
            'payment_method_id' => $payment->id,
            'price' => $purchaseItem->price,
            'postal_code' => '111-1111',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'マンション',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/mypage?page=buy');
        $response->assertStatus(200);
        $response->assertSee('テスト');
        $response->assertSee('test_image.png');
        $response->assertSee($purchaseItem->name);

        $response2 = $this->actingAs($user)->get('/mypage?page=sell');
        $response2->assertStatus(200);
        $response2->assertSee('テスト');
        $response2->assertSee('storage/test_image.png');
        $response2->assertSee('出品商品1');
        $response2->assertSee('出品商品2');
    }

    public function test_プロフィール編集画面に初期値が表示される()
    {
        // ユーザー作成＆ログイン
        $user = User::factory()->create([
            'name' => 'テスト太郎',
            'profile_image' => 'test_image.png',
            'email' => 'test@example.com',
        ]);

        // 住所も登録しておく
        $user->address()->create([
            'postal_code' => '111-1111',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'マンション',
        ]);

        $this->actingAs($user);

        // プロフィール編集ページを開く
        $response = $this->get('/mypage/profile');

        $response->assertStatus(200);
        $response->assertSee('テスト太郎');
        $response->assertSee('111-1111');
        $response->assertSee('東京都渋谷区1-1-1');
        $response->assertSee('マンション');
        $response->assertSee('storage/test_image.png');
    }
}
