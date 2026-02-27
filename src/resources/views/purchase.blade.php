<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>フリマ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
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
        <form action="/purchase/{{ $item->id }}" method="post">
        @csrf
            <div class="purchase__container">
                <!-- 左カラム -->
                <div class="purchase__left">
                    <!-- 商品情報 -->
                    <section class="purchase__item">
                        <div class="item__image">
                            <img src="{{ $item->image_url }}">
                        </div>
                        <div class="item__info">
                            <p class="item__name">{{ $item->name }}</p>
                            <p class="item__price">{{ $item->formatted_price }}</p>
                        </div>
                    </section>
                    <hr class="divider">
                    <!-- 支払い方法 -->
                    <section class="purchase__payment">
                        <h3>支払い方法</h3>
                        <select name="payment_method" id="payment-method">
                            <option value="">選択してください</option>
                            @foreach($methods as $method)
                                <option value="{{ $method->id }}"{{ old('payment_method') == $method->id ? 'selected' : ''}}>
                                    {{ $method->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form__error">
                            @error('payment_method')
                            {{ $message }}
                            @enderror
                        </div>
                    </section>
                    <hr class="divider">
                    <!-- 配送先 -->
                    <section class="purchase__address">
                        <div class="address__header">
                            <h3>配送先</h3>
                            <a href="/purchase/address/{{ $item->id }}">変更する</a>
                        </div>
                        @if($address)
                            <p>〒 {{ $address->postal_code }}<br>
                            {{ $address->address}}{{ $address->building}}
                            </p>
                        @endif
                        @if($address)
                            <input type="hidden" name="postal_code" value="{{ $address->postal_code }}" >
                            <input type="hidden" name="address" value="{{ $address->address }}" >
                            <input type="hidden" name="building" value="{{ $address->building }}" >
                        @endif
                        @if($errors->has('postal_code') || $errors->has('address'))
                            <div class="form__error">
                                {{ $errors->first('postal_code') ?? $errors->first('address') }}
                            </div>
                        @endif
                    </section>
                </div>
                <div class="purchase__right">
                    <div class="summary">
                        <div class="summary__row">
                            <span>商品代金</span>
                            <span>{{ $item->formatted_price }}</span>
                        </div>
                        <div class="summary__row">
                            <span>支払い方法</span>
                            <span id="selected-payment">未選択</span>
                        </div>
                    </div>
                    <div class="form__button">
                        <button type="submit" class="form__button-purchase">購入する</button>
                    </div>
                </div>
            </div>
        </form>
    </main>
    <script>
        document.getElementById('payment-method').addEventListener('change', function() {
        //payment-methodが変わった瞬間に処理を実行する

            const selectedText =
                this.options[this.selectedIndex].text;
                //thisは操作されたselect自身
                //this.optionsはselectの中のoption一覧
                //this.selectedindexは今選ばれてる番号
                //.textは表示されてる文字

            document.getElementById('selected-payment')
                .textContent = selectedText;

        });
    </script>
</body>
</html>