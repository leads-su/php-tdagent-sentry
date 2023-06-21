FROM php:7.3.33-cli-bullseye

RUN apt update && apt install -y sudo mc nano htop

RUN curl -fsSL https://toolbelt.treasuredata.com/sh/install-debian-bullseye-td-agent4.sh | sh
COPY td-agent.conf /etc/td-agent/td-agent.conf

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html/

CMD composer install \
    && /opt/td-agent/bin/fluentd -d /var/run/td-agent.pid -c /etc/td-agent/td-agent.conf --log /var/log/td-agent/td-agent.log \
    && tail -n +1 -f /var/log/td-agent/td-agent.log
