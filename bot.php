<?php
$config = require __DIR__ . '/config/config.php';

// Log a la terminal/railway
function botLog($message) {
    $dt = new DateTime("now", new DateTimeZone("Europe/Madrid"));
    $logLine = "[" . $dt->format("Y-m-d H:i:s") . "] $message";
    error_log($logLine);
}

// Log de inicio
botLog("Bot iniciado");

// Contar y loggear comandos cargados (solo para comandos)
function countCommands($dir) {
    $count = 0;
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $fullPath = $dir . DIRECTORY_SEPARATOR . $file;
        if (is_dir($fullPath)) {
            $count += countCommands($fullPath);
        } else {
            $count++;
        }
    }
    return $count;
}
$comandosCargados = countCommands($config['commands_path']);
botLog("Comandos cargados: $comandosCargados");

// Función para buscar el archivo de comando recursivamente (solo para /commands/)
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

// Prefijos permitidos para comandos
$allowedPrefixes = ['.', '/', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '_', '-', '+', '=', '{', '}', '"', "'", '<', '>', '?', '\\'];

$input = file_get_contents('php://input');
$update = json_decode($input, true);

// MANEJO DE MENSAJES NORMALES (comandos tipo /start, .id, etc)
if (isset($update['message'])) {
    $message = $update['message'];
    $chatId = $message['chat']['id'];
    $text = trim($message['text'] ?? '');

    // Revisa si inicia con algún prefijo permitido
    $firstChar = mb_substr($text, 0, 1);
    if (in_array($firstChar, $allowedPrefixes)) {
        // Quita el prefijo y espacios, toma solo el comando, ignora argumentos
        $cmd = explode(' ', substr($text, 1))[0];
        $cmd = str_replace('_', '', $cmd); // Ej: user_id busca "userid"
        $cmdFile = findCommandFile($cmd, $config['commands_path']);

        if ($cmdFile && file_exists($cmdFile)) {
            $username = $message['from']['username'] ?? 'sin_usuario';
            $userId = $message['from']['id'];
            botLog("Comando recibido: $text | Usuario: @$username | ID: $userId");

            $handler = require $cmdFile;
            if (is_callable($handler)) {
                $handler($chatId, $message, $config);
            }
        }
        // Si el comando no existe, no responde nada (silencioso)
    }
}

// MANEJO DE CALLBACK QUERY (BOTONES INLINE)
if (isset($update['callback_query'])) {
    $cbQuery = $update['callback_query'];
    $chatId = $cbQuery['message']['chat']['id'];
    $data = $cbQuery['data'];

    // Busca el handler en la carpeta raíz /handlers/
    $handlerFile = __DIR__ . "/handlers/$data.php";
    if (file_exists($handlerFile)) {
        $username = $cbQuery['from']['username'] ?? 'sin_usuario';
        $userId = $cbQuery['from']['id'];
        botLog("Callback: $data | Usuario: @$username | ID: $userId");
        $handler = require $handlerFile;
        if (is_callable($handler)) {
            $handler($chatId, $cbQuery['message'], $config, $cbQuery);
        }
    }
    // Si no existe handler, no hace nada.
}

// Función para enviar mensajes
function sendMessage($chatId, $text, $config, $parseMode = null, $replyMarkup = null, $replyTo = null) {
    $token = $config['token'];
    $url = "https://api.telegram.org/bot$token/sendMessage";
    $data = [
        "chat_id" => $chatId,
        "text" => $text
    ];
    if ($parseMode) {
        $data['parse_mode'] = $parseMode;
    }
    if ($replyMarkup) {
        $data['reply_markup'] = json_encode($replyMarkup);
    }
    if ($replyTo) {
        $data['reply_to_message_id'] = $replyTo;
    }
    file_get_contents($url . "?" . http_build_query($data));
}

// Función para editar mensajes con inline keyboard
function editMessage($chatId, $messageId, $text, $config, $parseMode = null, $replyMarkup = null) {
    $token = $config['token'];
    $url = "https://api.telegram.org/bot$token/editMessageText";
    $data = [
        "chat_id" => $chatId,
        "message_id" => $messageId,
        "text" => $text
    ];
    if ($parseMode) {
        $data['parse_mode'] = $parseMode;
    }
    if ($replyMarkup) {
        $data['reply_markup'] = json_encode($replyMarkup);
    }
    file_get_contents($url . "?" . http_build_query($data));
}

// Función para responder al callback de los botones inline
function answerCallback($callbackId) {
    $token = $GLOBALS['config']['token'];
    $url = "https://api.telegram.org/bot$token/answerCallbackQuery";
    $data = [
        "callback_query_id" => $callbackId
    ];
    file_get_contents($url . "?" . http_build_query($data));
}
?>
