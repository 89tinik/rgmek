<?php
// get_token.php — получение токена от Энергосферы
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json; charset=utf-8');

// === НАСТРОЙКИ ===
$authorizeUrl     = 'https://lkes.r-energiya.ru/Account/Authorize';
$externalSystemId = '3BFE2123-5FEE-4EBD-B9BE-7367B7499FEE';
$logFile          = __DIR__ . '/get_token.log';

// === Читаем счётчики из файла ===
$metersFile = __DIR__ . '/meters.php';
if (!file_exists($metersFile)) {
    echo json_encode([
        'ok'    => false,
        'error' => 'meters_file_not_found',
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$meters = include $metersFile;
if (!is_array($meters) || empty($meters)) {
    echo json_encode([
        'ok'    => false,
        'error' => 'meters_invalid_or_empty',
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// === XML для авторизации ===
$xml  = '<?xml version="1.0" encoding="utf-8"?>';
$xml .= '<UserData>';
$xml .= '<ExternalSystemId>' . htmlspecialchars($externalSystemId, ENT_XML1 | ENT_QUOTES, 'UTF-8') . '</ExternalSystemId>';
$xml .= '<Meters>';

foreach ($meters as $meter) {
    // поддержим варианты ключей Type/type, Serial/serial
    $type   = $meter['Type']   ?? $meter['type']   ?? '';
    $serial = $meter['Serial'] ?? $meter['serial'] ?? '';

    $type   = trim((string)$type);
    $serial = trim((string)$serial);

    if ($type === '' || $serial === '') {
        continue; // пропускаем некорректные записи
    }

    $xml .= '<Meter>';
    $xml .= '<Type>'   . htmlspecialchars($type,   ENT_XML1 | ENT_QUOTES, 'UTF-8') . '</Type>';
    $xml .= '<Serial>' . htmlspecialchars($serial, ENT_XML1 | ENT_QUOTES, 'UTF-8') . '</Serial>';
    $xml .= '</Meter>';
}

$xml .= '</Meters>';
$xml .= '</UserData>';

// === Отправляем POST-запрос на /Account/Authorize ===
$ch = curl_init($authorizeUrl);
curl_setopt_array($ch, [
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $xml,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER     => ['Content-Type: application/xml; charset=utf-8'],
    CURLOPT_TIMEOUT        => 15,
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_SSL_VERIFYHOST => 2,
    CURLOPT_USERAGENT      => 'RGMEK-Bridge/1.0 (+php-curl)',
]);
$body  = curl_exec($ch);
$err   = curl_error($ch);
$code  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$ctype = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
curl_close($ch);

// === Логирование ===
file_put_contents(
    $logFile,
    sprintf(
        "[%s] HTTP:%s Content-Type:%s\nSENT XML:\n%s\n\nRESPONSE/ERROR (first 400 chars):\n%s\n\n",
        date('Y-m-d H:i:s'),
        $code,
        $ctype,
        $xml,
        mb_substr($body ?: $err, 0, 5000, 'UTF-8')
    ),
    FILE_APPEND
);

// === Проверка ошибок ===
if ($err || !$body) {
    echo json_encode([
        'ok'    => false,
        'error' => 'curl_failed',
        'http'  => $code,
        'detail'=> $err ?: 'empty_response'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// === Извлекаем токен из XML-ответа ===
if (!preg_match('~<Token>([^<]+)</Token>~i', $body, $m)) {
    echo json_encode([
        'ok'     => false,
        'error'  => 'no_token_in_response',
        'http'   => $code,
        'sample' => mb_substr($body, 0, 300, 'UTF-8'),
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$token = trim($m[1]);

// === Успех ===
echo json_encode([
    'ok'    => true,
    'token' => $token,
], JSON_UNESCAPED_UNICODE);
