# nginx用Dockerfile

# https://hub.docker.com/_/nginx/
FROM nginx:latest

# apt-getをupdate（installできなかったので）
# install
RUN apt-get update \
    && apt-get install -y \
        vim \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*
