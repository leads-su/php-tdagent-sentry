<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use TdAgentSentry\TransportFluentFactory;
use Fluent\Logger\FluentLogger;

$dsn = getenv("SENTRY_DSN");

// инициализация клиента sentry
$client = \Sentry\ClientBuilder::create(['dsn' => $dsn]);

// установка транспорта
$client->setTransportFactory(new TransportFluentFactory(
    new FluentLogger("unix:///var/run/td-agent/fluentd.sock","24224")
));

// бинд клиента к текущему scope
\Sentry\SentrySdk::init()->bindClient($client->getClient());

// блок с ошибкой
try {
    $a = new B();
} catch (\Throwable $exception) {
    var_dump(\Sentry\captureException($exception));
}
