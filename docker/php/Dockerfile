FROM php:8.1-apache

# Install required PHP extensions
RUN apt-get update && apt-get install -y \
    libxml2-dev \
    && docker-php-ext-install simplexml \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Update Apache to listen on port 4002
RUN echo "Listen 4002" >> /etc/apache2/ports.conf
RUN sed -i 's/<VirtualHost \*:80>/<VirtualHost *:4002>/' /etc/apache2/sites-available/000-default.conf

# Expose port 4002
EXPOSE 4002

# Copy application files
COPY ./app /var/www/html

# Copy custom php.ini to override default PHP configuration
COPY ./docker/php/php.ini /usr/local/etc/php/php.ini
