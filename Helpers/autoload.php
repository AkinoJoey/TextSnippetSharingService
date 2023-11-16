<?php

function customAutoload($className)
{
    $filePath = str_replace('\\', '/', $className) . '.php';

    if (file_exists($filePath)) {
        require $filePath;
    }
}

spl_autoload_register('customAutoload');
