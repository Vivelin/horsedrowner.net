<?php
$videos = array("zwZISypgA9M", "QZYbRe9taiw", "o1b64ADcpDY", "4pTwQKMrTt0");

$v = $videos[array_rand($videos)];
echo Pretty::YouTube($v, 960, 540, false, true, true, true);