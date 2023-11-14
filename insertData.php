<?php
spl_autoload_extensions(".php");
spl_autoload_register();

use Database\MySQLWrapper;

$snippet = isset($_POST["snippet"]) ? $_POST["snippet"] : "";
$hashedValue = hash('sha256', uniqid(mt_rand(), true));
$language = isset($_POST["language"]) ? $_POST["language"] : "";
$expiration = isset($_POST["expiration"]) ? $_POST["expiration"] : "";

$db = new MySQLWrapper();
$stmt = $db->prepare("INSERT INTO snippets(snippet, url, language, expiration) VALUES (?, ?, ?, ?)");
$stmt->bind_param('ssss', $snippet, $hashedValue, $language, $expiration);
$result = $stmt->execute();

if(!$result) throw new Exception("Error executing INSERT query: " . $stmt->error);

echo $hashedValue;