# Промпт для Docker-упаковки FarmManager

## Контекст проекта

FarmManager - это веб-приложение на базе Yii2 Advanced Project Template с архитектурой из трех частей:
- **Backend** - административная панель (порт 8080)
- **Frontend** - публичная часть (порт 8081)
- **Console** - консольные команды для миграций и других задач

## Технические требования

### Серверные компоненты:
1. **PHP 7.4+** (проект требует >=5.6, но лучше использовать 7.4+ для безопасности) с расширениями:
   - php-fpm
   - pdo_mysql
   - gd (с поддержкой FreeType) - требуется для CAPTCHA
   - zip (ext-zip)
   - openssl
   - mbstring
   - intl
   - xml
   - json
   - curl
   - opcache (опционально, для продакшена)
   
   **Важно**: Composer post-install скрипт запускает `php init --env=Development --overwrite=n`, но в Docker лучше запускать это вручную через entrypoint

2. **MySQL 5.7+** или **MariaDB 10.3+**
   - База данных: `farm_db`
   - Пользователь: `radaev` (по умолчанию, должен быть настраиваемым)
   - Пароль: `123123` (по умолчанию, должен быть настраиваемым)

3. **Nginx** - веб-сервер
   - Frontend: `http://localhost:8080` → `/frontend/web/`
   - Backend: `http://localhost:8081` → `/backend/web/`
   - Pretty URLs должны работать (rewrite правила)

4. **Composer** - для установки зависимостей PHP

### Структура проекта:
```
FarmManager/
├── backend/          # Backend приложение
│   ├── web/          # Точка входа (backend/web/index.php)
│   ├── runtime/      # Должна быть доступна для записи
│   └── web/assets/   # Должна быть доступна для записи
├── frontend/         # Frontend приложение
│   ├── web/          # Точка входа (frontend/web/index.php)
│   ├── runtime/      # Должна быть доступна для записи
│   └── web/assets/   # Должна быть доступна для записи
├── common/           # Общие компоненты
├── console/          # Консольные команды
├── vendor/           # Composer зависимости
├── environments/     # Конфигурации окружений
│   └── dev/          # Development окружение
├── init              # Скрипт инициализации проекта
└── yii               # Консольный скрипт Yii2
```

### Процесс инициализации:
1. Установка зависимостей: `composer install`
2. Инициализация проекта: `php init --env=Development --overwrite=all`
   - Копирует конфигурационные файлы из `environments/dev/`
   - Генерирует cookie validation keys
   - Устанавливает права на директории
3. Настройка конфигурации базы данных (через переменные окружения)
   - Обновление `common/config/main-local.php` с параметрами БД
4. Применение миграций: `./yii migrate --interactive=0`
5. Установка прав на директории runtime и assets:
   - `backend/runtime` - 0777
   - `backend/web/assets` - 0777
   - `frontend/runtime` - 0777
   - `frontend/web/assets` - 0777

### Конфигурационные файлы:
- `common/config/main-local.php` - настройки БД и mailer
- `backend/config/main-local.php` - cookieValidationKey для backend
- `frontend/config/main-local.php` - cookieValidationKey для frontend
- `common/config/params-local.php` - параметры приложения

## Задача: Создать Docker-конфигурацию

Создай полную Docker-конфигурацию для проекта FarmManager с использованием Docker Compose, которая позволит развернуть проект одной командой на любом компьютере.

### Требования к решению:

1. **Docker Compose файл** (`docker-compose.yml`) с сервисами:
   - `php` - PHP-FPM контейнер с необходимыми расширениями
   - `nginx` - Nginx веб-сервер
   - `mysql` - MySQL база данных
   - Опционально: `phpmyadmin` для удобства работы с БД

2. **Dockerfile для PHP** (`docker/php/Dockerfile` или `Dockerfile.php`):
   - Базироваться на официальном образе PHP 7.4-fpm или 8.0-fpm
   - Установить все необходимые PHP расширения
   - Установить Composer
   - Настроить рабочую директорию

3. **Nginx конфигурация** (`docker/nginx/default.conf` или `nginx.conf`):
   - Два виртуальных хоста:
     - Backend: `localhost:8080` → `/backend/web/`
     - Frontend: `localhost:8081` → `/frontend/web/`
   - Поддержка Pretty URLs (try_files)
   - FastCGI подключение к PHP-FPM
   - Обработка PHP файлов

4. **Переменные окружения** (`.env` файл):
   - Настройки MySQL (DB_HOST, DB_NAME, DB_USER, DB_PASSWORD)
   - Настройки приложения (можно оставить дефолтные)
   - YII_DEBUG и YII_ENV

