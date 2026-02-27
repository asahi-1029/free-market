<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>フリマ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/setup.css') }}" />
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
        <div class="setting">
            <div class="setting__heading">
                <h2>プロフィール設定</h2>
            </div>
            <form class="form" method="post" action="/setup" enctype="multipart/form-data">
                @csrf
                <div class="profile">
                    <div class="profile__image" id="previewWrap">
                        @if($user->profile_image_url)
                        <img id="preview" src="{{ $user->profile_image_url }}" alt="">
                        @endif
                    </div>
                    <label class="profile__button">画像を選択する
                    <input type="file" name="profile_image" accept="image/*" hidden id="imageInput">
                    </label>
                </div>
                <div class="form__group">
                    <div class="form__group-title">
                        <label for="name">ユーザー名</label>
                    </div>
                    <div class="form__group-content">
                        <div class="form__input--text">
                            <input type="text" id="name" name="name" value="{{ old('name') }}">
                        </div>
                        <div class="form__error">
                            @error('name')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
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
                            @error('building')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form__button">
                    <button class="form__button-update" type="submit">更新する</button>
                </div>
            </form>
        </div>
    </main>
    <script>
    document.getElementById('imageInput').addEventListener('change', function(e) {

        // ファイル取得。files=選択したファイル一覧　[0]=1枚目
        const file = e.target.files[0];
        if (!file) return;

        // 画像を「画面で表示できる形式」に変換する
        const reader = new FileReader();

        // 画像の変換終わった瞬間に動く
        reader.onload = function(event) {
            const wrap = document.getElementById('previewWrap');
            // 中身をリセット（再選択対応）
            wrap.innerHTML = '';
            // img生成
            const img = document.createElement('img');
            img.src = event.target.result;
            img.id = 'preview';
            wrap.appendChild(img);
        }
        reader.readAsDataURL(file);
    });
    </script>
</body>
</html>