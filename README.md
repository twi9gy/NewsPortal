Инструкция по сборке:
1) Создать папку под проект и скачать в нее репозиторий: git cone https://github.com/twi9gy/NewsPortal
2) Восстановить резервную копию базы данных (db_news.dump): psql имя_базы < db_news.dump
3) Настроить строку подключения к базе данных в файле (.env). Пример : DATABASE_URL=postgresql://имя пользователя:пароль@127.0.0.1:5432/db_news?serverVersion=9.6&charset=utf8
4) Запустить: 1) symfony server:start -d
              2) symfony open:local
