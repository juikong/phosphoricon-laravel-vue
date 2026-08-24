# Phosphor Icons Library for Laravel Sample Vue Application

Sample Vue Application for Phosphor Icons Library for Laravel (https://github.com/juikong/phosphoricon).

## Installation

Copy .env.example into .env file

Install composer package

```bash
composer install
```

Generate APP_KEY

```bash
php artisan key:generate
```

Run migration

```bash
php artisan migrate
```

Import icon data

```bash
php artisan phosphor-icon:import
```

Run seeder for sample data

```bash
php artisan db:seed
```

Install npm packages

```bash
npm install
```

Build application

```bash
npm run build
```

## License

[PhosphorIcons](https://phosphoricons.com/)
[MIT](https://choosealicense.com/licenses/mit/)
