<?php
$config = require __DIR__ . '/config.php';

// Pega aquí la URL de Railway:
$botUrl = 'https://vortex-production-1e3e.up.railway.app/bot.php';

$action = $argv[1] ?? 'set'; // Por defecto registra, si pones "delete" lo borra

$token = $config['token'];

if ($action === 'delete') {
    $url = "https://api.telegram.org/bot$token/deleteWebhook";
    $msg = "Webhook eliminado.";
} else {
    $url = "https://api.telegram.org/bot$token/setWebhook?url=$botUrl";
    $msg = "Webhook registrado: $botUrl";
}

$result = file_get_contents($url);
echo "$msg\n";
echo "Respuesta Telegram: $result\n";
?>
