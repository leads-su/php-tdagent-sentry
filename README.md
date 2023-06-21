
# Транспортировка событий из приложения в Sentry через td-agent

## Использование

Поднять инфраструктуру:
```bash
$ export SENTRY_ADDR="http://sentry.local:9000"
$ export SENTRY_DSN="dsn"
$ docker compose up -d
```

Теперь можно зайти в контейнер с приложением и сгенерировать событие ошибки:
```bash
$ docker compose exec app php -f /var/www/html/src/index.php
```

В ответ будет распечатка объекта с идентификатором события:
```php
object(Sentry\EventId)#61 (1) {
["value":"Sentry\EventId":private]=>
string(32) "5a2c307521e147859af5b86d165d97cd"
}
```

> Если в ответ NULL значит что-то пошло не так ...

После прохождения через все узлы `td-agent` событие будет отправлено в `Sentry`.
