FROM richarvey/nginx-php-fpm:2.0.4

COPY . .

# Image config
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Laravel config
ENV APP_NAME=Libeyondea
ENV APP_ENV=production
ENV APP_KEY=base64:/DgWcIH4BkPQuxoj8IEM1fJ0Hw8j8G7YlCjux4TWVhc=
ENV APP_DEBUG=false
ENV APP_URL=https://backend-forum-example.onrender.com
ENV FRONTEND_VERIFY_EMAIL_URL=https://frontend-forum-example.vercel.app/verify_users?url=
ENV IMG_FOLDER=images

ENV LOG_CHANNEL=stack
ENV LOG_LEVEL=debug

ENV DB_CONNECTION=mysql
ENV DB_HOST=b7javkczfegkqi1zz8go-mysql.services.clever-cloud.com
ENV DB_PORT=3306
ENV DB_DATABASE=b7javkczfegkqi1zz8go
ENV DB_USERNAME=ukcued9eeuec0x0t
ENV DB_PASSWORD=3wkFPTh55cxu92qqAcMc

ENV BROADCAST_DRIVER=log
ENV CACHE_DRIVER=file
ENV QUEUE_CONNECTION=sync
ENV SESSION_DRIVER=file
ENV SESSION_LIFETIME=120

ENV MEMCACHED_HOST=127.0.0.1

ENV REDIS_HOST=127.0.0.1
ENV REDIS_PASSWORD=null
ENV REDIS_PORT=6379

ENV MAIL_MAILER=smtp
ENV MAIL_HOST=smtp.googlemail.com
ENV MAIL_PORT=465
ENV MAIL_USERNAME=admjnwapviip@gmail.com
ENV MAIL_PASSWORD=Wtpmjgda18051999
ENV MAIL_ENCRYPTION=ssl
ENV MAIL_FROM_ADDRESS=admjnwapviip@gmail.com
ENV MAIL_FROM_NAME=Libeyondea

ENV AWS_ACCESS_KEY_ID=
ENV AWS_SECRET_ACCESS_KEY=
ENV AWS_DEFAULT_REGION=ap-southeast-1
ENV AWS_BUCKET=

ENV PUSHER_APP_ID=
ENV PUSHER_APP_KEY=
ENV PUSHER_APP_SECRET=
ENV PUSHER_APP_CLUSTER=mt1

ENV MIX_PUSHER_APP_KEY=
ENV MIX_PUSHER_APP_CLUSTER=

# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version
RUN composer update --no-dev --working-dir=/var/www/html

RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache
RUN php artisan clear-compiled
RUN chmod -R 777 storage
RUN php artisan passport:keys
