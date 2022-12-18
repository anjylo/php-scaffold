FROM php:8.0.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    vim \
    unzip

RUN docker-php-ext-install pdo pdo_mysql 

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY --from=composer/composer /usr/bin/composer /usr/bin/composer

# set user with uid ang gid 1000
ARG USER=user
ARG UID=1000
ARG GID=1000
ARG PW=password

RUN useradd -m ${USER} --uid=${UID} && echo "${USER}:${PW}" | \
      chpasswd

USER ${UID}:${GID}

WORKDIR /var/www
