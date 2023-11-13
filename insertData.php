<?php
spl_autoload('.php');
spl_autoload_register();

use Database\MySQLWrapper;

$snippet = isset($_POST["snippet"]) ? $_POST["snippet"] : "";
$hashedValue = hash('sha256', uniqid(mt_rand(), true));
$language = isset($_POST["language"]) ? $_POST["language"] : "";
$expiration = isset($_POST["expiration"]) ? $_POST["expiration"] : "";

$db = new MySQLWrapper();
$stmt = $db->prepare("INSERT INTO snippets(snippet, url, language, expiration) VALUES (?, ?, ?, ?)");
$stmt->bind_param('ssss', $snippet, $hashedValue, $language, $expiration);
$stmt->execute();


echo $hashedValue;