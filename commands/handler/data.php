<?php
return function($chatId, $message, $config, $cbQuery = null) {
    $text = "¡Has pulsado el botón DATA!\nAquí puedes mostrar información, menús, etc.";
    
    if ($cbQuery) {
        answerCallback($cbQuery['id']); // Confirmación rápida al usuario
        editMessage($chatId, $cbQuery['message']['message_id'], $text, $config);
    } else {
        sendMessage($chatId, $text, $config, 'HTML', null, $message['message_id'] ?? null);
    }
};
