<?php
$CookieApocalypse = strtotime("+1 month");
$CookieDelete = time() - 3600;

if (isset($_POST["dark"])) { 
    setcookie("dark", $_POST["dark"], $CookieApocalypse);
}
else {
    setcookie("dark", $_POST["dark"], $CookieDelete);
}

if (isset($_POST["caps"])) {
	setcookie("caps", $_POST["caps"], $CookieApocalypse);
}
else {
    setcookie("caps", $_POST["caps"], $CookieDelete);
}

header("Location: index.php?p=preferences");
exit();

?>