FROM php:8.2-fpm

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    libzip-dev unzip git curl libpng-dev libonig-dev \
    && docker-php-ext-install pdo_mysql zip mbstring exif pcntl bcmath

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar código do projeto
COPY . .

# Instalar dependências do Laravel
RUN composer install --no-interaction --optimize-autoloader

# Gerar chave do aplicativo
RUN php artisan key:generate

# Permissões corretas
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 9000

CMD ["php-fpm"]
