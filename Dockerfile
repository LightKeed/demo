FROM debian:bullseye

RUN sed -i 's/http:\/\/deb.debian.org/http:\/\/ftp.ru.debian.org/' /etc/apt/sources.list

RUN cp /usr/share/zoneinfo/Asia/Yekaterinburg /etc/localtime \
    && apt-get update \
    && apt-get install -y \
        ca-certificates \
        apt-transport-https \
        software-properties-common \
        zip \
        unzip \
        wget \
        curl \
        git \
        npm \
        openssl \
        ssh \
        nginx \
        gpg \
        lsb-release \
        gettext-base \
            && wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg \
				&& sh -c 'echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list' \
					&& apt-get update \
					&& apt-get install -y \
						php8.2-fpm \
						php8.2-mbstring \
						php8.2-zip \
						php8.2-gd \
						php8.2-xml \
						php8.2-curl \
						php8.2-mysql \
						php8.2-pdo \
                        php8.2-bcmath \
                        php8.2-ldap \
                        php8.2-mysqlnd \
                        php8.2-iconv \
                        php8.2-soap \
                        php8.2-common \
 							&& echo "timezone = Asia/Yekaterinburg" >> /etc/php/8.2/php.ini \
                            && echo "max_execution_time = 300" >> /etc/php/8.2/fpm/php.ini \
                            && echo "max_input_time = 60" >> /etc/php/8.2/fpm/php.ini \
                            && echo "memory_limit = 128M" >> /etc/php/8.2/fpm/php.ini \
                            && echo "upload_max_filesize = 256M" >> /etc/php/8.2/fpm/php.ini \
                            && echo "max_file_uploads = 100" >> /etc/php/8.2/fpm/php.ini \
                            && echo "post_max_size = 256M" >> /etc/php/8.2/fpm/php.ini \
                                && curl -fsSL https://deb.nodesource.com/setup_current.x | bash - && \
                                    apt-get install -y nodejs \
                                    build-essential \
                                        && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY /docker/nginx.conf /etc/nginx/nginx.conf

COPY / /var/www/
RUN chown -R www-data:www-data /var/www
RUN chmod -R 775 /var/www

RUN cd /var/www \
    && composer install --prefer-dist --no-dev --no-progress --no-scripts --no-interaction --optimize-autoloader \
    && npm install --silent --no-progress \
    && npm run build

ADD /docker/entrypoint.sh /usr/bin/entrypoint
RUN chmod a+x /usr/bin/entrypoint

WORKDIR /var/www

ENTRYPOINT [ "entrypoint" ]
