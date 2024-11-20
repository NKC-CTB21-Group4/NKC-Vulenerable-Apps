# Use an official PHP image as the base image
FROM php:7-alpine

# Set the working directory in the container
WORKDIR /app

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PDO_MYSQL extension
RUN apk --no-cache add --virtual .build-deps $PHPIZE_DEPS \
    && docker-php-ext-install pdo_mysql \
    && apk del .build-deps

# Install Chromium for ELF compatibility with headless browsing
RUN apk add --no-cache chromium

# Set environment variable to point to Chromium executable
ENV CHROME_BIN=/usr/bin/chromium-browser

COPY ./entrypoint.sh /usr/local/bin/entrypoint.sh

# 実行権限を与える
RUN chmod +x /usr/local/bin/entrypoint.sh

# Expose the port that your application will run on
EXPOSE 8081

# Start the PHP server when the container runs
CMD ["sh", "/usr/local/bin/entrypoint.sh"]
