<?php
require_once "php/default.php";
require_once "config.php";

//Handles uncaught exceptions by returning them as JSON
function steam_json_exception_handler($exception) {
    echo <<<JSON
{ "error": "$exception" }

JSON;
}

try {
    set_exception_handler("steam_json_exception_handler");

    if (isset($_GET["id"])) $id = $_GET["id"]; else $id = "76561197994245359";

    if (file_exists("status.txt")) {
        $diff = time() - filemtime("status.txt");
        if ($diff < 15) {
            $json = Status::getCached();
        }
    }

    if (!isset($diff) || $diff >= 15 || $json === false) {
        $status = new Status($steamApiKey, $id);
        $json = $status->getStatusJson();
    }

    restore_exception_handler();
        
    echo $json;
}
catch (Exception $ex) {
    echo <<<JSON
{ "error": "{$ex->getMessage()}" }

JSON;
}
?>