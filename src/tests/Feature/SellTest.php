<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\UploadedFile;

class SellTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_出品画面の各項目が正しく保存されている()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $category = Category::factory()->create();

        $data = [
            'name' => 'テスト',
            'brand' => 'テストブランド',
            'description' => 'テスト説明',
            'price' => 3000,
            'condition' => 1,
            'category_ids' => [$category->id],
            'image' => UploadedFile::fake()->image('test.jpg')
        ];

        $response = $this->post('/sell', $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'name' => 'テスト',
            'brand' => 'テストブランド',
            'description' => 'テスト説明',
            'price' => 3000,
            'condition' => 1,
        ]);

        $this->assertDatabaseHas('category_items', [
            'category_id' => $category->id,
        ]);
    }
}
