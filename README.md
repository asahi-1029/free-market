# アプリケーション名
フリーマーケット

## 環境構築

## Dockerビルド
- git clone git@github.com:asahi-1029/free-market.git
- docker-compose up -d --build

## Laravel環境構築
- docker-compose exec php bash
- composer install
- cp .env.example .env、環境変数を変更
- php artisan key:generate
- php artisan migrate
- php artisan db:seed

## 開発環境
- 商品一覧画面（トップ画面）: http://localhost/
- 商品一覧画面（トップ画面）_マイリスト : http://localhost/?tab=mylist
- 会員登録画面 : http://localhost/register
- ログイン画面 : http://localhost/login
- ログアウト : http://localhost/logout
- メール認証誘導画面 : http://localhost/email/verify
- プロフィール設定画面（初回ログイン時）: http://localhost/setup
- 商品詳細画面 : http://localhost/item/{item_id}
- 商品購入画面 : http://localhost/purchase/{item_id}
- 住所変更ページ : http://localhost/purchase/address/{item_id}
- 商品出品画面 : http://localhost/sell
- プロフィール画面 : http://localhost/mypage
- プロフィール編集画面（設定画面）: http://localhost/mypage/profile
- プロフィール画面_購入した商品一覧 : http://localhost/mypage?page=buy
- プロフィール画面_出品した商品一覧 : http://localhost/mypage?page=sell
- phpMyadmin : http://localhost:8080/
- mailhog : http://localhost:8025/

## 使用技術（実行環境）
- PHP 8.1.x
- Laravel 8.83.8
- HTML/CSS
- JavaScript（Vanilla JS）
- MySQL 8.0.26
- nginx 1.21.1
- phpMyAdmin
- MailHog

## ER図
![ER図](index.drawio.png)