5. **Скрипт инициализации** (`docker/init.sh` или `docker-entrypoint.sh`):
   - Ожидание готовности MySQL (healthcheck)
   - Установка Composer зависимостей (если vendor/ отсутствует)
   - Инициализация проекта через `php init --env=Development --overwrite=all`
   - Настройка конфигурационных файлов из переменных окружения:
     * Обновить `common/config/main-local.php` с параметрами БД из .env
     * Обновить `common/config/params-local.php` если нужно
   - Применение миграций: `./yii migrate --interactive=0`
   - Установка правильных прав на директории (runtime, assets) - chmod 777
   - Создание необходимых директорий если их нет

6. **.dockerignore** файл:
   - Исключить ненужные файлы из Docker context

7. **README или инструкция** (`DOCKER_README.md`):
   - Как запустить проект
   - Как остановить
   - Как посмотреть логи
   - Как выполнить консольные команды
   - Как работать с базой данных

### Особенности реализации:

1. **Volumes** для:
   - Кода приложения (для hot-reload при разработке)
   - vendor/ директории (можно закешировать)
   - runtime/ и assets/ директории (должны быть доступны для записи)

2. **Health checks** для MySQL, чтобы дождаться его готовности

3. **Networks** - создать внутреннюю сеть для связи между контейнерами

4. **Права доступа**:
   - PHP-FPM должен работать от пользователя www-data
   - Директории runtime/ и assets/ должны быть доступны для записи

5. **Безопасность**:
   - Не хранить пароли в коде, только в .env
   - .env файл должен быть в .gitignore

6. **Производительность**:
   - Использовать opcache для PHP (в продакшене)
   - Настроить правильные лимиты для Nginx

### Ожидаемый результат:

После выполнения `docker-compose up -d`:
- Backend доступен по адресу: http://localhost:8080
- Frontend доступен по адресу: http://localhost:8081
- База данных создана и миграции применены
- Приложение готово к использованию
- Все права доступа настроены корректно
- Конфигурационные файлы созданы и настроены

### Дополнительные команды:

Реализуй удобные команды в docker-compose.yml или отдельном скрипте:
- `docker-compose exec php ./yii migrate` - применить миграции
- `docker-compose exec php composer install` - установить зависимости
- `docker-compose logs -f` - просмотр логов
- `docker-compose exec php bash` - войти в PHP контейнер

### Пример структуры файлов:

```
FarmManager/
├── docker/
│   ├── php/
│   │   └── Dockerfile
│   ├── nginx/
│   │   └── default.conf
│   └── init.sh
├── docker-compose.yml
├── .env.example
├── .dockerignore
└── DOCKER_README.md
```

---

## Важные замечания:

1. **Конфигурация базы данных** должна автоматически подставляться в `common/config/main-local.php` из переменных окружения (.env файл)
2. **Cookie validation keys** должны генерироваться автоматически при первом запуске через `php init`
3. **Миграции** должны применяться автоматически при первом запуске через entrypoint скрипт
4. **Права доступа** на runtime/ и assets/ должны устанавливаться автоматически (chmod 777)
5. Решение должно работать "из коробки" - просто `docker-compose up -d` и всё работает
6. **Volumes**: 
   - Код приложения должен быть смонтирован как volume для разработки
   - vendor/ можно закешировать через named volume для ускорения
   - runtime/ и assets/ должны быть volumes для сохранения данных между перезапусками
7. **Окружение**: Использовать Development окружение по умолчанию (можно сделать настраиваемым через .env)
8. **Composer**: Установить Composer в PHP контейнер для работы с зависимостями
9. **Nginx**: Настроить правильные пути к frontend/web и backend/web директориям
10. **Логи**: Все логи должны быть доступны через `docker-compose logs`

---

## Пример использования промпта:

1. Откройте Cursor AI
2. Скопируйте весь этот промпт
3. Вставьте в чат Cursor AI
4. Cursor создаст все необходимые Docker файлы
5. После создания выполните: `docker-compose up -d`

## Дополнительная информация:

### SQL дамп базы данных:
В проекте есть файл `u0544583_life_cattle_db.sql` - это дамп базы данных. Можно добавить опциональную поддержку импорта этого дампа при первом запуске (если он существует), но это не обязательно, так как миграции должны создать структуру БД.

### Альтернативные порты:
Если порты 8080 и 8081 заняты, можно использовать другие порты через переменные окружения в docker-compose.yml.

### Режим разработки vs продакшн:
- **Development**: YII_DEBUG=true, YII_ENV=dev, включены Gii и Debug модули
- **Production**: YII_DEBUG=false, YII_ENV=prod, оптимизированные настройки

---

**Примечание**: Этот промпт можно использовать в Cursor AI для автоматического создания Docker-конфигурации проекта FarmManager. После создания всех файлов проект можно будет развернуть одной командой `docker-compose up -d` на любом компьютере с установленным Docker и Docker Compose.

