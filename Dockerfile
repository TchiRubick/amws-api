FROM php:8.1-fpm

#Installing node 17.x
RUN curl -sL https://deb.nodesource.com/setup_17.x | bash -
RUN apt-get install -y --no-install-recommends nodejs

RUN npm install -g yarn

 #Copy composer.lock and composer.json
COPY  ./composer.json /var/www/

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    lsb-release \
    gnupg \
    build-essential \
    locales \
    zip \
    vim \
    unzip \
    git \
    curl

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions mbstring pdo_mysql zip exif pcntl gd

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY ./package.json /var/www/

# Change current user to www
USER $USER

COPY ./ /var/www

# Copy existing application directory permissions
RUN chown -R $USER:www-data storage
RUN chmod -R 775 storage

RUN chown -R $USER:www-data bootstrap/cache
RUN chmod -R 775 bootstrap/cache

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
