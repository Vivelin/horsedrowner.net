<?php
    spl_autoload_register(function ($class) {
        include 'php/' . $class . '.class.php';
    });

    require_once "config.php";
    require_once "markdown.php";

    /* Configuration */
    date_default_timezone_set('Europe/Amsterdam');
    $ReqPageID = "about"; // Default page if nothing is requested
    $avatar = "curly/dai/100px.jpg";
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
<meta name="msapplication-tooltip" content="horsedrowner.net">
<meta name="msapplication-starturl" content="./">
<meta name="msapplication-navbutton-color" content="#254A88">
<meta name="msapplication-TileImage" content="curly-144px.png">
<meta name="msapplication-TileColor" content="#254A88">
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:400,600,300,700,800">
<link rel="stylesheet" type="text/css" href="webkitsucks.css">
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
<script src="includes/jquery-1.9.1.min.js"></script>
<script src="includes/konami.js"></script>
<title>horsedrowner.net</title>

<p id="nowplaying" class="alert" <?php if ($nowPlaying == null) echo "style=\"display: none;\""; ?>>
    <a href="<?php echo $nowPlayingLink ?>" target="_blank"><img src="lastfm.png" alt="Last.fm" title="Now playing on Last.fm"> <span><?php Pretty::Write($nowPlaying); ?></span></a>
</p>

<div class="header">
    <a href="/"><img id="avatar" src="avatars/<?=$avatar?>" alt="" title="<?php Pretty::Write($avatar_title);?>" 
        class="avatar <?php Pretty::Write($stateClass);?>"></a>
    <span id="title" class="title <?php Pretty::Write($stateClass);?>"><?php Pretty::Write($stateMessage);?></span>
    <ul class="nav">
<?php         
//Add all available pages to the nav bar thingy
foreach ($Pages as $s) {
    if ($s === $ReqPageID) { $class = ' class="current"'; } else { $class = ''; }
    $s = htmlentities($s);
    $html = <<<NIGGER
        <li$class><a href="$s">$s</a>
    
NIGGER;
    print $html;
}
?>
    </ul>
</div>
    
<div class="main">
    <div class="content">
        <?php include "pages/$PageID.php";?>
    </div>
</div>

<div class="footer">
    <p><a href="//s.horsedrowner.net">Images</a>
       &bull; <a href="ip">IP</a>
       &bull; This page was last changed <?php Pretty::WriteDateTime($page_modified);?>
<?php if (defined("DEBUG")) { ?> 
    <pre>
<?php print_r($_SERVER); ?> 
    </pre>
<?php } ?> 
</div>
<script>
function updateStatus() {
    $.getJSON("status.php?id=horsedrowner", function(json) {
        if (typeof json.error == "undefined" && json.onlineState && json.stateMessage)
        {
            if (json.nowPlaying) {
                $("#nowplaying span").html(json.nowPlaying);
                $("#nowplaying a").attr("href", json.nowPlayingLink);
                $("#nowplaying").show();
            } else {
                $("#nowplaying").hide();
            }

            $("#title").html(json.stateMessage);
            switch (json.onlineState) {
                case "online":
                    $("#title").attr("class", "title online");
                    $("#avatar").attr("class", "avatar online");
                    break;
                case "in-game":
                    $("#title").attr("class", "title ingame");
                    $("#avatar").attr("class", "avatar ingame");
                    break;
                default:
                    $("#title").attr("class", "title offline");
                    $("#avatar").attr("class", "avatar offline");
                    break;
            }
        }
    });
    setTimeout(updateStatus, 15000); //Update again in 15 seconds
}

updateStatus();

if (typeof Konami != 'undefined') {
    var konami = new Konami("/?p=safetydance");
}
</script>