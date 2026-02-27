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
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* |-------------------------------------------------------------------------- 
| Email Verification Routes（手動追加） |-------------------------------------------------------------------------- */

//認証待機画面
Route::get('/email/verify',function() {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

//認証リンク処理
Route::get('email/verify/{id}/{hash}',function(EmailVerificationRequest $request){
    //email_verified_atに現在時刻を入れる
    $request->fulfill();
    return redirect('/setup');
})->middleware(['auth','signed'])->name('verification.verify');

//認証メール再送
Route::post('email/verification-notification',function (Request $request) {
    //署名付きURLの作成、メール送信
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message','認証メールを再送しました');
})->middleware(['auth','throttle:6,1'])->name('verification.send');

// Route::get('/', function () {
// return view('welcome');
// });
Route::get('/',[ItemController::class,'index']);
Route::get('/item/{item_id}',[ItemController::class,'show']);
Route::middleware('auth','verified')->group(function () {
    // 初期設定画面表示
    Route::get('/setup', [ProfileController::class, 'create']);
    // 保存処理
    Route::post('/setup', [ProfileController::class, 'update']);
    Route::get('/purchase/{item_id}',[PurchaseController::class,'index']);
    Route::post('/purchase/{item_id}',[PurchaseController::class,'purchase']);
    Route::get('/purchase/address/{item_id}',[AddressController::class,'index']);
    Route::patch('/purchase/address/{item_id}', [AddressController::class,'change']);
    Route::post('/comment/{item_id}',[ItemController::class,'comment']);
    Route::get('/sell',[ItemController::class,'create']);
    Route::post('/sell',[ItemController::class,'sell']);
    Route::get('/mypage',[ProfileController::class,'index']);
    Route::get('/mypage/profile',[ProfileController::class,'edit']);
    Route::post('/favorite/{item_id}',[ItemController::class,'toggle']);
});
