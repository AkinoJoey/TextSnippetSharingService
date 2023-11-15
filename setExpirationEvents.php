<?php
spl_autoload_register(function ($className) {
    $filePath = str_replace('\\', '/', $className) . '.php';

    if (file_exists($filePath)) {
        require $filePath;
    }
});

use Database\MySQLWrapper;

$json = file_get_contents('php://input');
$data = json_decode($json, true);

$hashedValue = $data['hashedValue'];
$expiration = $data['expiration'];

if ($expiration == 'Never') return;

$currentTimestamp = time();
$uniqueId = uniqid();
$uniqueEventName = 'delete_data_' . $currentTimestamp . '_' . $uniqueId;

$db = new MySQLWrapper();
$result = $db->query("
    CREATE EVENT IF NOT EXISTS $uniqueEventName
    ON SCHEDULE AT CURRENT_TIMESTAMP + INTERVAL $expiration
    DO
        DELETE FROM snippets
        WHERE url = '$hashedValue';
");

if (!$result) throw new Exception("Error creating event: " . $db->error);
