<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>フリマ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/goodsdetail.css') }}">
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
        <div class="goods">
            <div class="goods__left">
                <div class="goods__image">
                    <img src="{{ $item->image_url }}">
                </div>
            </div>
            <div class="goods__right">
                <div class="goods__main">
                    <h2 class="goods__name">{{ $item->name }}</h2>
                    <p class="goods__brand">{{ $item->brand }}</p>
                    <p class="goods__price">{{ $item->formatted_price }}</p>
                    <div class="goods__review">
                        <div class="goods__review--heart">
                            <form action="/favorite/{{ $item->id }}" method="post">
                                @csrf
                                <button type="submit" class="heart-button">
                                    @if($item->isFavoriteBy(Auth::user()))
                                        <img src="{{ asset('img/ハートロゴ_ピンク.png') }}" alt="いいね">
                                    @else
                                        <img src="{{ asset('img/ハートロゴ_デフォルト.png') }}" alt="いいね">
                                    @endif
                                </button>
                            </form>
                            <span>{{ $item->favoriteBy->count() }}</span>
                        </div>
                        <div class="goods__review--comment">
                            <img src="{{ asset('img/comment.png') }}" alt="コメント">
                            <span>{{ $item->comments->count() }}</span>
                        </div>
                    </div>
                </div>
                <a class="goods__buy-button" href="/purchase/{{ $item->id }}">
                    購入手続きへ
                </a>
                <section class="goods__description">
                    <h3>商品説明</h3>
                    <p>{!! nl2br(e($item->description)) !!}</p>
                </section>
                <section class="goods__info">
                    <h3>商品の情報</h3>
                    <p>カテゴリー
                        @foreach($item->categories as $category)
                            <span class="tag">{{ $category->name }}</span>
                        @endforeach
                    </p>
                    <p>商品の状態 <span class="condition">{{ $item->condition_label }}</span></p>
                </section>
                <section class="goods__comments">
                    <h3>コメント({{ $item->comments->count() }})</h3>
                    @forelse($item->comments as $comment)
                    <div class="comment">
                        <div class="comment__profile">
                            @if($comment->user->profile_image_url)
                            <img src="{{ $comment->user->profile_image_url }}" alt="">
                            @endif
                        </div>
                        <p class="comment__user">{{ $comment->user->name }}</p>
                    </div>
                    <div class="comment__text">
                        {{ $comment->content }}
                    </div>
                    @empty
                    <p class="comment__text">まだコメントはありません</p>
                    @endforelse
                    <form action="/comment/{{ $item->id }}" method="post">
                    @csrf
                        <label class="comment__label">商品へのコメント</label>
                        <textarea class="comment__input" name="content">{{ old('content') }}</textarea>
                        <div class="form__error">
                            @error('content')
                            {{ $message }}
                            @enderror
                        </div>
                        <div class="form__button">
                            <button class="form__button-submit" type="submit">コメントを送信する</button>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </main>
</body>
</html>