FROM dunglas/frankenphp:latest-bookworm as build

ENV SWYTCH_STATE_SECRET=chamge-me SWYTCH_DEFAULT_LANGUAGE=en SWYTCH_SUPPORTED_LANGUAGES=en,nl,de SWYTCH_LANGUAGE_DIR=/var/www/locales

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions @composer intl sodium zip uuid opcache apcu xdebug

COPY composer.json composer.lock /app/
WORKDIR /app/
RUN composer install --no-scripts --no-dev --no-plugins

COPY public /app/public
COPY src /app/src
COPY locales /app/locales

RUN composer dump -o --apcu

WORKDIR /app

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini && \
	echo "opcache.jit_buffer_size=100M" >> $PHP_INI_DIR/php.ini && \
	sed -i 's/variables_order = "GPCS"/variables_order = "EGPCS"/' $PHP_INI_DIR/php.ini;
