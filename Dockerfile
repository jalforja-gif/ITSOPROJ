# Base image with PHP and Apache
FROM php:8.2-apache

# Install Python AND PHP MySQLi extension
RUN apt-get update && apt-get install -y python3 python3-pip default-mysql-client \
    && docker-php-ext-install mysqli pdo pdo_mysql

# Set working directory
WORKDIR /var/www/html

# Copy PHP files
COPY login.php /var/www/html/
COPY process_login.php /var/www/html/
COPY db_connect.php /var/www/html/
COPY start_ml.php /var/www/html/
COPY .htaccess /var/www/html/

# Copy assets and other folders
COPY assets/ /var/www/html/assets/

# Copy Python API if needed
# COPY ml_api/ /var/www/html/ml_api/

# (Optional) Install Python dependencies if meron kang requirements.txt
# COPY requirements.txt /var/www/html/
# RUN pip3 install --no-cache-dir -r requirements.txt

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html/
RUN chmod -R 755 /var/www/html/

# Expose port 80
EXPOSE 80

# Start Apache in foreground
CMD ["apache2-foreground"]
