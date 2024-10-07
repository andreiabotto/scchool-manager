## School manager

## Installation

1. Create a copy of the .env.example file and rename it to .env

```cp .env.example .env```

2. Initiate de environment

```dockerfile docker-compose up -d --build```

3. Install de packages

```dockerfile docker-compose exec app composer install```

4. Generate key

```dockerfile docker-compose exec app php artisan key:generate```

5. Install and run the library of front-end

```dockerfile docker-compose exec app npm install && npm run dev```

6. Create database

```dockerfile docker-compose exec app php artisan migrate```

```dockerfile docker-compose exec app php artisan db:seed```

7. Generate the documentation swagger

```dockerfile docker-compose exec app php artisan l5-swagger:generate```

8. Access the page

```http GET http://localhost:8000 ```

9. Login for admin

``` e-mail: admin@email.com password: admin123 ```
