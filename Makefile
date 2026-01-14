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

tests:
	./vendor/bin/phpunit

#tests:
# if filter
#	./vendor/bin/phpunit --filter $1

coverage:
	XDEBUG_MODE=coverage ./vendor/bin/phpunit --coverage-html tests/coverage
