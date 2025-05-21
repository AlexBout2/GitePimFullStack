# Requirements 

System d'exploitation : 
Linux Ubuntu-24.04 avec WSL2


Curl 
```
sudo apt update && install
sudo apt install curl git
```

Npm 

```
sudo apt install nodejs npm 
```

```
node -v
```

```
npm -v
```
# My SQL

Installer mysql server et client

```
sudo apt install mysql-server mysql-client -y

sudo systemctl status mysql
```

## Paramètrage sql pour wsl 
 
```
sudo mysql -u root
CREATE DATABASE gitePim;
CREATE USER 'dev'@'%' IDENTIFIED BY 'NXC2Z2ApU0qqCv';
GRANT ALL PRIVILEGES ON gitePim.* TO 'dev'@'%';
FLUSH PRIVILEGES;
```

##  Mysql workbench 

```
Host: localhost
user: dev
mdp: 'NXC2Z2ApU0qqCv'
```

# Laravel install 

Php composer et laravel installer serront directement télécharger via cette commande fournis par [Laravel](https://laravel.com/docs/11.x/installation#installing-php)

```
/bin/bash -c "$(curl -fsSL https://php.new/install/linux/8.4)"
```

Rafraichir le terminal 

```
. ~/.bashrc
```

Installer Laravel
```
composer global require laravel/installer
```

## Attention

Crée l'application
```
laravel new gitPim-app
```

Pick up du repo
```
git clone https://github.com/AlexBout2/GitePimFullStack.git
```

## Installation des dépendances 

```
composer install 
```

```
npm install 
```

Dans boite de dialogue : 

```
none 
MySQL
no
no
```

Modifier le .env

```.env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gitPim
DB_USERNAME=dev
DB_PASSWORD=NXC2Z2ApU0qqCv
```

#  Application

## Asset front end. 

Les assettes front en sont compiler par vite. 
```vite.config.js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css',
                'resources/js/app.js',
                //Style de Breeze
                'resources/css/tailwind.css',
                'resources/js/admin.js',

            ],
            refresh: true,
        }),
    ],
});
```

il y a deux type d'import un pour l'application '@vite(['resources/css/app.css', 'resources/js/app.js'])' pour l'application. 
Un dédié à breeze pour  pour l'authentification '@vite(['resources/css/tailwind.css', 'resources/js/admin.js'])'

## Authentification 
[Breez + Blade](https://laravel.com/docs/11.x/starter-kits#breeze-and-blade) est utilisé poru l'authentifcation. 
L'installation de ce denier avec composer.

```
composer require laravel/breeze --dev
```

```
php artisan breeze:install

php artisan migrate
npm install
npm run dev
```


Une fois les assets recompiler la page est disponible sur 


<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
