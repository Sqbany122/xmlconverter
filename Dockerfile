FROM php:8.1-apache

# Install required dependencies
RUN apt-get update && apt-get install -y \
    libxml2-dev \
    && docker-php-ext-install simplexml \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite for friendly URLs
RUN a2enmod rewrite

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 4002

# Copy custom PHP script to the container
COPY ./ /var/www/html
