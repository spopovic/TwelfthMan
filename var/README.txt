## Local development setup

### Basics

- clone the github repository
- run ```composer install```
- copy ```env.example``` into ```.env``` and edit its contents. The config files from config/APP_ENV will be loaded on top of the default config files
- enable apache header and rewrite rule modules

- run ```php artisan key:generate```
- run ```php artisan storage:link```
- run ```php artisan migrate```
- run ```php artisan db:seed```

### How to add images using console command.

Add a zip file to the directory: \public\images, then run ```php artisan import:images```

### How to add composer dependencies

Run 'composer update PACKAGE-NAME' to update third party dependencies

### When changing the angular app, build it using ng cli:
ng build --base-href /app/ --target=production --environment=prod
### beware the angular cli has a bug where base href gets outputed as local path instead of just /app/ (fix manually in the output index.html if that happens)

### Move the built files from the dist/ folder into the laravel's /var/public/app/
