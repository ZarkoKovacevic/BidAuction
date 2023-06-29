# Use an official PHP runtime as the base image
FROM php:8.2-fpm

# Set the working directory inside the container
WORKDIR /app

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the project files to the working directory
COPY . .

# Install project dependencies
RUN composer install --no-interaction

# Expose the port to access the CLI command
EXPOSE 8000

# Start PHP-FPM
CMD ["php-fpm"]