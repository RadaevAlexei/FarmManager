# Docker-конфигурация для FarmManager

Этот документ описывает, как развернуть проект FarmManager с использованием Docker и Docker Compose.

## Предварительные требования

- Docker (версия 20.10 или выше)
- Docker Compose (версия 1.29 или выше)

## Быстрый старт

### 1. Настройка переменных окружения

Скопируйте файл `.env.example` в `.env` и при необходимости отредактируйте настройки:

```bash
cp .env.example .env
```

Или создайте файл `.env` вручную со следующим содержимым:

```env
# MySQL Database Configuration
DB_NAME=farm_db
DB_USER=radaev
DB_PASSWORD=123123
MYSQL_ROOT_PASSWORD=rootpassword
MYSQL_PORT=3306

# Application Environment
YII_DEBUG=true
YII_ENV=dev

# Ports Configuration
BACKEND_PORT=8080
FRONTEND_PORT=8081
PHPMYADMIN_PORT=8082
```

### 2. Запуск проекта

```bash
docker-compose up -d
```

Эта команда:
- Соберет Docker образы
- Запустит контейнеры (MySQL, PHP-FPM, Nginx, phpMyAdmin)
- Установит зависимости Composer
- Инициализирует проект Yii2
- Применит миграции базы данных
- Настроит права доступа

### 3. Доступ к приложению

После запуска приложение будет доступно по следующим адресам:

- **Backend**: http://localhost:8080
- **Frontend**: http://localhost:8081
- **phpMyAdmin**: http://localhost:8082

## Основные команды

### Остановка контейнеров

```bash
docker-compose stop
```

### Остановка и удаление контейнеров

```bash
docker-compose down
```

### Просмотр логов

```bash
# Все логи
docker-compose logs -f

# Логи конкретного сервиса
docker-compose logs -f php
docker-compose logs -f nginx
docker-compose logs -f mysql
```

### Выполнение команд в PHP контейнере

```bash
# Войти в контейнер
docker-compose exec php bash

# Применить миграции
docker-compose exec php ./yii migrate

# Откатить миграции
docker-compose exec php ./yii migrate/down

# Очистить кеш
docker-compose exec php ./yii cache/flush-all

# Установить зависимости
docker-compose exec php composer install

# Обновить зависимости
docker-compose exec php composer update
```

### Пересборка образов

```bash
# Пересобрать все образы
docker-compose build

# Пересобрать конкретный сервис
docker-compose build php

# Пересобрать без кеша
docker-compose build --no-cache
```

## Работа с базой данных

### Подключение к MySQL

```bash
# Через контейнер
docker-compose exec mysql mysql -u radaev -p123123 farm_db

# Или через phpMyAdmin
# Откройте http://localhost:8082 в браузере
```

### Импорт SQL дампа

Если у вас есть SQL дамп (например, `u0544583_life_cattle_db.sql`):

```bash
docker-compose exec -T mysql mysql -u radaev -p123123 farm_db < u0544583_life_cattle_db.sql
```

### Экспорт базы данных

```bash
docker-compose exec mysql mysqldump -u radaev -p123123 farm_db > backup.sql
```

### Просмотр данных MySQL

```bash
# Список баз данных
docker-compose exec mysql mysql -u root -prootpassword -e "SHOW DATABASES;"

# Список таблиц
docker-compose exec mysql mysql -u radaev -p123123 farm_db -e "SHOW TABLES;"
```

## Структура Docker конфигурации

```
FarmManager/
├── docker/
│   ├── php/
│   │   ├── Dockerfile          # PHP-FPM образ
│   │   ├── php.ini             # Конфигурация PHP
│   │   └── docker-entrypoint.sh # Скрипт инициализации
│   └── nginx/
│       └── default.conf        # Конфигурация Nginx
├── docker-compose.yml          # Docker Compose конфигурация
├── .env                        # Переменные окружения (создать из .env.example)
└── .dockerignore               # Исключения для Docker build
```

## Сервисы

### PHP (php)
- **Образ**: PHP 7.4-FPM
- **Расширения**: pdo_mysql, gd, zip, mbstring, intl, opcache и др.
- **Composer**: Установлен
- **Рабочая директория**: `/var/www/html`

### Nginx (nginx)
- **Образ**: Nginx Alpine
- **Порты**: 8080 (Backend), 8081 (Frontend)
- **Конфигурация**: `docker/nginx/default.conf`

### MySQL (mysql)
- **Образ**: MySQL 5.7
- **Порт**: 3306
- **База данных**: `farm_db` (настраивается через .env)
- **Пользователь**: `radaev` (настраивается через .env)

### phpMyAdmin (phpmyadmin)
- **Образ**: phpMyAdmin
- **Порт**: 8082
- **Доступ**: http://localhost:8082

## Volumes

Проект использует следующие volumes:

- `mysql_data` - данные MySQL
- `vendor_data` - кеш Composer зависимостей
- `backend_runtime` - runtime директория backend
- `backend_assets` - assets директория backend
- `frontend_runtime` - runtime директория frontend
- `frontend_assets` - assets директория frontend

## Решение проблем

### Проблемы с правами доступа

Если возникают проблемы с правами доступа:

```bash
docker-compose exec php chmod -R 777 backend/runtime backend/web/assets
docker-compose exec php chmod -R 777 frontend/runtime frontend/web/assets
```

### Проблемы с подключением к базе данных

Проверьте, что MySQL запущен:

```bash
docker-compose ps
docker-compose logs mysql
```

Проверьте настройки в `.env` файле.

### Очистка и перезапуск

Если нужно полностью пересоздать окружение:

```bash
# Остановить и удалить контейнеры и volumes
docker-compose down -v

# Пересобрать образы
docker-compose build --no-cache

# Запустить заново
docker-compose up -d
```

### Проблемы с миграциями

Если миграции не применяются автоматически:

```bash
docker-compose exec php ./yii migrate --interactive=0
```

### Просмотр ошибок PHP

```bash
# Логи PHP-FPM
docker-compose logs php

# Логи Nginx
docker-compose logs nginx

# Войти в контейнер и проверить логи
docker-compose exec php tail -f /var/www/html/backend/runtime/logs/app.log
```

## Разработка

### Hot Reload

Код приложения монтируется как volume, поэтому изменения в коде сразу отображаются в контейнере. После изменения кода просто обновите страницу в браузере.

### Установка новых зависимостей

```bash
# Добавить новую зависимость
docker-compose exec php composer require vendor/package

# Обновить composer.json в проекте
# После этого изменения будут сохранены в вашем локальном файле
```

### Отладка

Для отладки включите режим отладки в `.env`:

```env
YII_DEBUG=true
YII_ENV=dev
```

Модули Gii и Debug будут доступны в Backend.

## Production

Для production окружения:

1. Измените `.env`:
```env
YII_DEBUG=false
YII_ENV=prod
```

2. Оптимизируйте настройки PHP в `docker/php/php.ini`

3. Настройте правильные права доступа и безопасность

4. Используйте HTTPS (настройте SSL в Nginx)

## Дополнительная информация

- [Документация Docker](https://docs.docker.com/)
- [Документация Docker Compose](https://docs.docker.com/compose/)
- [Yii2 Документация](https://www.yiiframework.com/doc/guide/2.0/en)

