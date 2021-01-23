help: ## show this help
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'
up:
	docker-compose up -d
install:
	docker-compose exec application composer install --ignore-platform-req=php
build:
	docker-compose build
bash: ## run bash inside application container
	docker-compose exec application bash
start: ## start and install dependencies
start: build up install
psalm:
	docker-compose exec application vendor/bin/psalm
check: ## run QA checks
check: psalm
phpspec:
	docker-compose exec application vendor/bin/phpspec run
phpunit_unit:
	docker-compose exec application bin/phpunit --group unit
phpunit_integration:
	docker-compose exec application bin/phpunit --group integration
phpunit_functional:
	docker-compose exec application bin/phpunit --group functional
unit_test: ## run unit tests
unit_test: phpspec phpunit_unit
test: ## run all tests
test: unit_test phpunit_integration phpunit_functional
clear: ## clear after docker
	docker-compose down
