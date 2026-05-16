# 早起きできたねっ！アプリケーション

早起きを習慣化するためのソーシャル目標達成アプリケーション。目標起床時間を設定し、実際の起床時間を記録・共有することで、モチベーションを維持します。

## 技術スタック

### バックエンド
- **PHP**: 8.2+
- **Laravel**: 12.x
- **Laravel Breeze**: 認証システム
- **Inertia.js**: モダンなモノリスアーキテクチャ
- **Pest**: テストフレームワーク

### フロントエンド
- **React**: 18.x
- **TypeScript**: 5.x
- **Tailwind CSS**: 4.x
- **Vite**: 7.x
- **Recharts**: データ可視化
- **Lucide React**: アイコンライブラリ

### データベース
- MySQL / PostgreSQL / SQLite

## 主な機能

### 1. 起床記録
- ワンクリックで現在時刻を記録
- 目標時間との比較による達成判定
- 達成回数の自動カウント
- 直近1ヶ月の記録一覧表示
- 起床時間の分布チャート（円グラフ）

### 2. ユーザー管理
- プロフィール編集（名前、年齢、目標起床時間、自己紹介）
- メール認証
- パスワード変更
- アカウント削除

### 3. ソーシャル機能
- ユーザー一覧表示
- フォロー/アンフォロー機能
- フィルタリング
  - 同じ目標時間のユーザー
  - フォロー中のユーザー
- 達成回数順のランキング表示

## アーキテクチャ

このアプリケーションは**レイヤードアーキテクチャ**を採用しています。

```
Controller層 (HTTPリクエスト/レスポンス処理)
    ↓
Service層 (ビジネスロジック)
    ↓
Repository層 (データアクセス)
    ↓
Model層 (Eloquent ORM)
```

### ディレクトリ構造

```
app/
├── Http/
│   ├── Controllers/          # HTTPリクエストハンドリング
│   │   ├── WakeUpRecordController.php
│   │   ├── DashboardController.php
│   │   ├── UserController.php
│   │   └── FollowController.php
│   └── Requests/            # フォームリクエストバリデーション
│       ├── StoreWakeUpRecordRequest.php
│       └── ProfileUpdateRequest.php
├── Services/                # ビジネスロジック層
│   ├── WakeUpRecordService.php
│   ├── UserService.php
│   └── FollowService.php
├── Repositories/            # データアクセス層
│   ├── WakeUpRecordRepository.php
│   ├── UserRepository.php
│   └── FollowRepository.php
└── Models/                  # Eloquentモデル
    ├── User.php
    ├── WakeUpRecord.php
    └── Follow.php

resources/js/
├── Pages/                   # Inertia.jsページコンポーネント
│   ├── Dashboard.tsx        # ダッシュボード
│   ├── Users/Index.tsx      # ユーザー一覧
│   └── Profile/             # プロフィール編集
├── Components/              # 再利用可能なReactコンポーネント
├── Layouts/                 # レイアウトコンポーネント
└── types/                   # TypeScript型定義
    └── index.d.ts
```

### 設計原則

- **Constructor Property Promotion**: PHP 8.0+の機能を活用した依存性注入
- **型安全性**: TypeScriptによる静的型チェック（any型完全排除）
- **責務の分離**: 各層が明確な役割を持つ
- **テスタビリティ**: ユニットテスト可能な設計

## セットアップ

### 前提条件

- Docker & Docker Compose
- または以下の環境
  - PHP 8.2以上
  - Composer
  - Node.js 18以上
  - MySQL/PostgreSQL/SQLite

### Docker（推奨）

```bash
# 1. リポジトリのクローン
git clone <repository-url>
cd asakatsu

# 2. 環境変数の設定
cp .env.example .env

# 3. Dockerコンテナの起動
docker compose up -d

# 4. 依存関係のインストール
docker compose exec laravel.test composer install
npm install

# 5. アプリケーションキーの生成
docker compose exec laravel.test php artisan key:generate

# 6. データベースマイグレーション
docker compose exec laravel.test php artisan migrate

# 7. シードデータの投入（任意）
docker compose exec laravel.test php artisan db:seed

# 8. フロントエンドのビルド
npm run build
```

### ローカル環境

```bash
# 1. リポジトリのクローン
git clone <repository-url>
cd asakatsu

# 2. 環境変数の設定
cp .env.example .env

# 3. 依存関係のインストール
composer install
npm install

# 4. アプリケーションキーの生成
php artisan key:generate

# 5. データベースマイグレーション
php artisan migrate

# 6. シードデータの投入（任意）
php artisan db:seed

# 7. フロントエンドのビルド
npm run build
```

### 初期ユーザー

シードデータを投入した場合、以下のテストユーザーでログインできます：

- **Email**: test@example.com
- **Password**: password

## 開発コマンド

### バックエンド

