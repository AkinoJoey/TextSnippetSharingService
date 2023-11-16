<?php
require_once './Helpers/autoload.php';

use Helpers\DatabaseHelper;
use Helpers\ValidationHelper;

$json = file_get_contents('php://input');
$data = json_decode($json,true);

$snippet = $data['snippet'];
$language = $data['language'];
$expiration = $data['expiration'];

// バリデーション
$isValidSnippet = ValidationHelper::snippet($snippet);
$isValidExpiration = ValidationHelper::expiration($expiration);

// バリデーションに失敗したらアラートを出す
checkIsValid($isValidSnippet);
checkIsValid($isValidExpiration);

$hashedValue = hash('sha256', uniqid(mt_rand(), true));

// データベースにデータを挿入
$insertResult = DatabaseHelper::insertData($snippet, $language, $expiration, $hashedValue);
$setEventResult = DatabaseHelper::setExpirationEvents($hashedValue, $expiration);

if ($insertResult && $setEventResult) {
    echo json_encode(["success" => true, "url" => "snippet/{$hashedValue}"]);
} else {
    echo json_encode(["success" => false, "message" => "Database operation failed"]);
}


function checkIsValid(array $value){
    if (!$value['success']) {
        echo json_encode($value);
        exit;
    }
}