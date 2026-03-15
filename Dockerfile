# Base image na may PHP at Apache
FROM php:8.2-apache

# Install Python
RUN apt-get update && apt-get install -y python3 python3-pip

# Set working directory sa Apache web root
WORKDIR /var/www/html

# Copy PHP files
COPY php_app/ /var/www/html/

# Copy Python files
COPY python_app/ /var/www/html/python_app/

# Copy Python dependencies file
COPY requirements.txt /var/www/html/

# Install Python dependencies
RUN pip3 install --no-cache-dir -r requirements.txt

# Expose port 80
EXPOSE 80

# Start Apache in foreground
CMD ["apache2-foreground"]
