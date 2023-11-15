<?php
spl_autoload_register(function ($className) {
    $filePath = str_replace('\\', '/', $className) . '.php';

    if (file_exists($filePath)) {
        require $filePath;
    }
});

$DEBUG = true;

$routes = include('Routing/routes.php');

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = ltrim($path, '/');

$isSnippet = str_starts_with($path, 'snippet/');

if($isSnippet){
    $hash = explode('/', $path)[1];
    $path = 'snippet';
};

if (isset($routes[$path])) {
    $renderer = $isSnippet? $routes[$path]($hash) : $routes[$path]();

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
