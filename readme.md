# Info al sur

Plataforma general de noticias.

### (Re)Iniciar base de datos

```sh
/usr/bin/php8.2 artisan migrate:refresh --seed
```

### Instalar Passport

```sh
/usr/bin/php8.2 /usr/local/bin/composer require laravel/passport

/usr/bin/php8.2 artisan passport:keys

/usr/bin/php8.2 artisan vendor:publish --tag=passport-config

```

### Configurar Client Secret / ID para Aplicación.

```sh
/usr/bin/php8.2 artisan passport:client --password
```
