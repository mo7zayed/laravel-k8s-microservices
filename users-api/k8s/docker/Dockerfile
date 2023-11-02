FROM ubuntu:22.04

WORKDIR /var/www/html

ENV TZ=UTC

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update \
    && apt-get install -y gnupg gosu curl ca-certificates libcap2-bin libpng-dev python2 dnsutils librsvg2-bin \
    && curl -sS 'https://keyserver.ubuntu.com/pks/lookup?op=get&search=0x14aa40ec0831756756d7f66c4f4ea0aae5267a6c' | gpg --dearmor | tee /usr/share/keyrings/ppa_ondrej_php.gpg > /dev/null \
    && echo "deb [signed-by=/usr/share/keyrings/ppa_ondrej_php.gpg] https://ppa.launchpadcontent.net/ondrej/php/ubuntu jammy main" > /etc/apt/sources.list.d/ppa_ondrej_php.list \
    && apt-get update \
    # install php8.2
    && apt-get install -y php8.2-cli php8.2-curl php8.2-mysql php8.2-mbstring php8.2-xml php8.2-zip php8.2-bcmath php8.2-intl php8.2-redis php8.2-swoole \
    && curl -sLS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer \
    # cleanup
    && apt-get -y autoremove \
    && apt-get -y autoclean \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Installing composer dependencies
COPY composer.json composer.lock ./
COPY database/ database/
RUN composer install --no-interaction --optimize-autoloader --no-scripts

COPY . .

RUN cp ./k8s/docker/php.ini /etc/php/8.2/cli/conf.d/99-k8s.ini

RUN mkdir -p \
    storage/framework/{sessions,views,cache} \
    storage/logs \
    bootstrap/cache \
    storage \
    bootstrap/cache \
    && chmod -R ug+rwx storage bootstrap/cache

EXPOSE 80

CMD ["/bin/sh", "-c", "php artisan optimize && php artisan octane:start --host=0.0.0.0 --rpc-port=6001 --port=80"]
