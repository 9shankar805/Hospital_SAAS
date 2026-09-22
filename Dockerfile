# ==========================================
# Stage 1: Build frontend assets with Vite
# ==========================================
FROM node:20-alpine AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build || true

# ==========================================
# Stage 2: Install Composer dependencies
# ==========================================
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# ==========================================
# Stage 3: Production runtime with FrankenPHP
# ==========================================
FROM dunglas/frankenphp:1-php8.3-bookworm

# Install required PHP extensions for Laravel & PostgreSQL / MySQL / SQLite
RUN install-php-extensions \
    pdo_pgsql \
    pdo_mysql \
    pdo_sqlite \
    bcmath \
    ctype \
    curl \
    dom \
    fileinfo \
    filter \
    hash \
    mbstring \
    openssl \
    pcre \
    session \
    tokenizer \
    xml \
    zip \
    intl \
    opcache \
    gd \
    redis

WORKDIR /app

# Copy application source code
COPY . .
COPY --from=vendor /app/vendor ./vendor
COPY --from=frontend /app/public/build ./public/build

# Copy frontend distribution assets to public if present
RUN if [ -d "/app/frontend/dist" ]; then \
        cp -rn /app/frontend/dist/* /app/public/ 2>/dev/null || true; \
    fi

# Set permissions for storage and bootstrap cache
RUN mkdir -p /app/storage/framework/cache/data \
             /app/storage/framework/sessions \
             /app/storage/framework/views \
             /app/storage/logs \
             /app/bootstrap/cache \
    && chown -R www-data:www-data /app/storage /app/bootstrap/cache \
    && chmod -R 775 /app/storage /app/bootstrap/cache

# DockHosting / Coolify build arguments
ARG APP_URL
ARG DATABASE_URL
ARG DB_DATABASE
ARG DB_CONNECTION
ARG DB_HOST
ARG DB_PORT
ARG DB_USERNAME
ARG DB_PASSWORD
ARG TRUSTED_PROXIES=*
ARG HTTPS=on
ARG PORT=8000
ARG COOLIFY_URL
ARG COOLIFY_FQDN
ARG COOLIFY_BRANCH
ARG COOLIFY_RESOURCE_UUID

# Environment defaults
ENV PORT=${PORT}
ENV SERVER_NAME="http://:${PORT}"
ENV APP_ENV=production
ENV APP_DEBUG=false

EXPOSE ${PORT}

# Entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]
