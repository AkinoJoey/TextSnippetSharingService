<?php
spl_autoload_extensions(".php");
spl_autoload_register();

use Database\MySQLWrapper;

$json = file_get_contents('php://input');
$data = json_decode($json, true);
$snippet = $data["snippet"];
$language = $data["language"];
$expiration = $data["expiration"];

$hashedValue = hash('sha256', uniqid(mt_rand(), true));

$db = new MySQLWrapper();
$stmt = $db->prepare("INSERT INTO snippets(snippet, url, language, expiration) VALUES (?, ?, ?, ?)");
$stmt->bind_param('ssss', $snippet, $hashedValue, $language, $expiration);
$result = $stmt->execute();

if(!$result) throw new Exception("Error executing INSERT query: " . $stmt->error);

echo $hashedValue;