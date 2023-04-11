FROM composer:2

LABEL repository="https://github.com/claudiodekker/changelog-updater"
LABEL homepage="https://github.com/claudiodekker/changelog-updater"
LABEL maintainer="Claudio Dekker <claudio@ubient.net>"

RUN mkdir -p /app
WORKDIR /app
COPY . .

RUN composer install --no-dev --no-interaction --no-progress --no-scripts --optimize-autoloader

ENTRYPOINT ["php", "/app/action.php"]
