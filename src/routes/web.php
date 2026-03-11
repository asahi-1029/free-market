<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AddressController;

/*
|--------------------------------------------------------------------------
| Email Verification Routes
|--------------------------------------------------------------------------
*/

// 認証待機画面
Route::get('/email/verify', function() {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// 認証リンク処理
Route::get('email/verify/{id}/{hash}', function(EmailVerificationRequest $request){
    $request->fulfill();
    return redirect('/setup');
})->middleware(['auth','signed'])->name('verification.verify');

// 認証メール再送
Route::post('email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message','認証メールを再送しました');
})->middleware(['auth','throttle:6,1'])->name('verification.send');

/*
|--------------------------------------------------------------------------
| Public Routes（未認証でもアクセス可能）
|--------------------------------------------------------------------------
*/

// トップページ（商品一覧）
Route::get('/', [ItemController::class, 'index']);

// 商品詳細
Route::get('/item/{item_id}', [ItemController::class, 'show']);

/*
|--------------------------------------------------------------------------
| Authenticated Routes（認証必須）
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    
    // プロフィール関連
    Route::get('/setup', [ProfileController::class, 'create']);
    Route::post('/setup', [ProfileController::class, 'update']);
    Route::get('/mypage', [ProfileController::class, 'index']);
    Route::get('/mypage/profile', [ProfileController::class, 'edit']);
    
    // 商品出品
    Route::get('/sell', [ItemController::class, 'create']);
    Route::post('/sell', [ItemController::class, 'sell']);
    
    // 購入関連
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'index']);
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'purchase']);
    
    // 配送先変更
    Route::get('/purchase/address/{item_id}', [AddressController::class, 'index']);
    Route::patch('/purchase/address/{item_id}', [AddressController::class, 'change']);
    
    // コメント
    Route::post('/comment/{item_id}', [ItemController::class, 'comment']);
    
    // お気に入り
    Route::post('/favorite/{item_id}', [ItemController::class, 'toggle']);
});
