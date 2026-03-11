<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;

class CommentTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_ログイン済みのユーザーはコメントを送信できる()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user)->post("comment/{$item->id}", [
            'content' => 'テストコメント'
        ]);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => 'テストコメント',
        ]);
    }

    public function test_ログイン前のユーザーはコメントを送信できない()
    {
        $item = Item::factory()->create();
        $response = $this->post("comment/{$item->id}", [
            'content' => 'テストコメント'
        ]);

        // ログイン画面にリダイレクトされること
        $response->assertRedirect('/login');

        // DBに保存されていないこと
        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'content' => 'テストコメント',
        ]);

    }

    public function test_コメントが入力されてない場合、バリデーションエラーになる()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->post("comment/{$item->id}", [
            'content' => '',
        ]);

        $response->assertSessionHasErrors('content');
    }

    public function test_コメントが256文字以上の場合、バリデーションエラーになる()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $longText = str_repeat('あ', 256);

        $response = $this->actingAs($user)->post("comment/{$item->id}", [
            'content' => $longText,
        ]);

        $response->assertSessionHasErrors('content');
    }
    
}
