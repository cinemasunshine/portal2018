# cinemasunshine/portal2018

シネマサンシャイン ポータルサイト2018

## 概要

2018年リリースのポータルサイトです。

## システム要件

- PHP: 7.2
- MySQL: 5.7
- Azure App Service (Windows)

## Docker

ローカル環境としてDockerが利用できます。

※ [Docker](https://www.docker.com/)をインストールしてください。

※ 現状では開発環境としての利用のみを想定してます。

※ AzureはWindowsサーバですが、こちらはLinuxサーバです。

※ データベース、ストレージについてはCMSアプリケーションのドキュメントを参照してください。

web: https://localhost:8010/

### コマンド例

コンテナを作成し、起動する。

```sh
$ docker-compose up
```

# その他
## PHP CodeSniffer

```sh
$ composer phpcs
```

## PHPStan

```sh
$ composer phpstan
```
