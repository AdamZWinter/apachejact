#  php:7.4-apache is from Debian GNU/Linux 11 (bullseye)
FROM php:7.4-apache
WORKDIR /var/www/html
COPY html/* ./
WORKDIR /var/www
RUN mkdir /var/www/secrets
COPY secrets/* /var/www/secrets/
RUN mkdir /var/www/scripts
COPY scripts/* /var/www/scripts/
RUN mkdir /var/www/logs
RUN chown -R www-data html
RUN chown -R www-data logs
RUN chmod -R 400 secrets
RUN apt-get update
RUN docker-php-ext-install mysqli
RUN pear install --alldeps mail
RUN pear install Mail_mimeDecode
RUN apt-get install -y libzip-dev
RUN docker-php-ext-install zip

RUN apt-get update && \
    apt-get -y install openssh-server && \
    mkdir -p /var/run/sshd

EXPOSE 80
EXPOSE 22
CMD ["/var/www/scripts/daemon.sh >> /var/www/logs/daemon.log 2>&1 &"]
CMD ["/usr/sbin/sshd -D -e /var/www/logs/sshd.log 2>&1 &"]
