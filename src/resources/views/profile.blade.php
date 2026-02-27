<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    <title>フリマ</title>
</head>
<body>
    <header class="header">
        <div class="header__inner">
            <img class="header__logo" src="{{ asset('img/COACHTECHヘッダーロゴ.png') }}" alt="COACHTECH">
            <form action="/" method="GET" class="header__search-wrap">
                <input type="hidden" name="tab" value="{{ request('tab') }}">
                <input type="text" name="keyword" class="header__search" placeholder="なにをお探しですか？" value="{{ request('keyword') }}">
            </form>
            <nav class="header__actions">
                <form action="/logout" method="post">
                @csrf
                    <button type="submit" class="header__link-logout">ログアウト</button>
                </form>
                <a href="/mypage" class="header__link-mypage">マイページ</a>
                <a href="/sell" class="header__link-sell">出品</a>
            </nav>
        </div>
    </header>
    <main>
        <div class="profile">
            <div class="profile__image">
                @if($user->profile_image_url)
                <img src="{{ $user->profile_image_url }}" alt="{{ $user->name }}">
                @endif
            </div>
            <p class="profile__name">{{ $user->name }}</p>
            <div class="profile__edit">
                <a href="/mypage/profile">プロフィールを編集</a>
            </div>
        </div>
        <div class="tab">
            <a href="/mypage?page=sell" class="tab__item {{ $page === 'sell' ? 'active' : '' }}">出品した商品</a>
            <a href="/mypage?page=buy" class="tab__item {{ $page === 'buy' ? 'active' : '' }}">購入した商品</a>
        </div>
        <hr class="divider">
        <div class="product-list">
            @foreach($items as $item)
                @if($item->purchase_exists)
                <div class="product-card sold">
                    <div class="product-card__image">
                        <img src="{{ $item->image_url }}" alt="">
                        <div class="sold-label">SOLD</div>
                    </div>
                    <p class="product-card__name">{{ $item->name }}</p>
                </div>
                @else
                <a href="/item/{{ $item->id }}" class="product-card">
                    <div class="product-card__image">
                        <img src="{{ $item->image_url }}" alt="{{ $item->name }}">
                    </div>
                    <p class="product-card__name">{{ $item->name }}</p>
                </a>
                @endif
            @endforeach
        </div>
    </main>
</body>
</html>