<?php

namespace Helpers;

class ValidationHelper
{

    public static function snippet(string $snippet): array{
        if ((bool)preg_match('/^\s*$/', $snippet)) {
            return ['success' => false, 'message' => '無効なテキスト: 空白のみのスニペットは作成できません。'];
        }

        if (!mb_check_encoding($snippet, 'UTF-8') && !(bool)preg_match('//u', $snippet)) {
            return ['success' => false, 'message' => '無効なテキスト: エンコーディングが無効またはUnicode文字以外が含まれています。'];
        }

        $snippet_size = strlen($snippet);
        $text_max_length = 65535; // text型の最大バイト数

        if ($snippet_size > $text_max_length) {
            return ['success' => false, 'message' => '無効なテキスト: 入力できる最大文字数を超えています。'];
        }

        return ['success' => true];
    }

    public static function expiration(string $expiration) : array {
        $expirations = DatabaseHelper::getExpirations();

        $expirationValues = array_column($expirations, 'value');

        if(!in_array($expiration, $expirationValues)){
            return ['success' => false, 'message' => '無効な値です。'];
        }

        return ['success' => true];
        
    }
}
