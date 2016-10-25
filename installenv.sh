#!/bin/bash

sudo apt-get update -y

sudo apt-get install -y apache2


sudo systemctl enable apache2

sudo systemctl  start apache2

git clone https://github.com/rads6/boostrap-website.git

rm -rf/var/www/*

mv boostrap-website/* /var/www/html



