# the different stages of this Dockerfile are meant to be built into separate images
# https://docs.docker.com/develop/develop-images/multistage-build/#stop-at-a-specific-build-stage
# https://docs.docker.com/compose/compose-file/#target

ARG PHP_VERSION=7.4
ARG NGINX_VERSION=1.17

# "php" stage
FROM php:${PHP_VERSION}-fpm-alpine AS news_paring_service

# persistent / runtime deps
RUN apk add --no-cache --update \
		acl \
		curl \
        file \
        gettext \
        git \
        freetype \
        icu \
        libjpeg-turbo \
        libpng \
        libzip \
    ;

ARG APCU_VERSION=5.1.17
RUN set -eux; \
	apk add --no-cache --virtual .build-deps \
		$PHPIZE_DEPS \
		icu-dev \
		libzip-dev \
		mysql-dev \
		zlib-dev \
		freetype-dev \
		libjpeg-turbo-dev \
		libpng-dev \
		libxml2-dev \
		pcre-dev \
		gmp-dev \
		rabbitmq-c-dev \
	; \
	\
	docker-php-ext-configure zip; \
	docker-php-ext-configure gd --with-freetype --with-jpeg; \
	docker-php-ext-install -j$(nproc) \
		intl \
		pdo_mysql \
		zip \
		calendar \
		gd \
		xml \
		gmp \
		bcmath \
	; \
	pecl install \
		apcu-${APCU_VERSION} \
		mongodb \
		xdebug \
		amqp \
	; \
	pecl clear-cache; \
	docker-php-ext-enable \
		apcu \
		opcache \
		mongodb \
		amqp \
	; \
	\
	runDeps="$( \
		scanelf --needed --nobanner --format '%n#p' --recursive /usr/local/lib/php/extensions \
			| tr ',' '\n' \
			| sort -u \
			| awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
	)"; \
	apk add --no-cache --virtual .api-phpexts-rundeps $runDeps; \
	\
	apk del .build-deps

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY docker/php/php.ini /usr/local/etc/php/conf.d/news_service.ini
COPY docker/php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
RUN set -eux; \
	ln -s /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN composer global config --no-plugins allow-plugins.symfony/flex true

# install Symfony Flex globally to speed up download of Composer packages (parallelized prefetching)
RUN set -eux; \
	composer global require symfony/flex:1.19.4 --prefer-dist --no-progress --classmap-authoritative; \
	composer clear-cache
ENV PATH="${PATH}:/root/.composer/vendor/bin"


WORKDIR /srv

RUN composer create-project symfony/skeleton:"^5.4" .
RUN composer require

# prevent the reinstallation of vendors at every changes in the source code
#COPY composer.json composer.lock ./
RUN set -eux; \
	composer install --prefer-dist --no-dev --no-scripts --no-progress; \
	composer clear-cache

RUN apk update && apk add nodejs npm

ENV APP_ENV=dev
ENV APP_DEBUG=1
COPY .env ./
RUN set -eux; \
	composer dump-env ${APP_ENV}; \
	rm .env

# copy only specifically what we need
#COPY bin bin/
#COPY config config/
#COPY src src/
#COPY public public/

RUN set -eux; \
	mkdir -p var/cache var/log var/sessions; \
	setfacl -R -m u:www-data:rwX -m u:"$(whoami)":rwX var; \
	setfacl -dR -m u:www-data:rwX -m u:"$(whoami)":rwX var; \
	composer dump-autoload --no-dev --classmap-authoritative; \
	composer run-script --no-dev post-install-cmd; \
	chmod +x bin/console; \
	chown -R www-data:www-data . ; sync;
VOLUME /srv/var

COPY docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]

# "nginx" stage
# depends on the "php" stage above
FROM nginx:${NGINX_VERSION}-alpine AS news_paring_service_nginx

COPY docker/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf
COPY docker/nginx/conf.d/shell.conf /etc/nginx/conf.d/shell.conf

WORKDIR /srv/public

COPY --from=news_paring_service /srv/public ./
