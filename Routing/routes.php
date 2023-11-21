<?php
use Helpers\DatabaseHelper;
use Helpers\ValidationHelper;
use Response\Render\JSONRenderer;
use Response\Render\HTMLRenderer;

return [
        '' => [
            'GET' => function (): HTMLRenderer {
            $expirations =  DatabaseHelper::getExpirations();
            return new HTMLRenderer('component/top', ['expirations' => $expirations]);
            },
            'POST' => function (): HTMLRenderer | JSONRenderer {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            $snippet = $data['snippet'];
            $language = $data['language'];
            $expiration = $data['expiration'];

            // バリデーション
            $isValidSnippet = ValidationHelper::snippet($snippet);
            $isValidExpiration = ValidationHelper::expiration($expiration);

            // バリデーション結果のチェックとエラーレスポンスの生成
            $checkIsValid = function (array $value): ?JSONRenderer {
                if (!$value['success']) {
                    return new JSONRenderer($value);
                }
                return null;
            };

            // バリデーションに失敗したらエラーレスポンスを返す
            if ($errorResponse = $checkIsValid($isValidSnippet)) {
                return $errorResponse;
            }

            if ($errorResponse = $checkIsValid($isValidExpiration)) {
                return $errorResponse;
            }

            $hashedValue = hash('sha256', uniqid(mt_rand(), true));

            // データベースにデータを挿入
            $insertResult = DatabaseHelper::insertData($snippet, $language, $expiration, $hashedValue);
            $setEventResult = DatabaseHelper::setExpirationEvents($hashedValue, $expiration);

            if ($insertResult && $setEventResult) {
                return new JSONRenderer(["success" => true, "url" => "snippet/{$hashedValue}"]);
            } else {
                return new JSONRenderer(["success" => false, "message" => "Database operation failed"]);
            }
        }
    ],
    'snippet' => [
            'GET' => function (): HTMLRenderer {
            $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $paths = explode('/', $url);

            // hash部分がない場合は404を出す
            if (count($paths) < 3) {
                http_response_code(404);
                return new HTMLRenderer('component/404', ['errormsg' => 'Page not found']);
            }

            $hash = $paths[2];
            $data = DatabaseHelper::getSnippetData($hash);

            if (!$data) {
                http_response_code(404);
                return new HTMLRenderer('component/404', ['errormsg' => "Expired Snippet"]);
            }
            $snippet = $data['snippet'];
            $language = $data['language'];

            return new HTMLRenderer('component/snippet', ['snippet' => $snippet, 'language' => $language]);
        }
        ]
];
