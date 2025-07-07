FROM php:8.4-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    git \
    nginx \
    supervisor \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo pdo_mysql zip

# Install Xdebug for coverage
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create ec2-user account with umask 0000
RUN useradd -m -s /bin/bash ec2-user
RUN echo "umask 0000" >> /home/ec2-user/.bashrc
RUN echo "umask 0000" >> /home/ec2-user/.profile

# Configure nginx
COPY docker/web/nginx.conf /etc/nginx/sites-available/default
COPY docker/web/nginx.conf /etc/nginx/sites-enabled/default

# Configure PHP-FPM to run as ec2-user
RUN sed -i 's/user = www-data/user = ec2-user/' /usr/local/etc/php-fpm.d/www.conf
RUN sed -i 's/group = www-data/group = ec2-user/' /usr/local/etc/php-fpm.d/www.conf

# Configure supervisor
COPY docker/web/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Set working directory
WORKDIR /var/www/html

# Set proper permissions - ec2-user owns everything under /var/www/html
RUN chown -R ec2-user:ec2-user /var/www/html
RUN chmod -R 755 /var/www/html

# Expose port 80
EXPOSE 80

# Start supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
