FROM python:3.8-slim

RUN apt-get update \
    && apt-get install -y cron \
    && apt-get autoremove -y
COPY ./src ./obs-face
WORKDIR ./obs-face
RUN pip install -r requirements.txt
RUN echo "*/15 * * * * root bash /obs-face/runWiesbaden.sh >> /var/log/runObsWiesbaden.log 2>&1" >> /etc/crontab
CMD ["cron", "-f"]