FROM php:7.4-apache-buster as git-history-base

RUN apt-get -y update && apt-get -y upgrade && apt-get -y install git bash

RUN groupadd --gid 1000 git-history \
  && useradd --uid 1000 --gid git-history --shell /bin/bash --create-home git-history

COPY --from=composer:2.0 /usr/bin/composer /usr/bin/composer

WORKDIR /home/git-history/project
