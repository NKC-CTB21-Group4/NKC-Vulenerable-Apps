# Use an official PHP image as the base image
FROM php:7-alpine

# Set the working directory in the container
WORKDIR /app

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Expose the port that your application will run on
EXPOSE 8081

# Start the PHP server when the container runs
CMD ["php", "-S", "0.0.0.0:8081", "-t", "public"]
