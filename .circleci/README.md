# CircleCI

[ドキュメント](https://circleci.com/docs/ja/)

## Contexts

cinemasunshine (Organizations) > Organization Settings > Contexts

### Docker Hub

| Name | Value |
|:---|:---|
|DOCKERHUB_ID |Docker Hub ユーザ |
|DOCKERHUB_ACCESS_TOKEN |Docker Hub アクセストークン |

## Environment Variables

cinemasunshine (Organizations) > portal2018 (Projects) > Project Settings > Environment Variables

### Composer

| Name | Value |
|:---|:---|
|BACKLOG_USER |backlogのユーザ（プロジェクトSASAKIの権限が必要） |
|BACKLOG_PASSWORD |backlogユーザのパスワード |

### デプロイ

| Name | Value |
|:---|:---|
|AZURE_TENANT |Azure テナントID [circleci/azure-cli orb](https://circleci.com/developer/orbs/orb/circleci/azure-cli) |
|AZURE_USERNAME |Azure ユーザ名 [circleci/azure-cli orb](https://circleci.com/developer/orbs/orb/circleci/azure-cli) |
|AZURE_PASSWORD |Azure パスワード [circleci/azure-cli orb](https://circleci.com/developer/orbs/orb/circleci/azure-cli) |
