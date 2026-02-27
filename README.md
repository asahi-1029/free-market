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
- 商品一覧画面 （トップ画面）: http://localhost/
- 商品一覧画面（トップ画面）_マイリスト : http://localhost/?tab=mylist
- 会員登録画面 : http://localhost/register
- ログイン画面 : http://localhost/login
- ログアウト : http://localhost/logout
- 商品詳細画面 : http://localhost/item/{item_id}
- 商品購入画面 : http://localhost/purchase/{item_id}
- 住所変更ページ : http://localhost/purchase/address/{item_id}
- 商品出品画面 : http://localhost/sell
- phpMyadmin : http:/localhost:8080/

## 使用技術（実行環境）
- PHP 8.2.11
- Laravel 8.83.8
- jquery 3.7.1.min.js
- MySQL 8.0.26
- nginx 1.21.1
