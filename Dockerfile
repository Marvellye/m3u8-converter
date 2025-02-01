  FROM php:7.4-apache
  RUN apt-get update && apt-get install -y ffmpeg
  COPY . /var/www/html/
  WORKDIR /var/www/html
  RUN chmod 777 /var/www/html/tmp
  EXPOSE 80
