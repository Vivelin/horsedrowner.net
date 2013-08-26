<?php
	function read_lines($url) 
	{
		$content = file_get_contents($url);

		// FUCK. ME.
		$content = mb_convert_encoding($content, 'UTF-8', 
			mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true));

		return explode("\n", $content);
	}

	$horse = "https://dl.dropboxusercontent.com/u/764206/names.txt";
	$fear = "https://dl.dropboxusercontent.com/u/5124198/names.txt";

	$names = array_merge(
		read_lines($horse),
		read_lines($fear)
	);
	$names = array_map("trim", $names);
	$names = array_filter($names);
	sort($names);

	$randomName = $names[array_rand($names)];
?>

<p>Random name: <strong><?=htmlspecialchars($randomName)?></strong></p>

<ul>
<?php
    foreach ($names as $name) {
        echo "\t<li>" . htmlspecialchars($name) . "\n";
    }
?>
</ul>

<p>
	<a href="<?=$horse?>" target="_blank">Direct link (horsedrowner)</a><br>
	<a href="<?=$fear?>" target="_blank">Direct link (Lord Fear)</a>
</p>