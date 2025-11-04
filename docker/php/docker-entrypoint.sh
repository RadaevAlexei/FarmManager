#!/bin/bash
set -e

echo "Waiting for MySQL to be ready..."
until MYSQL_PWD="$DB_PASSWORD" mysql -h"$DB_HOST" -u"$DB_USER" -e "SELECT 1" &> /dev/null; do
    echo "MySQL is unavailable - sleeping"
    sleep 2
done

echo "MySQL is ready!"

# Установка зависимостей, если vendor отсутствует
if [ ! -d "vendor" ]; then
    echo "Installing Composer dependencies..."
    composer install --no-interaction --prefer-dist
fi

# Инициализация проекта, если конфигурационные файлы отсутствуют
if [ ! -f "common/config/main-local.php" ]; then
    echo "Initializing Yii2 application..."
    php init --env=Development --overwrite=all
fi

# Настройка конфигурации базы данных из переменных окружения
if [ -f "common/config/main-local.php" ]; then
    echo "Configuring database connection..."
    # Создаем PHP скрипт для обновления конфигурации
    cat > /tmp/update_db_config.php <<'PHPSCRIPT'
<?php
$configFile = 'common/config/main-local.php';
$config = require $configFile;

// Обновляем настройки БД из переменных окружения
$config['components']['db'] = [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_NAME'),
    'username' => getenv('DB_USER'),
    'password' => getenv('DB_PASSWORD'),
    'charset' => 'utf8',
];

// Сохраняем обновленную конфигурацию
$content = "<?php\nreturn " . var_export($config, true) . ";\n";
file_put_contents($configFile, $content);
PHPSCRIPT
    php /tmp/update_db_config.php
    rm /tmp/update_db_config.php
fi

# Установка прав на директории
echo "Setting permissions..."
chmod -R 777 backend/runtime 2>/dev/null || true
chmod -R 777 backend/web/assets 2>/dev/null || true
chmod -R 777 frontend/runtime 2>/dev/null || true
chmod -R 777 frontend/web/assets 2>/dev/null || true

# Создание директорий, если их нет
mkdir -p backend/runtime backend/web/assets
mkdir -p frontend/runtime frontend/web/assets

# Применение миграций
echo "Running migrations..."
./yii migrate --interactive=0

echo "Initialization completed!"

# Запуск оригинальной команды
exec "$@"

