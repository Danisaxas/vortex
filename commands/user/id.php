<?php
return function($chatId, $message, $config) {
    $userId = $message['from']['id'];
    $username = $message['from']['username'] ?? 'sin usuario';

    $text = $config['texts']['id_txt'];
    $text = str_replace(['{user_id}', '{username}'], [$userId, $username], $text);
    sendMessage($chatId, $text, $config);
};
