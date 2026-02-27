<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>フリマ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/top.css') }}">
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
    <main class="main">
        <!-- タブ -->
        <div class="tab">
            <a href="/" class="tab__item {{ request('tab') !== 'mylist' ? 'active' : '' }}">おすすめ</a>
            <a href="/?tab=mylist" class="tab__item {{ request('tab') === 'mylist' ? 'active' : '' }}">マイリスト</a>
        </div>
        <!-- 仕切り線 -->
        <hr class="divider">
        <!-- 商品一覧 -->
        <div class="product-list">
            @foreach($items as $item)
                @if($item->purchase_exists)
                    <div class="product-card sold">
                @else
                    <a class="product-card" href="/item/{{ $item->id }}">
                @endif
                    <div class="product-card__image">
                        <img src="{{ $item->image_url }}" alt="{{ $item->name }}">
                        @if($item->purchase_exists)
                            <div class="sold-label">SOLD</div>
                        @endif
                    </div>
                    <p class="product-card__name">{{ $item->name }}</p>
                @if($item->purchase_exists)
                    </div>
                @else
                    </a>
                @endif
            @endforeach
        </div>
    </main>
</body>
</html>