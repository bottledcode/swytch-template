FROM php:8.2-apache

ENV SWYTCH_STATE_SECRET=chamge-me SWYTCH_DEFAULT_LANGUAGE=en SWYTCH_SUPPORTED_LANGUAGES=en,nl,de SWYTCH_LANGUAGE_DIR=/var/www/locales

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions @composer dom intl mbstring sodium zip uuid opcache apcu

RUN a2enmod rewrite headers expires && \
	sed -e '/<Directory \/var\/www\/>/,/<\/Directory>/s/AllowOverride None/AllowOverride All/' -i /etc/apache2/apache2.conf

COPY composer.json composer.lock /var/www/
WORKDIR /var/www
RUN composer install

COPY html src locales /var/www/

RUN composer dump -o --apcu

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini && \
	echo "opcache.jit_buffer_size=100M" >> $PHP_INI_DIR/php.ini
