<?php
use Helpers\DatabaseHelper;
use Response\HTTPRenderer;
use Response\Render\HTMLRenderer;

return [
    '' => function() : HTMLRenderer {
        return new HTMLRenderer('component/top');
    },
];
