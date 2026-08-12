# Build frontend assets
FROM node:22-alpine AS assets

WORKDIR /app

COPY package.json package-lock.json* ./
RUN if [ -f package-lock.json ]; then npm ci; else npm install; fi

COPY vite.config.js ./
COPY resources ./resources
COPY public ./public

RUN npm run build


# Laravel + Nginx + PHP-FPM (Render)
FROM richarvey/nginx-php-fpm:3.1.6

COPY . /var/www/html
COPY --from=assets /app/public/build /var/www/html/public/build

# Image config
ENV SKIP_COMPOSER=1
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV RUN_SCRIPTS=1
ENV REAL_IP_HEADER=1

# Laravel defaults (overridden by Render env vars)
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr
ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /var/www/html

CMD ["/start.sh"]
