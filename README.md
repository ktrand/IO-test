# Установка и запуск проекта

## Шаги для настройки

1. **Создайте файл `.env`** в корневой папке проекта и в папке `backend`.

   ```bash
   touch .env
   touch backend/.env
   ```

2. **Скопируйте содержимое** файла `.env.example` из соответствующих папок в только что созданные файлы `.env`.

   ```bash
   cp .env.example .env
   cp backend/.env.example backend/.env
   ```

3. **Запустите Docker Compose** для поднятия всех сервисов.

   ```bash
   docker-compose up -d --build
   ```

Теперь ваш проект готов к использованию!

## Документация API
### Документация доступна по адресу: http://your_domain/api/documentation.