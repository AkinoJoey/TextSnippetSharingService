<?php

namespace Helpers;

use Database\MySQLWrapper;
use Exception;

class DatabaseHelper{
    public static function getSnippetData(string $hash) : array | false {
        $db = new MySQLWrapper();
        $stmt = $db->prepare("SELECT snippet, language FROM snippets WHERE url = ?");
        $stmt->bind_param('s', $hash);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        if(!$data) return false;
        
        return $data;
    }

    public static function getExpirations() : array {
        $expirations = [
            [
                "text" => "Never",
                "value" => "Never"
            ],
            [
                "text" => "10 Minutes",
                "value" => "10 MINUTE"
            ],
            [
                "text" => "1 Hour",
                "value" => "1 HOUR"
            ],
            [
                "text" => "1 Day",
                "value" => "1 DAY"
            ],
            [
                "text" => "1 Week",
                "value" => "1 WEEK"
            ],
            [
                "text" => "2 Weeks",
                "value" => "2 WEEK"
            ],
            [
                "text" => "1 Month",
                "value" => "1 MONTH"
            ],
            [
                "text" => "6 Month",
                "value" => "6 MONTH"
            ],
            [
                "text" => "1 Year",
                "value" => "1 YEAR"
            ],

        ];

        return $expirations;
    }

    public static function insertData(string $snippet, string $language, string $expiration, string $hashedValue) : bool {
        $db = new MySQLWrapper();
        $stmt = $db->prepare("INSERT INTO snippets(snippet, url, language, expiration) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssss', $snippet, $hashedValue, $language, $expiration);
        $result = $stmt->execute();

        if (!$result) throw new Exception("Error executing INSERT query: " . $stmt->error);

        return true;
    }

    public static function setExpirationEvents(string $hashedValue, string $expiration) : bool {
        $currentTimestamp = time();
        $uniqueId = uniqid();
        $uniqueEventName = 'delete_data_' . $currentTimestamp . '_' . $uniqueId;

        $db = new MySQLWrapper();

        $expiration =  $db->real_escape_string($expiration);

        if ($expiration == 'Never') return true;

        $result = $db->query("
            CREATE EVENT IF NOT EXISTS $uniqueEventName
            ON SCHEDULE AT CURRENT_TIMESTAMP + INTERVAL $expiration
            DO
                DELETE FROM snippets
                WHERE url = '$hashedValue';
        ");

        if (!$result) throw new Exception("Error creating event: " . $db->error);

        return true;
    }
}