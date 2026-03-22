# Use an official PHP runtime as a parent image
FROM php:8.2-apache

# Install required system packages and dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Enable PDO and PDO_PGSQL extensions
RUN docker-php-ext-install pdo pdo_pgsql

# Copy custom Apache configuration
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# Enable Apache modules
RUN a2enmod rewrite

# Set working directory in the container
WORKDIR /var/www/html

# Copy project files to Apache web root into container
COPY . /var/www/html/

# Expose port 80 to allow incoming connections
EXPOSE 80