# アプリケーション名
フリーマーケット

## 環境構築

## Dockerビルド
- git clone git@github.com:asahi-1029/free-market.git
- docker-compose up -d --build

## Laravel環境構築
- docker-compose exec php bash
- composer install
- cp .env.example .env
- php artisan key:generate
- php artisan migrate
- php artisan db:seed

## 開発環境