```bash
# 開発サーバー起動（すべてのサービスを同時に起動）
composer dev
# 内訳: Webサーバー、キューワーカー、ログビューア、Vite

# マイグレーション実行
php artisan migrate

# マイグレーションのロールバック
php artisan migrate:rollback

# シードデータ投入
php artisan db:seed

# キャッシュクリア
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# コード整形（Laravel Pint）
./vendor/bin/pint
```

### フロントエンド

```bash
# 開発サーバー起動（ホットリロード）
npm run dev

# 本番ビルド
npm run build

# TypeScriptコンパイルチェック
npx tsc --noEmit

# ESLint実行（自動修正）
npm run lint
```

### テスト

```bash
# 全テスト実行
php artisan test

# 特定のテストファイル実行
php artisan test tests/Unit/Services/WakeUpRecordServiceTest.php

# カバレッジ付きテスト実行
php artisan test --coverage
```

### Docker環境の場合

コマンドの前に `docker compose exec laravel.test` を付けます：

```bash
docker compose exec laravel.test php artisan migrate
docker compose exec laravel.test php artisan test
```

## データベーススキーマ

### usersテーブル

| カラム名 | 型 | 説明 |
|---------|---|------|
| id | bigint | プライマリキー |
| name | varchar | ユーザー名 |
| email | varchar | メールアドレス（ユニーク） |
| password | varchar | ハッシュ化パスワード |
| age | integer | 年齢（nullable） |
| target_wake_up_time | time | 目標起床時間（nullable） |
| introduction | text | 自己紹介（nullable） |
| profile_image_path | varchar | プロフィール画像パス（nullable） |
| wake_up_achievements | integer | 達成回数（デフォルト: 0） |
| email_verified_at | timestamp | メール確認日時 |

### wake_up_recordsテーブル

| カラム名 | 型 | 説明 |
|---------|---|------|
| id | bigint | プライマリキー |
| user_id | bigint | ユーザーID（外部キー） |
| recorded_at | datetime | 記録日時 |
| is_achieved | boolean | 目標達成フラグ |
| created_at | timestamp | 作成日時 |
| updated_at | timestamp | 更新日時 |

### followsテーブル

| カラム名 | 型 | 説明 |
|---------|---|------|
| id | bigint | プライマリキー |
| follower_id | bigint | フォローする側のユーザーID（外部キー） |
| followed_id | bigint | フォローされる側のユーザーID（外部キー） |
| created_at | timestamp | 作成日時 |
| updated_at | timestamp | 更新日時 |

※ `[follower_id, followed_id]` の組み合わせにユニーク制約

## API仕様

Inertia.jsを使用しているため、REST APIではなくサーバーサイドレンダリングです。

### 主要ルート

| メソッド | パス | 説明 |
|---------|------|------|
| GET | `/dashboard` | ダッシュボード表示 |
| POST | `/wake-up` | 起床記録登録 |
| GET | `/users` | ユーザー一覧表示 |
| POST | `/users/{user}/follow` | フォロー |
| DELETE | `/users/{user}/follow` | アンフォロー |
| GET | `/profile` | プロフィール編集画面 |
| PATCH | `/profile` | プロフィール更新 |

## トラブルシューティング

### vendorディレクトリが壊れている場合

```bash
# Docker環境
docker compose exec laravel.test rm -rf vendor composer.lock
docker compose exec laravel.test composer install

# ローカル環境
rm -rf vendor composer.lock
composer install
```

### npm installでエラーが出る場合

```bash
rm -rf node_modules package-lock.json
npm install
```

### 画面が表示されない場合

```bash
# キャッシュクリア
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# アセットの再ビルド
npm run build
```

### 型エラーが出る場合（TypeScript）

```bash
# 型定義の確認
npx tsc --noEmit

# node_modulesの再インストール
rm -rf node_modules package-lock.json
npm install
```

## デプロイ

### 本番環境の設定

1. `.env`ファイルを本番環境用に設定
   ```
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://your-domain.com
   ```

2. 依存関係のインストール
   ```bash
   composer install --optimize-autoloader --no-dev
   npm install
   npm run build
   ```

3. キャッシュの最適化
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

4. マイグレーション実行
   ```bash
   php artisan migrate --force
   ```

## テスト

### ユニットテスト

```bash
# Service層のテスト
php artisan test tests/Unit/Services/

# Repository層のテスト
php artisan test tests/Unit/Repositories/
```

### 主要テストケース

- `WakeUpRecordServiceTest`: 起床記録登録のビジネスロジック
- `UserRepositoryTest`: ユーザーデータアクセス

## 貢献

プルリクエストを歓迎します。大きな変更を行う場合は、まずissueを開いて変更内容を議論してください。

## ライセンス

MIT License

## 作成者

早起き習慣化プロジェクトチーム
