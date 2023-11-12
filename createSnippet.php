<?php

$language = isset($_POST["language"]) ? $_POST["language"] : "";
$expiration = isset($_POST["expiration"]) ? $_POST["expiration"] : "";
$snippet = isset($_POST["snippet"]) ? $_POST["snippet"] : "";
$hashedValue = hash('sha256', $snippet);

echo "Language: " . $language . PHP_EOL;
echo "Expiration: " . $expiration . PHP_EOL;
echo "Text:" . $snippet. PHP_EOL;
echo "Hashed:" . $hashedValue;

