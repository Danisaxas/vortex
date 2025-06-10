<?php
return function($chatId, $message, $config, $cbQuery) {
    $start = require __DIR__ . '/../inicio/start.php';
    if (is_callable($start)) {
        $start($chatId, $cbQuery['message'], $config, $cbQuery);
    }
};
