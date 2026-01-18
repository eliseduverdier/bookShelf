.PHONY: tests  build  start  stop  restart  logs

###############
# Docker      #
###############
build:
	docker compose build

start:
	docker compose up -d
	@echo "-> http://localhost:8000"

stop:
	docker compose down

restart:
	docker compose restart

logs:
	docker compose logs -f

sh:
	docker compose exec web bash

migrate:
	docker compose exec web bash -c 'php bin/console doctrine:migrations:rollup'

###############
# Quality     #
###############
csfix:
	docker compose exec web bash -c './vendor/bin/php-cs-fixer fix'

tests:
ifdef filter
	docker compose exec web bash -c './vendor/bin/phpunit --filter $(filter)'
else
	docker compose exec web bash -c './vendor/bin/phpunit'
endif

coverage:
	docker compose exec web bash -c 'XDEBUG_MODE=coverage ./vendor/bin/phpunit --coverage-html tests/coverage'
