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
$ php composer install [--no-dev]
```

※ リポジトリにcomposer.lockがあるのでupdateコマンドではなくinstallコマンドを使います。

### ４．環境変数

パフォーマンスを考慮するならばサーバ等で設定します。

ローカル環境などパフォーマンスを気にしないのでであればルートディレクトリに *.env* ファイルを作成し、 *sample.env* ファイルを参考に設定します。

#### アプリケーション設定

Azure Web Appsのアプリケーション設定で設定する場合は **APPSETTING_** を省略します。

|名前|値|説明|
|:--|:--|:--|
|APPSETTING_ENV|'prod' or 'dev'|アプリケーションの実行環境|
|APPSETTING_COA_SCHEDULE|'prod' or 'prod_and_test'|コアシステムズ様スケジュールの接続環境|
|APPSETTING_MV_AD|'true' or 'false'|ムービーウォーカー様広告のサポート|
|APPSETTING_MP_TICKET_URL|[ site URL ]|MPオンラインチケットのURL|
|APPSETTING_MP_TICKET_ENTRANCE_URL|[ entrance site URL ]|MPオンラインチケットのエントランスURL|

#### 接続文字列

Azure Web Appsのアプリケーション設定で設定する場合は **MYSQLCONNSTR_** 等を省略します。

|名前|値|説明|
|:--|:--|:--|
|MYSQLCONNSTR_HOST|[host name]|MySQLのホスト名|
|MYSQLCONNSTR_PORT|[port]|MySQLのポート番号|
|MYSQLCONNSTR_NAME|[database name]|MySQLのデータベース名|
|MYSQLCONNSTR_USER|[user name]|MySQLのユーザ名|
|MYSQLCONNSTR_PASSWORD|[user password]|MySQLのユーザパスワード|
|MYSQLCONNSTR_SSL|'true' or 'false'|MySQLにSSL接続するか|
|CUSTOMCONNSTR_STORAGE_NAME|[storage name]|Azure Storage名|
|CUSTOMCONNSTR_STORAGE_KEY|[storage access key]|Azure Sotrageのアクセスキー|
|CUSTOMCONNSTR_REDIS_HOST|[redis host]|Redisのホスト名|

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

内容はサンプルを参考にしてください。

#### PHP設定

sample.user.ini を参考に必要な設定を行います。

※ 直接 php.ini を編集するなど方法は環境によって適宜選択してくだい。
