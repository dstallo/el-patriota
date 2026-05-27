# El Patriota

Portal de noticias de El Patriota

### (Re)Iniciar base de datos

```sh
php artisan migrate:refresh --seed
```

### Instalar Passport

```sh
php artisan install:api --passport

php artisan passport:keys

php artisan vendor:publish --tag=passport-config

```

### Configurar Client Secret / ID para Aplicación.

```sh
php artisan passport:client --password
```