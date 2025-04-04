# **University API**

Этот проект представляет собой API для управления университетами, студентами и их посещениями. API построен на Laravel и использует Docker для контейнеризации. Документация API доступна через Swagger UI.

### Технологии:

**Laravel** — PHP-фреймворк для разработки API.

**Docker** — контейнеризация приложения.

**Swagger** — документация и тестирование API.

**PostgreSQL** — база данных для хранения информации.

**ClickHouse** — база данных для аналитики посещений.

**Redis** — кэширование данных.

**Meilisearch** — полнотекстовый поиск.

И ещё некоторые, в которых предстоит разобраться.


## Установка и запуск

**Клонируйте репозиторий:**

`git clone https://github.com/your-repo/university-api.git`

`cd university-api`

**Запустите Docker-контейнеры:**

Соберите и запустите контейнеры с помощью Docker Compose:

`docker-compose up --build -d`

**Подключитесь к контейнеру Laravel:**

Откройте терминал внутри контейнера Laravel:

`docker-compose exec -it laravel sh`

Запустите миграции:

Внутри контейнера выполните команду для создания таблиц в базе данных:

`php artisan migrate`


Сгенерируйте документацию Swagger:

Сгенерируйте документацию API:

`php artisan l5-swagger:generate`

Откройте Swagger UI:

Перейдите по адресу [Swagger](http://localhost:8080), чтобы увидеть документацию API.

**Использование API**

API доступен по адресу: http://localhost/api/v1/