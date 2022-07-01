install:
	composer install --prefer-dist --no-progress
lint:
	composer exec --verbose phpcs -- --standard=PSR12 src bin