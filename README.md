# Login System

PHPで作成されたシンプルなログインシステムです。

## 機能

- ユーザーサインアップ
- ユーザーログイン/ログアウト
- ダッシュボード
- ログイン履歴記録

## システム要件

- Docker & Docker Compose
- PHP 8.4
- MariaDB 11.8.2
- nginx

## セットアップ

1. リポジトリをクローン
```bash
git clone <repository-url>
cd ghc-agent-claude-app
```

2. Dockerコンテナを起動
```bash
bin/start
```

3. Composerの依存関係をインストール
```bash
bin/web-user
composer install
```

4. データベースマイグレーションを実行
```bash
php migrate.php
```

5. ブラウザでアクセス
```
http://localhost:8080
```

## コマンド

- `bin/start` - コンテナ起動
- `bin/stop` - コンテナ停止
- `bin/web-user` - Webコンテナにユーザー権限でアクセス
- `bin/web-root` - Webコンテナにroot権限でアクセス
- `bin/mariadb-connect` - データベースに接続

## テスト実行

```bash
bin/web-user
composer test-unit
composer test-http
```

## 開発

詳細な開発指示については `CLAUDE.md` を参照してください。
