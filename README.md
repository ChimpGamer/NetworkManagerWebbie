<p align="center"><img src="https://imgur.com/wUhBSGv.png" width="400" alt="NetworkManager Logo"></p>

## Wiki
https://networkmanager.gitbook.io/wiki/networkmanager-webinterface/installation

## Updating

First enter the folder that contains the web files. Then run the following commands to update:
```shell
php artisan down
git pull
composer install --no-dev --optimize-autoloader
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan up
```
After that it should be running just fine again.

## Addons

Currently, there is only two addons implemented. Feel free to add more addons by creating a PR.
* [UltimateTags](https://polymart.org/resource/ultimatetags.3765) - You can enable this addon by running `php artisan module:enable UltimateTags`. The configuration for this addon is located in: `addons/UltimateTags/config`.
* [UltimateJQMessages]([https://polymart.org/resource/ultimatetags.3765](https://polymart.org/resource/ultimatejqmessages.4815)) - You can enable this addon by running `php artisan module:enable UltimateJQMessages`. The configuration for this addon is located in: `addons/UltimateJQMessages/config`.
