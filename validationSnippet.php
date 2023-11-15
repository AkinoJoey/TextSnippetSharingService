<?php

$json = file_get_contents('php://input');
$snippet = json_decode($json, true)['snippet'];

if ((bool)preg_match('/^\s*$/', $snippet)){
    echo json_encode(['success' => false, 'message' => '無効なテキスト: 空白のみのスニペットは作成できません。']);
    exit;
}

if(!mb_check_encoding($snippet, 'UTF-8') && !(bool)preg_match('//u', $snippet)){
    echo json_encode(['success' => false, 'message' => '無効なテキスト: エンコーディングが無効またはUnicode文字以外が含まれています。']);
    exit;
}

$snippet_size = strlen($snippet);
$text_max_length = 65535; // text型の最大バイト数

if ($snippet_size > $text_max_length ) {
    echo json_encode(['success' => false, 'message' => '無効なテキスト: 入力できる最大文字数を超えています。']);
    exit;
}

echo json_encode(['success' => true]);