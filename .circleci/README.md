# CircleCI

[ドキュメント](https://circleci.com/docs/ja/)

## Environment Variables

Settings > cinemasunshine > portal2018 > Environment Variables

### Composer

| Name | Value |
|:---|:---|
|BACKLOG_USER |backlogのユーザ（プロジェクトSASAKIの権限が必要） |
|BACKLOG_PASSWORD |backlogユーザのパスワード |

### デプロイ

| Name | Value |
|:---|:---|
|DEV_AAS_USER |開発環境デプロイユーザ |
|DEV_AAS_PASSWORD |開発環境デプロイユーザのパスワード |
|TEST_AAS_USER |テスト環境デプロイユーザ |
|TEST_AAS_PASSWORD |テスト環境デプロイユーザのパスワード |
|PROD_RELEASE_AAS_USER |運用環境releaseスロット デプロイユーザ |
|PROD_RELEASE_AAS_PASSWORD |運用環境releaseスロット デプロイユーザのパスワード |

デプロイユーザとパスワードはAzure App Serviceのプロパティ > デプロイの開始URL
