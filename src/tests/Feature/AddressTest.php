<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Address;
use App\Models\PaymentMethod;

class AddressTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_登録した住所が商品購入画面に正しく反映される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user)->patch("/purchase/address/{$item->id}", [
            'address' => '東京都渋谷区1-1-1',
            'postal_code' => '111-1111',
            'building' => 'マンション',
        ]);

        $this->assertDatabaseHas('addresses', [
            'user_id' => $user->id,
            'postal_code' => '111-1111',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'マンション',
        ]);

        $response = $this->actingAs($user)->get("/purchase/{$item->id}");
        $response->assertSee('東京都渋谷区1-1-1');
        $response->assertSee('111-1111');
        $response->assertSee('マンション');
    }

    public function test_正しく送信先住所が紐づいている()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        $payment = PaymentMethod::factory()->create();

        $this->actingAs($user)->patch("/purchase/address/{$item->id}", [
            'address' => '東京都渋谷区1-1-1',
            'postal_code' => '111-1111',
            'building' => 'マンション',
        ]);

        $address = $user->address()->first();

        $this->actingAs($user)->post("/purchase/{$item->id}", [
            'payment_method_id' => $payment->id,
            'postal_code' => $address->postal_code,
            'address' => $address->address,
            'building' => $address->building,
        ]);

        $this->assertDatabaseHas('addresses', [
            'address' => '東京都渋谷区1-1-1',
            'postal_code' => '111-1111',
            'building' => 'マンション',
        ]);
    }
    
}
