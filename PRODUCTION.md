
# Laravel opti
https://laravel.com/docs/11.x/deployment#main-content

Si vous avec télécharger l'application depuis git 

```
php artisan key:generate --force
```

## caches

php artisan config:cache
php artisan route:cache
php artisan view:cache

php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

## Middleware 


## Packages php avec composer

```
composer install --no-dev --optimize-autoloader
composer dump-autoload --optimize
```

## Liens symboliques 

```
php artisan storage:link
```

# Filament
https://filamentphp.com/docs/3.x/panels/installation#optimizing-filament-for-production

```
php artisan filament:upgrade
```

```
php artisan filament:assets
```

# Asset front build

https://vite.dev/guide/build#building-for-production

```
npm install && npm run build
```

```
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/front.css',
                'resources/js/front.js'
            ],
            refresh: true,
        }),
    ],
});

```

Vite config de prod. 
# Railway 

Commande de deployment ? 

```
php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT
```

APP_URL="[https://gitepimfullstack-production.up.railway.app](https://gitepimfullstack-production.up.railway.app)"