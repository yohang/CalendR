test:
	./vendor/bin/phpunit tests --colors --coverage-text --whitelist=src --coverage-clover=build/coverage/clover.xml

docker_build:
	docker build --build-arg PHP_VERSION=7.4 -t calendr:7.4 .
	docker build --build-arg PHP_VERSION=7.4 --build-arg COMPOSER_FLAGS="--prefer-lowest" -t calendr:7.4-lowdeps .
	docker build --build-arg PHP_VERSION=8.0 -t calendr:8.0 .
	docker build --build-arg PHP_VERSION=8.0 --build-arg COMPOSER_FLAGS="--prefer-lowest" -t calendr:8.0-lowdeps .

docker_test: docker_build
	docker run -it --rm calendr:7.4 ./vendor/bin/phpunit tests --colors
	docker run -it --rm calendr:7.4-lowdeps ./vendor/bin/phpunit tests --colors
	docker run -it --rm calendr:8.0 ./vendor/bin/phpunit tests --colors
	docker run -it --rm calendr:8.0-lowdeps ./vendor/bin/phpunit tests --colors
