# Use an official PHP image as the base image
FROM php:8.0-alpine

# Set the working directory in the container
WORKDIR /var/www

COPY . .

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer install 

RUN docker-php-ext-install pdo_mysql

COPY --chown=www-data:www-data ./assets/avatar ./assets/avatar
COPY --chown=www-data:www-data ./assets/content ./assets/content

# Expose the port that your application will run on
EXPOSE 8080

# Start the PHP server when the container runs
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
