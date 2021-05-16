FROM trafex/php-nginx AS base
ENV PHP_MAX_EXECUTION_TIME 110
ENV OBS_UPLOAD_DIR /obs-uploads/
USER root

FROM base AS collector
RUN mkdir -p $OBS_UPLOAD_DIR && chown -R nobody $OBS_UPLOAD_DIR
COPY --chown=nobody ./collector/lib/api.php /var/www/html/index.php

FROM collector AS tbd-project-name
USER nobody
