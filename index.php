<?php
    require_once "php/default.php";
    require_once "config.php";
    require_once "markdown.php";

    /* Configuration */
    date_default_timezone_set('Europe/Amsterdam');
    $ReqPageID = "about"; // Default page if nothing is requested
    $avatar = "curly/dai/512px.jpg";
    $avatar_title = "Curly Brace (Cave Story, drawn by daijitsu)";

    /* Load the page or 404 */
    if (isset($_GET["p"])) $ReqPageID = $_GET["p"];
    $PageID = $ReqPageID;

    if (!file_exists("pages/$PageID.php")) {
        header('HTTP/1.0 404 Not Found');
        if (file_exists("pages/404.php")) {
            $PageID = "404";
        } else {
            readfile('404.htm');
            exit();
        }
    }
    
    /* Determine content modification date/time */
    $page_modified = time();
    $page_modified = filemtime("pages/$PageID.php");
    $index_modified = filemtime(__FILE__);
    header("Last-Modified: ".gmdate("D, d M Y H:i:s", max($index_modified, $page_modified))." GMT");
    header("Content-type: text/html; charset=utf-8"); 
    header("Cache-Control: no-cache"); /* Can't cache with the status thing */
    
    $Pages = [ "about", "projects", "quotes" ];
    if (!in_array($ReqPageID, $Pages)) {
        $Pages[] = $ReqPageID;
    }

    /* Get the cached status before the client can load the actual one */
    $stateMessage = "horsedrowner";
    $stateClass = "";
    $nowPlaying = "";
    $nowPlayingLink = "";

    if (file_exists("status.txt")) {
        $json = @file_get_contents("status.txt");
        if ($json !== false) {
            $status = json_decode($json, true);
            $stateMessage = $status["stateMessage"];
            $nowPlaying = $status["nowPlaying"];
            $nowPlayingLink = $status["nowPlayingLink"];
            $stateClass = $status["onlineState"];
            if ($stateClass === "in-game") {
                $stateClass = "ingame";
            }
        }
    }
?>
<!DOCTYPE html>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
<meta name="author" content="Ruben Verdoes">
<meta name="description" content="Nothing to see here, please move along.">
<meta name="msapplication-tooltip" content="horsedrowner.net">
<meta name="msapplication-starturl" content="./">
<meta name="msapplication-navbutton-color" content="#254A88">
<meta name="msapplication-TileImage" content="curly-144px.png">
<meta name="msapplication-TileColor" content="#254A88">
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Exo+2:300,400,600,700" >
<link rel="stylesheet" type="text/css" href="webkitsucks.css">
<link rel="stylesheet" type="text/css" href="style.css">
<?php if (mt_rand(0,1000) == 0) { ?>
<link rel="stylesheet" type="text/css" href="/assets/aurebesh.css">
<?php } ?>
<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
<script src="includes/jquery-1.9.1.min.js"></script>
<script src="includes/konami.js"></script>
<title>horsedrowner.net</title>

<p id="nowplaying" class="alert" <?php if (true && $nowPlaying == null) echo "style=\"display: none;\""; ?>>
    <a href="<?php echo $nowPlayingLink ?>" target="_blank"><img class="lastfm-logo" src="lastfm.png" alt="Last.fm" title="Now playing on Last.fm"> <span><?php Pretty::Write($nowPlaying); ?></span> <img class="load-indicator" src="loading-red.gif"></a>
</p>

<div class="header">
    <div class="avatar">
        <a href="/">
            <img id="avatar" src="avatars/<?=$avatar?>" alt="" 
                title="<?php Pretty::Write($avatar_title);?>" 
                class="<?php Pretty::Write($stateClass);?>">
        </a>
    </div>
    <div class="titlebar">
        <span id="title" class="title <?php Pretty::Write($stateClass);?>">
            <?php Pretty::Write($stateMessage);?>
        </span> 
        <img id="title-load" class="load-indicator" src="loading-grey.gif">
        <ul class="nav">
    <?php         
    //Add all available pages to the nav bar thingy
    foreach ($Pages as $s) {
        if ($s === $ReqPageID) { $class = ' class="current"'; } else { $class = ''; }
        $s = htmlentities($s);
        $html = "<li$class><a href=\"$s\">$s</a>\n";
        print $html;
    }
    ?>
        </ul>
    </div>
</div>
    
<div class="content">
    <?php include "pages/$PageID.php";?>
</div>

<div class="footer">
    <p><a href="//s.horsedrowner.net">Images</a>
       &bull; <a href="ip">IP</a>
       &bull; This page was last changed <?php print Pretty::DateTime($page_modified);?> 
       &bull; Powered by <a href="http://steampowered.com/">Steam</a> and <a href="http://www.last.fm/">Last.fm</a>
<?php if (Debug::IsEnabled()) { ?> 
    <pre>
<?php print_r($_SERVER); ?> 
    </pre>
<?php } ?> 
</div>
<script>
function updateStatus() {
    $("#title-load").show();
    $.getJSON("status.php?mode=steam", function(json) {
        $("#title-load").hide();

        if (typeof json.error == "undefined" && json.onlineState && json.stateMessage)
        {
            $("#title").html(json.stateMessage);
            switch (json.onlineState) {
                case "online":
                    $("#title").attr("class", "title online");
                    $("#avatar").attr("class", "online");
                    break;
                case "ingame":
                    $("#title").attr("class", "title ingame");
                    $("#avatar").attr("class", "ingame");
                    break;
                default:
                    $("#title").attr("class", "title offline");
                    $("#avatar").attr("class", "offline");
                    break;
            }
        }
    });

    setTimeout(updateStatus, 15000);
}

function updateNowPlaying() {
    $("#nowplaying .load-indicator").show();
    $.getJSON("status.php?mode=lastfm", function(json) {
        $("#nowplaying .load-indicator").hide();

        if (typeof json.error == "undefined")
        {
            if (json.text) {
                $("#nowplaying span").html(json.text);
                $("#nowplaying a").attr("href", json.link);
                $("#nowplaying").show();
            } else {
                $("#nowplaying").hide();
            }
        }
    });

    setTimeout(updateNowPlaying, 15000);
}

updateNowPlaying();
updateStatus();

if (typeof Konami != 'undefined') {
    var konami = new Konami("/?p=safetydance");
}
</script>
