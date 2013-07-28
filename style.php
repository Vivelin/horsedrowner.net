<?php 
function getCookie($cookie) {
    $value = false;
    if (isset($_COOKIE[$cookie]))
        $value = $_COOKIE[$cookie];
    return ($value == "1");
}

/* Designed with simplicity in mind, rather than coverage. */
function isMobile() {
    $ua = strtolower($_SERVER["HTTP_USER_AGENT"]);
    return (strpos($ua, "mobile") !== FALSE || strpos($ua, "android") !== FALSE);
}

header('X-Powered-By: Go away.');
header("Content-type: text/css"); 
//header("Last-Modified: ".gmdate("D, d M Y H:i:s", filemtime(__FILE__))); //Caching the page means customization breaks until you Ctrl+F5
require_once 'reset.css';

$BackgroundColor = "rgb(100%, 100%, 100%)";
$ForegroundColor = "rgb(0%, 0%, 0%)";
$SubtleColor = "rgba(0%, 0%, 0%, 0.5)";
$SubtlerColor = "rgba(0%, 0%, 0%, 0.05)";

$hue = mt_rand(0, 359);
try {
	$ip = @explode('.', $_SERVER['REMOTE_ADDR']);
	$hue = array_sum($ip) % 360;
}
catch (Exception $e) {
	// We already have a fallback default
}
$AccentColor = "hsl(" . $hue . ", 60%, 50%)";


$Capitalization = (getCookie("caps") ? "capitalize" : "lowercase");

$TextFontFamily = "\"Segoe UI\", \"Segoe WP\", \"Open Sans\", sans-serif";
$LightFontFamily = "\"Segoe UI Light\", \"Segoe UI\", \"Segoe WP\" ,\"Open Sans\", sans-serif";
$BoldFontFamily = "\"Segoe WP Black\", \"Segoe WP\", \"Segoe UI\", \"Open Sans\", sans-serif";
$CodeFontFamily = "Consolas, \"Ubuntu Mono\", monospace";

if (getCookie("dark") xor isMobile()) {
    $BackgroundColor = "rgba(0%, 0%, 0%, 1)";
    $ForegroundColor = "rgba(100%, 100%, 100%, 1)"; 
    $SubtleColor = "rgba(100%, 100%, 100%, 0.5)"; 
    $SubtlerColor = "rgba(100%, 100%, 100%, 0.1)"; 
}

/* Funky mode */
if ((mt_rand() / mt_getrandmax()) < 0.001) {
	$OldAccentColor = $AccentColor;
	$AccentColor = $BackgroundColor;
	$BackgroundColor = $OldAccentColor;
}
?>

@media (min-width: 960px) {
    #main {
	   max-width: 960px;
    }
}

@media (max-width: 480px) {
    #header { 
        height: auto !important;
    }

    #content {
    	margin-top: 2em;
    }

    #avatar { 
        display: none; 
    }

    #nav li { 
        font-size: 1.5em !important; 
    }
}

::selection {
	background-color: #1e90ff;
	color: <?=$BackgroundColor?>;
}

::-moz-selection {
	background-color: #1e90ff;
	color: <?=$BackgroundColor?>;
}

/* 
 * Base elements and typography
 */
html {
	font-size: 100%;
}

body {
	background: <?=$BackgroundColor?>;
	color: <?=$ForegroundColor?>;
	font-family: <?=$TextFontFamily?>;
	line-height: 1.4;
}

h1 {
	color: #1e90ff;
	font: 100 2.5em <?=$LightFontFamily?>;
	margin: 1em 0 .5em 0;
}

h2 {
	color: #1e90ff;
	font: 100 1.8em <?=$LightFontFamily?>;
	margin: 1em 0 .5em 0;
}

h3 {
	color: #1e90ff;
	font: 900 1.2em <?=$BoldFontFamily?>;
	margin: 1em 0 .5em 0;
	text-transform: uppercase;
}

h4 {
	font: 600 1em <?=$TextFontFamily?>;
}

p {
	margin: 1em 0;
}

pre, code, kbd {
	font-family: <?=$CodeFontFamily?>;
}

pre {
    background-color: <?=$SubtlerColor?>;
	border-left: .5em solid #1e90ff;
	padding: 0.5em 0.5em 0.5em 1.5em;
	margin-bottom: 1em;
}

a, a:link, a:visited {
	color: #1e90ff; /* "dodger blue" */
	cursor: pointer;
	text-decoration: none;
}

a:hover, a:active {
	color: #1e90ff;
	text-decoration: underline;
}

small {
	color: <?=$SubtleColor?>;
	font-size: 0.6em;
}

strong {
	font-weight: 700;
}

em {
	color: <?=$SubtleColor?>;
}

blockquote {
    background-color: <?=$SubtlerColor?>;
	border-left: .5em solid #1e90ff;
	padding: 0.5em 0.5em 0.5em 1.5em;
	margin-bottom: 1em;
}

blockquote p {
	font-size: 1.2em;
	margin: 0;
}

