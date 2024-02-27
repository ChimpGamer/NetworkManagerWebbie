<p align="center"><img src="https://imgur.com/wUhBSGv.png" width="400" alt="NetworkManager Logo"></p>

## Requirements
1. PHP 8.1 or 8.2
2. Nginx, Apache or some other web server software that supports php. Nginx is the favorite here!
3. Composer
4. Git

## Installation
1. Open the terminal to your web server.
2. Execute ``cd /var/www/``
3. Execute ``git clone https://github.com/ChimpGamer/NetworkManagerWebbie.git networkmanager`` to clone the repository.
4. Enter the directory by executing ``cd networkmanager``
5. Configure nginx to direct all requests to the ban list application. See [Nginx Configuration Example](#Nginx-Configuration-Example).
6. Run the ``composer install --optimize-autoloader --no-dev``
7. Make sure to set the owner of the networkmanager folder to www-data:www-data by executing ``sudo chown -R www-data:www-data /var/www/networkmanager``
8. Rename ``.env.example`` to ``.env`` by executing ``mv .env.example .env``.
9. Configure the settings in the .env file.
10. Execute ``php artisan key:generate``
11. You should now be able to browse to networkmanager.example.com (The Server name in your webserver configuration)

## Optimizations
To improve performance there are a few things you can do. Caching! Cache the config, routes and views. You can do this by running these commands:
```shell
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Nginx Configuration Example
```
server {
    listen 80;
    listen [::]:80;
    server_name networkmanager.example.com;
    root /var/www/networkmanager/public;
 
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
 
    index index.php;
 
    charset utf-8;
 
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
 
    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }
 
    error_page 404 /index.php;
 
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
 
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## Updating

First enter the folder that contains the web files. Then run the following commands to update:
```shell
php artisan down
git pull
composer install --no-dev
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan up
```
After that it should be running just fine again.
