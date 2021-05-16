FROM trafex/php-nginx

ENV PHP_MAX_EXECUTION_TIME 110

COPY  --chown=nobody ./collector/lib/api.php /var/www/html/index.php
