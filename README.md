# cinemasunshine/portal2018

シネマサンシャイン ポータルサイト2018

## 概要

2018年リリースのポータルサイトです。

## システム要件

- PHP: 7.2
- MySQL: 5.7
- Redis
- Azure App Service (Windows)
- Azure Blob Storage

## EditorConfig

[EditorConfig](https://editorconfig.org/) でコーディングスタイルを定義しています。

利用しているエディタやIDEにプラグインをインストールしてください。

[Download a Plugin](https://editorconfig.org/#download)

## CircleCI

CIツールとして [CircleCI](https://circleci.com) を導入してます。

※ 現在はコード解析だけですが、将来的にデプロイなども実施する予定です。

## Docker

ローカル環境としてDockerが利用できます。

※ [Docker](https://www.docker.com/)をインストールしてください。

※ 現状では開発環境としての利用のみを想定してます。

※ AzureはWindowsサーバですが、こちらはLinuxサーバです。

※ データベース、ストレージについてはCMSアプリケーションのドキュメントを参照してください。

web: https://localhost:8010/

phpRedisAdmin: http://localhost:8081/

### docker-compose コマンド例

コンテナを作成し、起動する。

```sh
$ docker-compose up
```

## アプリケーション コマンド

```sh
$ php bin/concole help
```

### viewキャッシュ削除

```sh
$ php bin/concole cache:clear:view
```

## その他 コマンド
### PHP CodeSniffer

```sh
$ composer phpcs
```

### PHPStan

```sh
$ composer phpstan
```
