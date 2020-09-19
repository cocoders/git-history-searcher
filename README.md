# Git history searcher

Simple project which should allow searching in project git history.
Project will provide console ui and REST API for the searching of given
projects in "git".

## Development env

Project is using docker and docker-compose so `docker-compose up -d` should work. 
For installation of docker and docker-compose please follow: https://docs.docker.com/engine/install/ubuntu/
and https://docs.docker.com/compose/install/ official docker documentation.

Please check out our [Makefile](Makefile) as well.

To start project please run `make run` if you do not have "make" you can run `docker-compose up -d`
and after checking our make file install all needed dependencies (via composer package manager for more 
info please check [composer website](https://getcomposer.org/)).

## Testing

We are using phpspec and phpunit to run tests (all needed libs can be installed using composer/symfony flex).
Run `make test` to run all test, if you want only some parts of test please use `make bash` and run test from
bash console manually. Please check out our [Makefile](Makefile).
