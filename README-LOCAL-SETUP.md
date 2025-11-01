# FarmManager - Локальное развертывание

## Системные требования

- PHP >= 5.4.0 (рекомендуется PHP 7.4+)
- MySQL >= 5.6
- Composer
- Apache/Nginx (опционально, для продакшена)

## Пошаговая инструкция по установке

### 1. Клонирование проекта
```bash
git clone <repository-url>
cd FarmManager
```

### 2. Установка зависимостей
```bash
composer install
```

### 3. Настройка базы данных

#### Установка MySQL (macOS)
```bash
brew install mysql
brew services start mysql
```

#### Создание базы данных и пользователя
```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS farm_db CHARACTER SET utf8 COLLATE utf8_unicode_ci;"
mysql -u root -e "CREATE USER IF NOT EXISTS 'radaev'@'localhost' IDENTIFIED BY '123123';"
mysql -u root -e "GRANT ALL PRIVILEGES ON farm_db.* TO 'radaev'@'localhost'; FLUSH PRIVILEGES;"
```

### 4. Инициализация проекта
```bash
php init --env=Development --overwrite=all
```

### 5. Применение миграций
```bash
./yii migrate
```

### 6. Создание тестового пользователя
```bash
# Пользователь уже создан автоматически
# Логин: admin
# Пароль: password123
```

## Запуск проекта

### Способ 1: Использование скрипта (рекомендуется)
```bash
./start-dev.sh
```

### Способ 2: Ручной запуск
```bash
# Backend (порт 8080)
cd backend/web
php -S localhost:8080

# Frontend (порт 8081) - в новом терминале
cd frontend/web
php -S localhost:8081
```

## Доступ к приложению

- **Backend**: http://localhost:8080
- **Frontend**: http://localhost:8081

## Данные для входа

- **Логин**: admin
- **Пароль**: password123

## Структура проекта

```
FarmManager/
├── backend/          # Административная панель
├── frontend/         # Публичная часть
├── common/           # Общие компоненты
├── console/          # Консольные команды
└── environments/     # Конфигурации окружений
```

## Конфигурация

Основные конфигурационные файлы:
- `common/config/main-local.php` - настройки базы данных
- `backend/config/main-local.php` - настройки backend
- `frontend/config/main-local.php` - настройки frontend

## Устранение неполадок

### Проблемы с правами доступа
```bash
chmod -R 777 backend/runtime
chmod -R 777 backend/web/assets
chmod -R 777 frontend/runtime
chmod -R 777 frontend/web/assets
```

### Проблемы с базой данных
```bash
# Проверка подключения
mysql -u radaev -p123123 farm_db -e "SHOW TABLES;"
```

### Очистка кеша
```bash
./yii cache/flush-all
```

## Разработка

### Создание миграции
```bash
./yii migrate/create migration_name
```

### Применение миграций
```bash
./yii migrate
```

### Откат миграций
```bash
./yii migrate/down
```

## Полезные команды

```bash
# Список доступных команд
./yii help

# Создание контроллера
./yii gii/controller --controllerClass=app\\controllers\\MyController

# Создание модели
./yii gii/model --modelClass=MyModel --tableName=my_table
``` 