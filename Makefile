# The purpose of this Makefile is helping this project's contributors to setup
# a working environment. To do so, simply execute:
#
#     make
#
# This will, hopefully, download all the developer dependencies and get you
# started immediately. If this is not the case, please report an issue.
#
# @todo Check if XDebug is installed

.PHONY: update clean

all: composer.lock
	# Dependencies installed/upgraded successfully

composer.lock: composer.phar
	php composer.phar install

composer.phar:
	curl -sS https://getcomposer.org/installer | php

update: composer.phar
	php composer.phar update

test: composer.lock
	vendor/bin/phpunit --colors --coverage-html ../../build/logs/coverage --coverage-text=../../build/logs/coverage.txt tests/unit && \
	echo && \
	echo ======== Code coverage ======== && \
	cat build/logs/coverage.txt | grep -A3 Summary | tail -n 3 && \
	echo ===============================

clean:
	rm -Rf vendor composer.phar composer.lock
