# Install

アプリケーションのインストールについてです。

データベース等の各サービスを用意したうえでの手順を記載しています。

各サービスの構築についてはwikiを参照してください。

[wiki](https://m-p.backlog.jp/alias/wiki/508245)

※ 管理画面アプリケーション（cinemasunshine/portal2018-admin）を前提とした部分もあります。そちらも合わせて確認してください。

## 手順

### １．データベース

ユーザ作成を行います。

手順はwikiを参照してください。

https://m-p.backlog.jp/alias/wiki/568643

※ データベース作成、テーブル作成、マスターデータ登録は管理画面アプリケーションの手順を参照してください。

### ２．ストレージ

Azure Storageについてです。次のBlob Containerを作成します。

- frontend-log

※ 管理画面アプリケーションの手順も合わせて参照してください。

### ３．composer

composerコマンドで依存ライブラリをインストールします。

※ Backlog(m-p.backlog.jp)の認証が必要な場合があります。

※ composerがインストールされている必要があります。

[Download Composer](https://getcomposer.org/download/)

```sh
$ php composer install [--no-dev] [-o|--optimize-autoloader]
```

※ リポジトリにcomposer.lockがあるのでupdateコマンドではなくinstallコマンドを使います。

※ 運用環境ではno-dev、optimize-autoloaderオプションを推奨。

### ４．環境変数

ルートディレクトリに *.env* ファイルを作成し、 *.env.example* ファイルを参考に設定します。

※ パフォーマンスを考慮するならば.envファイルは作成せず、サーバ等で設定します。

#### アプリケーション設定

Azure Web Appsのアプリケーション設定で設定する場合はプレフィックス（ **APPSETTING_** ）を省略します。

|名前|値|必須|説明|
|:--|:--|:--|:--|
|APPSETTING_ENV|*String*|○|アプリケーションの実行環境|
|APPSETTING_DEBUG|*Boolean*|-|デバッグ設定（デフォルト： false）|
|APPSETTING_DOCTRINE_CACHE|*String*|-|Database Cache Driver（デフォルト： array）|
|APPSETTING_SCHEDULE_ENV|*String*|○|スケジュールの接続環境（cinemasunshine/schedule）|
|APPSETTING_SCHEDULE_API_URL|*String*|○|MPスケジュールAPIのURL|
|APPSETTING_MP_API_HOST|*String*|○|APIホスト名|
|APPSETTING_MP_AUTHORIZATION_CODE_HOST|*String*|○|Authorization Code Grantのホスト名|
|APPSETTING_MP_AUTHORIZATION_CODE_CLIENT_ID|*String*|○|Authorization Code GrantのクライアントID|
|APPSETTING_MP_AUTHORIZATION_CODE_CLIENT_SECRET|*String*|○|Authorization Code Grantのクライアント シークレット|
|APPSETTING_MP_CLIENT_CREDENTIALS_HOST|*String*|○|Client Credentials Grantのホスト名|
|APPSETTING_MP_CLIENT_CREDENTIALS_CLIENT_ID|*String*|○|Client Credentials GrantのクライアントID|
|APPSETTING_MP_CLIENT_CREDENTIALS_CLIENT_SECRET|*String*|○|Client Credentials Grantのクライアント シークレット|
|APPSETTING_MP_TICKET_URL|*String*|○|MPオンラインチケットのURL|
|APPSETTING_MP_TICKET_ENTRANCE_URL|*String*|○|MPオンラインチケットのエントランスURL|

#### 接続文字列

Azure Web Appsのアプリケーション設定で設定する場合はプレフィックス（ **MYSQLCONNSTR_** 等）を省略します。

|名前|値|必須|説明|
|:--|:--|:--|:--|
|MYSQLCONNSTR_HOST|*String*|○|MySQLのホスト名|
|MYSQLCONNSTR_PORT|*Integer*|○|MySQLのポート番号|
|MYSQLCONNSTR_NAME|*String*|○|MySQLのデータベース名|
|MYSQLCONNSTR_USER|*String*|○|MySQLのユーザ名|
|MYSQLCONNSTR_PASSWORD|*String*|○|MySQLのユーザパスワード|
|MYSQLCONNSTR_SSL|*Boolean*|○|MySQLにSSL接続するか|
|MYSQLCONNSTR_SSL_CA|*String*|-|CA証明書のファイルパス|
|CUSTOMCONNSTR_STORAGE_SECURE|*Boolean*|-|HTTPS接続するか。デフォルト: true|
|CUSTOMCONNSTR_STORAGE_NAME|*String*|○|Azure Storage名|
|CUSTOMCONNSTR_STORAGE_KEY|*String*|○|Azure Sotrageのアクセスキー|
|CUSTOMCONNSTR_STORAGE_BLOB_ENDPOINT|*String*|-|Blob エンドポイント|
|CUSTOMCONNSTR_STORAGE_PUBLIC_ENDOPOINT|*String*|-|パブリック アクセス エンドポイント|
|CUSTOMCONNSTR_REDIS_HOST|*String*|○|Redisのホスト名|
|CUSTOMCONNSTR_REDIS_PORT|*String*|○|Redisのポート番号|
|CUSTOMCONNSTR_REDIS_AUTH|*String*|-|Redisの認証文字列|

### ５．Doctrine

#### Schema生成

このアプリケーションからのSchema更新は想定されていません。

管理画面アプリケーションを参照してください。

#### Proxy生成

開発環境**以外**は手動で生成が必要です。

```sh
$ vendor/bin/doctrine orm:generate-proxies
```

### ６．マスターデータ

管理画面アプリケーションを参照してください。

### ７．その他

#### Webサーバ設定（Windowsサーバの場合）

ドキュメントルートに *Web.config* を設置します。

内容はexampleを参考にしてください。

#### PHP設定

user.ini.example を参考に必要な設定を行います。

※ 直接 php.ini を編集するなど方法は環境によって適宜選択してくだい。
