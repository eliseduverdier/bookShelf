PHONY: tests




###############
# Build     #
###############
install:
	composer install

start:
	symfony serve

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
