<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>フリマ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/address.css') }}">
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
        <div class="address">
            <div class="address__heading">
                <h2>住所の変更</h2>
            </div>
            <form class="form" action="/purchase/address/{{ $item->id }}" method="post">
                @csrf
                @method('PATCH')
                <div class="form__group">
                    <div class="form__group-title">
                        <label for="postal_code">郵便番号</label>
                    </div>
                    <div class="form__group-content">
                        <div class="form__input--text">
                            <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code') }}">
                        </div>
                        <div class="form__error">
                            @error('postal_code')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form__group">
                    <div class="form__group-title">
                        <label for="address">住所</label>
                    </div>
                    <div class="form__group-content">
                        <div class="form__input--text">
                            <input type="text" id="address" name="address" value="{{ old('address') }}">
                        </div>
                        <div class="form__error">
                            @error('address')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form__group">
                    <div class="form__group-title">
                        <label for="building">建物名</label>
                    </div>
                    <div class="form__group-content">
                        <div class="form__input--text">
                            <input type="text" id="building" name="building" value="{{ old('building') }}">
                        </div>
                        <div class="form__error">
                        <!--バリデーション機能を実装したら記述します。-->
                        </div>
                    </div>
                </div>
                <div class="form__button">
                    <button class="form__button-update">更新する</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>