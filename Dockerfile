# Dockerfile
FROM webdevops/php-nginx:8.3-alpine

ENV WEB_DOCUMENT_ROOT=/app/public
WORKDIR /app

RUN apk add --no-cache git zip unzip bash
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . /app

RUN mkdir -p /app/storage /app/bootstrap/cache \
 && chown -R application:application /app

USER application

# Install deps tanpa dev + optimize autoload
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader || true

# (opsional) kalau pakai Vite, build di sini
# RUN corepack enable && corepack prepare pnpm@latest --activate \
#  && pnpm install && pnpm build
