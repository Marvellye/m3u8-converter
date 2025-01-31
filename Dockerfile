FROM php:7.4-apache
  RUN apt-get update && apt-get install -y ffmpeg
  COPY . /var/www/html/
  WORKDIR /var/www/html
  EXPOSE 80
