FROM  webdevops/php-nginx:7.4

ENV PHP_MAX_EXECUTION_TIME 110
COPY ./app /app
COPY ./php.ini opt/docker/etc/php/php.ini
RUN mkdir -p /obs-uploads
RUN chown -R application /obs-uploads