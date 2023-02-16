# To run application:
`docker compose up --build -d`
# run tests by entering to php container:
### functional tests: `vendor/bin/phpunit tests/Functional`
### unit tests: `vendor/bin/phpunit tests/Unit`

- The command for crawling news will be executed automatically via crontab inside docker container, or you can run it manually via symfony command.
- rabbitMQ consumer will be executed automatically after composer install command