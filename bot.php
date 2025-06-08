<?php
$config = require __DIR__ . '/config/config.php';

function findCommandFile($command, $dir) {
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $fullPath = $dir . DIRECTORY_SEPARATOR . $file;
        if (is_dir($fullPath)) {
            $found = findCommandFile($command, $fullPath);
            if ($found) return $found;
        } else if (strtolower(pathinfo($file, PATHINFO_FILENAME)) === strtolower($command)) {
            return $fullPath;
        }
    }
    return null;
}

$allowedPrefixes = ['.', '/', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '_', '-', '+', '=', '{', '}', '"', "'", '<', '>', '?', '\\'];

$input = file_get_contents('php://input');
$update = json_decode($input, true);

if (isset($update['message'])) {
    $message = $update['message'];
    $chatId = $message['chat']['id'];
    $text = trim($message['text'] ?? '');

    // Revisa si inicia con algún prefijo permitido
    $firstChar = mb_substr($text, 0, 1);
    if (in_array($firstChar, $allowedPrefixes)) {
        // Quita el prefijo y espacios, toma solo el comando, ignora argumentos
        $cmd = explode(' ', substr($text, 1))[0];
        $cmd = str_replace('_', '', $cmd); // ejemplo: user_id -> userid
        $cmdFile = findCommandFile($cmd, $config['commands_path']);

        if ($cmdFile && file_exists($cmdFile)) {
            $handler = require $cmdFile;
            if (is_callable($handler)) {
                $handler($chatId, $message, $config);
            }
        }
        // Si no existe, no responde nada
    }
}

function sendMessage($chatId, $text, $config, $parseMode = null) {
    $token = $config['token'];
    $url = "https://api.telegram.org/bot$token/sendMessage";
    $data = [
        "chat_id" => $chatId,
        "text" => $text
    ];
    if ($parseMode) {
        $data['parse_mode'] = $parseMode;
    }
    file_get_contents($url . "?" . http_build_query($data));
}
?>
