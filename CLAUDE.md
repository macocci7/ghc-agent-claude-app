# 🏗️ CLAUDE.md - Login system

このファイルはAIや開発者向けの詳細設計・運用指示書です。README.mdは利用者向けです

## 概要

これは、外部ライブラリーやフレームワークを使用しない、PHPで書かれた簡単なログインシステムです。
ユーザーはメールアドレスとパスワードでサインアップできます。
ユーザーはサインアップで登録されたメールアドレスとパスワードでログインできます。

## AIアシスタントへの注意事項

When working on this codebase:

1. **Always run `php -l [PHPファイル名]` and fix warnings** before suggesting code
2. **Test your changes** - don't assume code works
3. **Preserve existing behavior** unless explicitly asked to change it
4. **Follow PSR-1, PSR-2 and PSR-12*** - basically follow other PSRs, too. But ignore the PSRs abandoned.
5. **Maintain predictable defaults** - user should never be surprised
6. **Document any new features** in both code and README
7. **Consider edge cases** - empty states, missing files, permissions

Remember: This tool is about speed and simplicity.
Every feature should make context switching faster or easier, not more complex.
**Predictability beats cleverness.**

## 設計

### モデル定義

- `設計_モデル.md`の内容に従う。（最終行まで読み取ること）

### アプリケーションの仕様

- `設計_仕様.md`の内容に従う。（最終行まで読み取ること）

### システム

#### プロジェクトフォルダ

- `システム_フォルダ構成.md`の内容に従う。（最終行まで読み取ること）

#### 開発コマンド

- `システム_開発コマンド.md`に従う（最終行まで読み取ること）

#### システム構成

- `システム_構成.md`に従う（最終行まで読み取ること）

#### テスト

- `システム_テスト.md`に従う（最終行まで読み取ること）

#### ワークフロー

##### 初回

1. Dockerコンテナを構築して開始: `docker compose up -d`
2. `bin/`フォルダ内のコマンド作成
3. データベースマイグレーションファイル作成
4. データベースマイグレーション実行
5. WEBサーバーコンテナにPHPUnit 12をcomposerでインストール
6. `html/`フォルダ内のソースコード作成
7. WEBサーバーコンテナ上でPHPUnitのUnitテスト実行
8. Unitテストが全てpassするまでソースコードを修正してUnitテスト実行
9. Unitテストが全てpassしたらgitでコミットをする
