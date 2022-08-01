FROM php:7.4-apache
WORKDIR /var/www/html
COPY html/* .
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
EXPOSE 80
CMD ["/var/www/scripts/daemon.sh"]
