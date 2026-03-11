<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\PaymentMethod;
use App\Models\Address;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_購入が完了する()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        $payment = PaymentMethod::factory()->create();
        $address = Address::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)->post("/purchase/{$item->id}", [
            'payment_method_id' => $payment->id,
            'postal_code' => $address->postal_code,
            'address' => $address->address,
            'building' => $address->building,
        ]);

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'price' => $item->price,
            'payment_method_id' => $payment->id,
            'postal_code' => $address->postal_code,
            'address' => $address->address,
            'building' => $address->building,
        ]);
    }

    public function test_購入した商品が「sold」として表示されている()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        $payment = PaymentMethod::factory()->create();
        $address = Address::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)->post("/purchase/{$item->id}", [
            'payment_method_id' => $payment->id,
            'postal_code' => $address->postal_code,
            'address' => $address->address,
            'building' => $address->building,
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertSee($item->name);
        $response->assertSee('SOLD');
    }

    public function test_購入した商品がプロフィールの購入した商品一覧に追加されている()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        $payment = PaymentMethod::factory()->create();
        $address = Address::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)->post("/purchase/{$item->id}", [
            'payment_method_id' => $payment->id,
            'postal_code' => $address->postal_code,
            'address' => $address->address,
            'building' => $address->building,
        ]);

        $response = $this->actingAs($user)->get('/mypage?page=buy');

        $response->assertSee($item->name);
    }

    public function test_選択した支払い方法が正しく反映される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        $payment1 = PaymentMethod::factory()->create(['name' => 'コンビニ払い']);
        $payment2 = PaymentMethod::factory()->create(['name' => 'カード払い']);
        $address = Address::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)->post("/purchase/{$item->id}", [
            'payment_method_id' => $payment2->id,
            'postal_code' => $address->postal_code,
            'address' => $address->address,
            'building' => $address->building,
        ]);

        $this->assertDatabaseHas('purchases', [
            'item_id' => $item->id,
            'payment_method_id' => $payment2->id
        ]);

        $response = $this->actingAs($user)->get("/purchase/{$item->id}");
        $response->assertSee('カード払い');

    }
}
