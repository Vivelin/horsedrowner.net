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
    if (isset($_GET["mode"])) $mode = $_GET["mode"]; else $mode = "steam";
    $mode = preg_replace('/[^A-Za-z0-9\-]/', '', $mode);

    $filename = "status_" . $mode . ".txt";
    if (file_exists($filename)) {
        $diff = time() - filemtime($filename);
        if ($diff < 15) {
            $json = Status::getCached($filename);
        }
    }

    if (!isset($diff) || $diff >= 15 || $json === false) {
        switch ($mode) {
            case "lastfm":
                $status = new Status($steamApiKey, $id, $lastfmAPI);
                $json = $status->getLastfmJson();
                break;
            
            default:
                $status = new Status($steamApiKey, $id, $lastfmAPI);
                $json = $status->getSteamJson();
                break;
        }
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