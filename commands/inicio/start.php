<?php
return function($chatId, $message, $config) {
    $dt = new DateTime("now", new DateTimeZone("Europe/Madrid"));
    $dateEs = $dt->format("Y-m-d h:i:s A");
    $username = $message['from']['username'] ?? 'usuario';

    $text = $config['texts']['start_txt'];
    $text = str_replace(
        ['{bot_name}', '{date_es}', '{username}'],
        [$config['bot_name'], $dateEs, $username],
        $text
    );
    sendMessage($chatId, $text, $config, 'HTML');
};
