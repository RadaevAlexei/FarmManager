#!/bin/bash
# Скрипт для исправления проблемы с GitHub OAuth токеном

echo "=== Исправление GitHub OAuth токена в Composer ==="
echo ""

# Шаг 1: Проверяем текущую конфигурацию
echo "1. Проверка текущей конфигурации:"
if [ -f "$HOME/.composer/auth.json" ]; then
    echo "✓ Файл auth.json найден: $HOME/.composer/auth.json"
    echo "Текущее содержимое:"
    cat "$HOME/.composer/auth.json"
else
    echo "✗ Файл auth.json не найден"
fi
echo ""

# Шаг 2: Резервная копия
if [ -f "$HOME/.composer/auth.json" ]; then
    echo "2. Создание резервной копии:"
    cp "$HOME/.composer/auth.json" "$HOME/.composer/auth.json.backup.$(date +%Y%m%d_%H%M%S)"
    echo "✓ Резервная копия создана"
    echo ""
fi

# Шаг 3: Удаление проблемного токена
echo "3. Исправление конфигурации:"
if [ -f "$HOME/.composer/auth.json" ]; then
    # Удаляем github-oauth из auth.json
    php -r "
    \$config = json_decode(file_get_contents(getenv('HOME') . '/.composer/auth.json'), true);
    if (isset(\$config['github-oauth'])) {
        unset(\$config['github-oauth']);
    }
    if (isset(\$config['http-basic'])) {
        unset(\$config['http-basic']['github.com']);
    }
    file_put_contents(getenv('HOME') . '/.composer/auth.json', json_encode(\$config, JSON_PRETTY_PRINT));
    echo '✓ Конфигурация обновлена\n';
    "
else
    # Создаём новый auth.json
    mkdir -p "$HOME/.composer"
    echo '{}' > "$HOME/.composer/auth.json"
    echo "✓ Создан новый auth.json"
fi
echo ""

# Шаг 4: Проверка результата
echo "4. Проверка новой конфигурации:"
cat "$HOME/.composer/auth.json"
echo ""

# Шаг 5: Проверка работы composer
echo "5. Проверка работы composer:"
php composer.phar --version 2>&1 | head -5

echo ""
echo "=== Готово! ==="
echo ""
echo "Если проблема сохраняется, попробуйте:"
echo "1. Удалить полностью: rm $HOME/.composer/auth.json"
echo "2. Или использовать GitHub Personal Access Token (см. ниже)"
echo ""
echo "Для создания Personal Access Token:"
echo "https://github.com/settings/tokens"
echo "Вам нужен токен с правом: 'repo' (Full control of private repositories)"
