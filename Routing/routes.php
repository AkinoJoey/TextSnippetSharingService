<?php
use Helpers\DatabaseHelper;
use Response\HTTPRenderer;
use Response\Render\HTMLRenderer;

return [
    '' => function() : HTMLRenderer {
        return new HTMLRenderer('component/top');
    },
    'snippet' => function(string $hash) :HTMLRenderer {
        $data = DatabaseHelper::getSnippetData($hash);
        $snippet = $data['snippet'];
        $language = $data['language'];
        
        return new HTMLRenderer('component/snippet', ['snippet' => $snippet, 'language' => $language]);
    }
];
