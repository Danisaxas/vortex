<?php
return function($chatId, $message, $config) {
    // Fecha y hora en Madrid
    $dt = new DateTime("now", new DateTimeZone("Europe/Madrid"));
    $dateEs = $dt->format("Y-m-d h:i:s A");

    $username = $message['from']['username'] ?? 'usuario';
    $text = $config['texts']['start_txt'];
    $text = str_replace(
        ['{bot_name}', '{date_es}', '{username}'],
        [$config['bot_name'], $dateEs, $username],
        $text
    );

    // Carga botones
    $keyboard = [
        'inline_keyboard' => require __DIR__ . '/../../texts/button/es.php'
    ];

    sendMessage($chatId, $text, $config, 'HTML', $keyboard);
};
