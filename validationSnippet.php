<?php

$snippet = file_get_contents('php://input');

$db_charset = 'utf8mb4'; // MySQLのcharsetに合わせる
$text_max_length = 32767; // 全角だと65535, 半角だと32767

if (mb_strlen($snippet, $db_charset) >= $text_max_length || !mb_check_encoding($snippet, $db_charset)) {
    http_response_code(400);
    echo "Invalid text: Either the text exceeds the maximum allowed length or it contains invalid characters.";
    throw new Exception("Invalid text: Either the text exceeds the maximum allowed length or it contains invalid characters.");
}
