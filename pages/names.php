<ul>
<?php
	function read_lines($url) 
	{
		$content = file_get_contents($url);
		return explode("\n", $content);
	}

	$horse = "https://dl.dropboxusercontent.com/u/764206/names.txt";
	$fear = "https://dl.dropboxusercontent.com/u/5124198/names.txt";

	$names = array_merge(
		read_lines($horse),
		read_lines($fear)
	);
	sort($names);

    foreach ($names as $name) {
        if (strlen(trim($name)) > 0) {
            echo "  <li>".htmlspecialchars(trim($name))."\n";
        }
    }
?>
</ul>

<a href="<?=$horse?>" target="_blank">Direct link (horsedrowner)</a>
<a href="<?=$fear?>" target="_blank">Direct link (Lord Fear)</a>