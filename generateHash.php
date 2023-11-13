<?php
$snippet = isset($_POST["snippet"]) ? $_POST["snippet"] : "";
$hashedValue = hash('sha256', $snippet);

echo $hashedValue;
