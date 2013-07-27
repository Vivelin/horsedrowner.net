<?php
$request = parse_url($_SERVER["REQUEST_URI"]);
$path = substr($request["path"], 1) ?: "index.php";

if (!file_exists($path)) {
	$_GET["p"] = $path;
	include "index.php";
}
else {
	return false;
}