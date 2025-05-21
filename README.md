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
            input: [
                'resources/css/front.css',
                'resources/js/front.js',

            ],
            refresh: true,
        }),
    ],
});
```

il y a deux type d'import un pour l'application '@vite(['resources/css/app.css', 'resources/js/app.js'])' 
Un dédié à breeze pour l'administration
 '@vite(['resources/css/front.css', 'resources/js/front.js'])'
# Panel admin 


[Fillament](https://filamentphp.com/docs/3.x/panels/installation) est utilisé  pour le panel. Le package intègre déjà une gestion des utilsateur 
L'installation de ce denier avec composer.

```
composer require filament/filament:"^3.3" -W

php artisan filament:install --panels
```

Un nouvelle utilisateur peut être crée 

```
php artisan make:filament-user
```
Les différents widgets sont dans app/Filament/Widgets/ et ces classes sont appeler dans 'app/Providers/Filament/AdminPanelProvider.php' 
app/Filament/Widgets/Bungalows.php affiche les reservation des bungalow. 

## Production
A utiliser avant la production
https://filamentphp.com/docs/3.x/panels/installation#deploying-to-production

```
php artisan filament:optimize
```
```
php artisan make:filament-user
```