<?php
$config = require __DIR__ . '/config.php';

// Pon aquí la URL PÚBLICA de ngrok cada vez que corras ngrok
$ngrokUrl = 'https://289c-149-88-111-101.ngrok-free.app/bot.php'; // <-- Edita esta línea si cambia

$action = $argv[1] ?? 'set'; // por defecto registra, si pones "delete" lo borra

$token = $config['token'];

if ($action === 'delete') {
    $url = "https://api.telegram.org/bot$token/deleteWebhook";
    $msg = "Webhook eliminado.";
} else {
    $url = "https://api.telegram.org/bot$token/setWebhook?url=$ngrokUrl";
    $msg = "Webhook registrado: $ngrokUrl";
}

$result = file_get_contents($url);
echo "$msg\n";
echo "Respuesta Telegram: $result\n";
?>
