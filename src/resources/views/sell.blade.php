<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>フリマ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sell.css') }}">
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
        <div class="sell">
            <div class="sell__heading">
                <h2>商品の出品</h2>
            </div>
            <form class="form" action="/sell" method="post" enctype="multipart/form-data">
                @csrf
                <!-- 商品画像 -->
                <div class="form__group">
                    <div class="form__group-title">
                        <label>商品画像</label>
                    </div>
                    <div class="form__group-content">
                        <label class="form__image">
                            <img id="preview" style="display: none;">
                            <span id="select-button" class="form__image-button">画像を選択する</span>
                            <input type="file" name="image" id="imageInput" accept="image/*" hidden>
                        </label>
                        <div class="form__error">
                            @error('image')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
                <!-- 商品の詳細 -->
                <div class="product-detail">
                    <h2>商品の詳細</h2>
                    <hr class="divider">
                    <!-- カテゴリー -->
                    <div class="form__group">
                        <div class="form__group-title">
                            <label>カテゴリー</label>
                        </div>
                        <div class="form__group-content">
                            <div class="category-list">
                                @foreach($categories as $category)
                                <label class="category-item">
                                    <input type="checkbox" name="category_ids[]" value="{{ $category->id }}" {{ in_array($category->id, old('category_ids', [])) ? 'checked' : '' }}>{{ $category->name }}
                                </label>
                                @endforeach
                            </div>
                            <div class="form__error">
                                @error('category_ids[]')
                                {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>
                    <!-- 商品の状態 -->
                    <div class="form__group"> <div class="form__group-title"> 
                        <label>商品の状態</label> 
                    </div> 
                    <div class="form__group-content"> 
                        <div class="form__select-wrap"> 
                            <select class="form__select" name="condition"> 
                                <option value="">選択してください</option> 
                                    @foreach($conditions as $condition) 
                                        <option value="{{ $condition->condition }}" {{ old('condition') == $condition->condition ? 'selected' : ''}}>   
                                            @php 
                                                switch($condition->condition) {
                                                    case 1: echo '良好'; break; 
                                                    case 2: echo '目立った傷や汚れなし'; break; 
                                                    case 3: echo 'やや傷や汚れあり'; break; 
                                                    case 4: echo '状態が悪い'; break; 
                                                    default: echo '不明'; 
                                                } 
                                            @endphp 
                                        </option> 
                                    @endforeach 
                            </select> 
                        </div> 
                        <div class="form__error"> 
                            @error('condition') 
                                {{ $message }} 
                            @enderror 
                        </div> 
                    </div> 
                </div>
                <!-- 商品名と説明 -->
                <div class="product-description">
                    <h2>商品名と説明</h2>
                    <hr class="divider">
                    <div class="form__group">
                        <div class="form__group-title">
                            <label for="name">商品名</label>
                        </div>
                        <div class="form__group-content">
                            <input type="text" id="name" name="name" value="{{ old('name') }}">
                            <div class="form__error">
                                @error('name')
                                {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form__group">
                        <div class="form__group-title">
                            <label for="brand">ブランド名</label>
                        </div>
                        <div class="form__group-content">
                            <input type="text" id="brand" name="brand" value="{{ old('brand') }}">
                            <div class="form__error">
                                @error('brand')
                                {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form__group">
                        <div class="form__group-title">
                            <label for="description">商品の説明</label>
                        </div>
                        <div class="form__group-content">
                            <textarea id="description" name="description" >{{ old('description') }}</textarea>
                            <div class="form__error">
                                @error('description')
                                {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form__group">
                        <div class="form__group-title">
                            <label for="price">販売価格</label>
                        </div>
                        <div class="form__group-content">
                            <input type="text" id="price" name="price" value="{{ old('price') }}">
                            <div class="form__error">
                                @error('price')
                                {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 送信 -->
                <div class="form__button">
                    <button class="form__button-sell" type="submit">出品する</button>
                </div>
            </form>
        </div>
    </main>
    <script>
        document.getElementById('imageInput').addEventListener('change', function (e) {
            const file = e.target.files[0];//選ばれたファイルを取り出す
            const preview = document.getElementById('preview');
            const button = document.getElementById('select-button');

            if(file) {
                const reader = new FileReader();

                //ファイルの読み込みが完了したときの処理
                reader.onload = function (e) {
                    preview.src = e.target.result; //画像のURLを設定
                    preview.style.display = 'block';//画像を表示
                    button.style.display = 'none';//画像を選択するボタンを隠す
                }

                reader.readAsDataURL(file);//ファイルを読み込む
            }
        })
    </script>
</body>
</html>
