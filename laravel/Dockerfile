# Используем официальный образ PHP с Apache
FROM php:8.2-fpm

RUN apt-get update && apt-get install -y apt-transport-https
# Устанавливаем необходимые расширения PHP
RUN apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql
# Устанавливаем Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Копируем исходный код Laravel
COPY src/ /var/www/html/

# Устанавливаем зависимости Laravel
WORKDIR /var/www/html
RUN composer install

# Настраиваем права
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Открываем порт 9000 для PHP-FPM
EXPOSE 9000

# Запускаем PHP-FPM
CMD ["php-fpm"]