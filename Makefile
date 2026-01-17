PHONY: tests  build  start  stop  restart  logs

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

###############
# Quality     #
###############
csfix:
	docker compose exec web bash -c './vendor/bin/php-cs-fixer fix'

tests:
	docker compose exec web bash -c './vendor/bin/phpunit'

coverage:
	docker compose exec web bash -c 'XDEBUG_MODE=coverage ./vendor/bin/phpunit --coverage-html tests/coverage'
