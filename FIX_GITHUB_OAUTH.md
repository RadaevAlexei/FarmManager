# Исправление проблемы с GitHub OAuth токеном в Composer

## Проблема
```
[UnexpectedValueException]
Your github oauth token for github.com contains invalid characters: "апвап"
```

## Решение

### Способ 1: Простое удаление конфигурации (рекомендуется)

Выполните на проде:

```bash
# 1. Посмотреть текущую конфигурацию
cat ~/.composer/auth.json

# 2. Создать резервную копию
cp ~/.composer/auth.json ~/.composer/auth.json.backup

# 3. Удалить проблемный файл
rm ~/.composer/auth.json

# 4. Проверить работу composer
php composer.phar --version
```

### Способ 2: Исправить конфигурацию

Если файл нужно сохранить, выполните:

```bash
# 1. Создать резервную копию
cp ~/.composer/auth.json ~/.composer/auth.json.backup

# 2. Отредактировать файл
nano ~/.composer/auth.json

# Удалите секцию "github-oauth" или исправьте токен
# Удалите строки типа:
#     "github.com": "апвап"  или другую некорректную строку
```

Оставьте файл в таком виде:
```json
{
    "bitbucket-oauth": {},
    "github-oauth": {},
    "gitlab-oauth": {},
    "gitlab-token": {},
    "http-basic": {}
}
```

### Способ 3: Использование правильного GitHub токена

Если вам нужен GitHub токен для private repositories:

1. Создайте токен на GitHub:
   - Перейдите: https://github.com/settings/tokens
   - Нажмите "Generate new token (classic)"
   - Выберите права: `repo` (Full control of private repositories)
   - Скопируйте токен

2. Настройте composer:

```bash
# Введите ваш токен, когда composer попросит
php composer.phar require "hail812/yii2-adminlte3=^1.1" "hail812/yii2-adminlte-widgets=^1.0"
```

## После исправления

После исправления конфигурации выполните обновление:

```bash
php composer.phar require "hail812/yii2-adminlte3=^1.1" "hail812/yii2-adminlte-widgets=^1.0" --update-with-dependencies
```

Или, если у вас уже есть обновлённый composer.json и composer.lock:

```bash
# Залейте изменения с локальной машины
git pull origin develop

# Установите зависимости
php composer.phar install
```

## Проверка работы

После исправления проверьте:

```bash
php composer.phar --version
php composer.phar self-update
```

Если всё работает, обновите пакеты и очистите кеш:

```bash
php yii cache/flush-all
# или
rm -rf runtime/cache/*
```

