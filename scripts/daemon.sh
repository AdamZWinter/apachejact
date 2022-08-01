#!/bin/sh

echo "Starting daemon.sh " >> /var/www/logs/daemon.log
apache2-foreground
sleep 2

while [ 1 -eq 1 ]
do


if [ "$(ls -A /var/www/html)" ]; then

result=`/usr/local/bin/php -v`

resultsize=${#result}
thresholdsize=8

if [ "$resultsize" -gt "$thresholdsize" ]; then
echo $result >> /var/www/logs/daemon.log
#echo $result
fi

fi

sleep 30
done
