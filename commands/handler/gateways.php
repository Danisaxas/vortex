<?php
return function($chatId, $message, $config, $cbQuery = null) {
    $text = $config['texts']['gateways_txt'];
    $text = str_replace('{bot_name}', $config['bot_name'], $text);

    $keyboard = [
        'inline_keyboard' => $config['buttons']['gateways_menu']
    ];

    if ($cbQuery) {
        answerCallback($cbQuery['id']);
        editMessage($chatId, $cbQuery['message']['message_id'], $text, $config, 'HTML', $keyboard);
    } else {
        sendMessage($chatId, $text, $config, 'HTML', $keyboard, $message['message_id'] ?? null);
    }
};
