test:
	./vendor/bin/phpunit tests --colors --coverage-text --whitelist=src --coverage-clover=build/coverage/clover.xml

test-all:
	dagger call test-all
