<?php

namespace Helpers;

use Database\MySQLWrapper;
use Exception;

class DatabaseHelper{
    public static function getSnippetData(string $hash) : array {
        $db = new MySQLWrapper();
        $stmt = $db->prepare("SELECT snippet, language FROM snippets WHERE url = ?");
        $stmt->bind_param('s', $hash);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        if(!$data) {
            http_response_code(404);
            echo "Snippet Expired";
            throw new Exception("Could not find a snippet in database");
        }
        
        return $data;
    }
}