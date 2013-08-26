<ul>
<?php
	$namesUrl = "http://dl.dropbox.com/u/764206/names.txt";
    $content = htmlspecialchars(file_get_contents($namesUrl));
    $names = explode("\n", $content);
    foreach ($names as $name) {
        if (strlen($name) > 0) {
            echo "  <li>".trim($name)."\n";
        }
    }
?>
</ul>

<a href="<?=$namesUrl?>" target="_blank">Direct link</a>