blockquote p:before {
	content: "\201C";
}

blockquote p:after {
	content: "\201D";
}

blockquote em,
blockquote i {
	color: <?=$ForegroundColor?>;
	font-style: italic;
}

blockquote small {
	font-size: 0.8em;
}

blockquote small:before {
	content: '\2014 \00A0';
}

q {
	quotes: '\201C' '\201D 	' '\2018' '\2019';
}

q:before {
	content: '\201C';
	content: open-quote;
}

q:after {
	content: '\201D 	';
	content: close-quote;
}

q q:before {
	content: '\2018';
	content: open-quote;
}

q q:after {
	content: '\2019';
	content: close-quote;
}

/* 
 * Custom elements
 */
.nickname {
	color: <?=$SubtleColor?>;
}

a.button, a.button:visited {
	border: 2px solid <?=$ForegroundColor?>;
	color: <?=$ForegroundColor?>;
	display: inline-block;
	margin-bottom: 0.3em;
	margin-right: .3em;
	padding: 0 .5em;
	font-weight: 600;
	text-decoration: none;
}

a.button:hover {
	background-color: <?=$SubtlerColor?>;
}

a.button:active {
	background-color: <?=$ForegroundColor?>;
	color: <?=$BackgroundColor?>;
}

a.button:not([href]) {
	background: none;
	border: 2px solid <?=$SubtleColor?>;
	color: <?=$SubtleColor?>;
	cursor: default;
}

.gist {
	font-size: .8em;
}

/*.nowplaying {
	padding-left: 30px;
	background-image: url('lastfm.png');
	background-position: center left;
	background-size: 28px 16px;
	background-repeat: no-repeat;
}*/

.alert,
#nowplaying {
	background-color: #d81102;
	color: <?=$BackgroundColor?>;
	font-weight: 700;
	margin: 0;
	padding: .2em .5em;
}

#nowplaying img {
	height: 1.5ex;
}

.tile {
	background-color: #3d3d3d;
	color: white;
	float: left;
	width: 160px;
	height: 160px;
	margin: .5em;
}

.tile img {
	width: 129px; 
	height: 129px; 
	padding: 0 15px;
}

.tile p {
	margin: 0 15px;
	font-family: "Segoe WP", "Segoe UI", "Open Sans", sans-serif;
	font-size: .85em;
	font-weight: 600;
}

img.pixelart {
	image-rendering:-moz-crisp-edges; 
	image-rendering: -o-crisp-edges; 
	image-rendering:-webkit-optimize-contrast; 
	-ms-interpolation-mode:nearest-neighbor;
	/* Fuck my life */
}


/*
 * Lists
 */
ul {
	padding-left: 2em;
	margin-bottom: 1em;
	list-style: square;
}

ol {
	padding-left: 2em;
	margin-bottom: 1em;
	list-style: decimal;
}

li {
	line-height: 1.5em;
}

li > p {
	margin: 0;
}

dl, dialog {
	margin: 1em 0;
}

dt {
	float: left;
	font-weight: 600;
}

dd {
	padding-left: 120px;
	line-height: 1.5;
}

#nav {
	list-style: none !important;
	padding-left: 0 !important;
}

#nav li {
	float: left;
	font: lighter 1.9em <?=$LightFontFamily?>;
	margin-right: 0.5em;
	text-transform: <?=$Capitalization?>;
}

#nav li a {
	color: #999999 !important;
	text-decoration: none !important;
	text-shadow: none !important;
}

#nav li.current a {
	color: #eeeeee !important;
	text-decoration: none !important;
	text-shadow: none !important;
}

/*
 * Header
 */
#main {
	margin: 1em;
}

#header {
	height: 100px;
	padding: 1em;
	background-color: #2d2d2d;
}
 
#avatar {
	border: 3px solid #86b5d9;
	float: left;
	margin-right: 1em;
	width: 96px;
	height: 96px;
}

#avatar.online {
	border-color: #86b5d9;
}

#avatar.ingame {
	border-color: #8bc53f;
}

#avatar.offline {
	border-color: #999999;
}

#title {
	font-weight: bold;
	text-transform: uppercase;
	color: <?=$ForegroundColor?>;
}

#title.online {
	color: #86b5d9;
}

#title.ingame {
	color: #8bc53f;
}

#title.offline {
	color: #999999;
}

#login-id {
	float: right;
	font-weight: bold;
	text-transform: uppercase;
	color: <?=$ForegroundColor?>;
}

/*
 * Footer
 */
#footer {
	border-top: 1px solid <?=$SubtleColor?>;
	color: <?=$SubtleColor?>;
	font-size: 0.8em;
	margin: 3em 0;
}

/*
 * Layout
 */
#content {
	clear: both;
}

/*
 * What the fuck, Chrome? http://bit.ly/OXGzDy
 */
dialog {
	background-color: inherit;
	border: none;
	color: inherit;
	display: block;
	padding: 0;
	position: static;
}