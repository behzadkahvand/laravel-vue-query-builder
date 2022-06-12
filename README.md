
## laravel-vuejs-query-builder

This is simple a query builder project has been developed with laravel and vuejs and typescript

### Bring the containers up & running
```
$ cd deploy && docker-compose up -d --build
```

### Install composer requirements for the backend side
```
$ docker-compose exec fpm bash
```

inside the fpm container, run:
```
composer install
```

### Install requirements for the frontend side
```
$ cd frontend-vue 

$ npm install && npm run dev
```

