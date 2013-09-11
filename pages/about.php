<?php 
function get_age() {
    return DateTime::createFromFormat('d/m/Y', '10/12/1991')->diff(new DateTime('now'))->y;
}

function print_show($show) {
    $html = "<li>" . htmlspecialchars($show["name"]);

    if (isset($show["season"])) {
        $html .= " <em>";

        if (isset($show["episode"])) {
            $html .= sprintf("S%02dE%02d", intval($show["season"]), intval($show["episode"]));
        } else {
            $html .= "Season " . intval($show["season"]);
        }

        if (isset($show["episodename"])) {
            $html .= " <q>" . htmlspecialchars($show["episodename"]) . "</q>";
        }

        if (isset($show["time"])) {
            $html .= " &bull; <span class=\"datetime\">" . Pretty::RelativeTime($show["time"]) 
                   . "</span>";
        }

        $html .= "</em>";
    }

    $html .= "\n";

    echo $html;
}

include "data/series.php";

usort($series, function($x, $y) {
    $xd = new DateTime($x["time"]);
    $yd = new DateTime($y["time"]);

    if ($xd == $yd) {
        return 0;
    }
    return $xd > $yd ? -1 : 1;
});

?>

<dl>
    <dt>Name 
    <dd>Ruben Verdoes <span class="nickname">(horsedrowner)</span>

    <dt>Age     
    <dd><?php echo get_age();?> <em>(10 december 1991)</em>
        
    <dt>Occupation
    <dd><a href="http://www.liacs.nl/">Computer Science student</a> <em>since 2010</em>
    <dd><a href="http://www.decos.com/">Software Engineer</a> <em>since 2007</em>

    <dt>Email
    <dd><a href="mailto:horsedrowner@gmail.com">horsedrowner@gmail.com</a>

    <dt>Steam
    <dd><a href="http://steamcommunity.com/id/horsedrowner">horsedrowner</a>

    <dt>Twitter
    <dd><a href="https://twitter.com/horsedrowner">@horsedrowner</a>
</dl>

<p>
    The name <span class="nickname">&ldquo;horsedrowner&rdquo;</span> was the name I came up with 
    when I first registered an account online. That was in the game <em>Age&nbsp;of&nbsp;Mythology
    </em>. The name was based on the Norse &ldquo;Hersir&rdquo; unit, which each has a unique, 
    random name. Examples include <em>Ormr Boardmonger</em>, <em>Vigfus Manysmasher</em>, 
    <em>Herjolfr Mammothbreaker</em>, and the first thing that came to my mind when registering an 
    account to play multiplayer was <span class="nickname">&ldquo;horsedrowner&rdquo;</span>. I have
    been using it ever since, so if you see the name anywhere, you can be sure it's me.

<h1>Friends</h1>
<ul>
    <li><a href="http://www.fearswe.net/">Lord Fear</a>
    <li><a href="http://www.team-metro.net/">Wingar</a>
    <li><a href="http://fundamentalscarf.se/">FundamScarf</a>
    <li><a href="http://jennifoxi.tumblr.com/">Jenni</a>
</ul>

<h1 id="tv">TV series</h1>
<h2>Watching</h2>
<ul>
<?php
    foreach ($series as $show) {
        print_show($show);
    }
?>
</ul>

<h2>Planning to watch</h2>
<ul>
    <li>Firefly
    <li>Dead Like Me
</ul>

<h1 id="movies">Movies</h1>
<p>Obviously not a complete list.
<ul>
    <li>Austin Powers: International Man of Mystery <em><?php Pretty::WriteDateTime('18-8-2012 22:48'); ?></em>
    <li>WALL-E <em><?php Pretty::WriteDateTime('18-6-2012 15:00'); ?></em>
    <li>Super Troopers <em><?php Pretty::WriteDateTime('5-5-2012'); ?></em>
</ul>

<h1>Awesome people</h1>
<ul>
    <li><strong><a href="http://dbsoundworks.com/">Danny Baranowsky</a></strong>
        <p>
            For doing the music from <a href="http://www.supermeatboy.com/">Super Meat Boy</a>, 
            <a href="http://store.steampowered.com/app/113200/">The Binding of Isaac</a>, and the 
            remastered music from <a href="http://store.steampowered.com/app/200900/">
            Cave Story+</a>, and responding to emails (and quick at that!). Watch the <a href="http://www.design3.com/industry-insight/gdc-2012/item/2500-gdc-2012-interview-with-danny-baranowsky/2500-gdc-2012-interview-with-danny-baranowsky">
            interview with Danny Baranowsky</a> on GDC2012.
            
    <li><strong><a href="http://www.gordonmcneil.com/">Gordon McNeil</a></strong>
        <p>
            For doing the absolutely amazing <a href="http://store.steampowered.com/app/107310/">
            Cthulhu Saves The World</a> <a href="http://gordonmcneil.com/?page_id=143">
            soundtrack</a>.

    <li><strong><a href="http://www.youtube.com/CynicalBrit">TotalBiscuit</a>,
            <a href="http://www.youtube.com/PressHeartToContinue">Dodger (PressHeartToContinue)</a>,
            <a href="http://www.youtube.com/JesseCox">Jesse Cox</a> and
            <a href="http://www.youtube.com/Lucahjin">Lucahjin</a>
        </strong>
        <p>
            Also watch the <a href="http://www.youtube.com/TheGameStation">The Game Station podcast</a>.
</ul>

<h1>PC Specs</h1>
<dl>
    <dt>Case
    <dd>Obsidian 650D

    <dt>Motherboard
    <dd>Asus P8Z68-V Pro

    <dt>CPU
    <dd>Intel Core i5 2500K

    <dt>RAM
    <dd>G.Skill 8 GB DDR3-1600

    <dt>Graphics
    <dd>EVGA NVIDIA GeForce GTX 570

    <dt>SSD
    <dd>Crucial M4 128 GB

    <dt>HDD
    <dd>Samsung Spinpoint F3 1 TB

    <dt>PSU
    <dd>Corsair TX750M

    <dt>Keyboard
    <dd>Das Keyboard Professional Silent

    <dt>Mouse
    <dd>Logitech G500
</dl>