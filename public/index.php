<?php
require '../autoload.php';
$DEBUG = true;

$routes = include('../Routing/routes.php');
$url = parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);
$paths = explode('/', $url);
$path = $paths[1];
$method = $_SERVER['REQUEST_METHOD'];

if (isset($routes[$method][$path])) {
    if($method === 'GET'){
        $renderer = $routes[$method][$path]($url);
        
    }elseif ($method === 'POST') {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $renderer = $routes[$method][$path]($data);
    }

    try {
        foreach ($renderer->getFields() as $name => $value) {
            $sanitized_value = filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);

            if ($sanitized_value && $sanitized_value === $value) {
                header("{$name}: {$sanitized_value}");
            } else {
                http_response_code(500);
                if ($DEBUG) print("Failed setting header - original: '$value', sanitized: '$sanitized_value'");
                exit;
            }

            print($renderer->getContent());
        }
    } catch (Exception $e) {
        http_response_code(500);
        print("Internal error, please contact the admin.<br>");
        if ($DEBUG) print($e->getMessage());
    }
} else {
    http_response_code(404);
    echo "404 Not Found: The requested route was not found on this server.";
}