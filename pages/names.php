<ul>
<?php
    $content = htmlspecialchars(file_get_contents("http://dl.dropbox.com/u/764206/names.txt"));
    $names = explode("\n", $content);
    foreach ($names as $name) {
        if (strlen($name) > 0) {
            echo "  <li>".trim($name)."\n";
        }
    }
?>
</ul>
