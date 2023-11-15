<?php
use Helpers\DatabaseHelper;
use Response\Render\HTMLRenderer;

return [
    '' => function() : HTMLRenderer {
        return new HTMLRenderer('component/top');
    },
    'snippet' => function(string $hash) :HTMLRenderer {
        $data = DatabaseHelper::getSnippetData($hash);

        if(!$data) return new HTMLRenderer('component/404');

        $snippet = $data['snippet'];
        $language = $data['language'];
        
        return new HTMLRenderer('component/snippet', ['snippet' => $snippet, 'language' => $language]);
    }
];
