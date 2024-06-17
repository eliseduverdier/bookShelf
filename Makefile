PHONY: tests


init: # to do manually
	composer install
	mysq-l -e 'create database book_with_users'

###############
# Build     #
###############
install:
	composer install

# first you need symfont cli installed. Maybe in your ~/.config
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
