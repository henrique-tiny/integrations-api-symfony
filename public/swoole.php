<?php

use App\Kernel;

$_SERVER['APP_RUNTIME_OPTIONS'] = [
    'host' => 'localhost',
    'port' => 3001,
    'mode' => SWOOLE_BASE,
    'settings' => [
        \Swoole\Constant::OPTION_WORKER_NUM => 50,
        \Swoole\Constant::OPTION_ENABLE_STATIC_HANDLER => true,
        \Swoole\Constant::OPTION_DOCUMENT_ROOT => dirname(__DIR__).'/public'
    ],
];

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    try {
        return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
    } catch (\Throwable $t) {
        echo $t->getMessage(), "\n";
        while(true) {}
    }
};
