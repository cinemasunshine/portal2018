# cinemasunshine/portal2018-frontend

シネマサンシャイン ポータルサイト2018 フロントエンド

## 概要

ポータルサイト2018のフロントエンドです。

## システム要件

- PHP: 7.2
- MySQL: 5.7

## Docker

ローカル環境としてDockerが利用できます。

※ [Docker](https://www.docker.com/)をインストールしてください。

※ 現状では開発環境としての利用のみを想定すてます。

※ AzureはWindowsサーバですが、こちらはLinuxサーバです。

※ StorageはAzureプラットフォームで別途作成してください。

web: http://localhost:8010/

### コマンド例

コンテナを作成し、起動する。

```sh
$ docker-compose up
```

# その他
## PHP CodeSniffer
### コードチェック

```sh
$ vendor/bin/phpcs
```
