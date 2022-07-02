install:
	composer install
lint:
	composer exec --verbose phpcs -- --standard=PSR12 src bin
test:
	composer run-script phpunit
test-coverage:
	composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml