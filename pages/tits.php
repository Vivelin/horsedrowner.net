<?php
require_once "markdown.php";

$src = "https://raw.github.com/horsedrowner/TITS/development/README.md";

$text = file_get_contents($src);
echo Markdown($text);