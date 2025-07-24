FROM php:8.3-fpm-alpine

RUN apk add --no-cache nginx supervisor nodejs npm

COPY . /var/www/html

WORKDIR /var/www/html

RUN composer install --no-dev --optimize-autoloader

RUN npm install && npm run build

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
