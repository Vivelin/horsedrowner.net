<?php
function print_project($project) 
{
    $name = htmlspecialchars($project["name"]);
    $desc = $project["description"];
    $status = htmlspecialchars($project["status"]);

    $html = "<h1>$name</h1>\n"
          . "<p>$desc</p>\n"
          . "<dl>\n"
          . "\t<dt>Status:\n\t<dd>$status\n";

    if (isset($project["version"])) {
        $html .= "\t<dt>Version:\n"
               . "\t<dd>" . htmlspecialchars($project["version"]) . "\n";
    }

    if (isset($project["customAttributes"])) {
        foreach ($project["customAttributes"] as $key => $value) {
            $html .= "\t<dt>" . htmlspecialchars($key) . ": \n"
                   . "\t<dd>" . htmlspecialchars($value) . "\n";
        }
    }

    $html .= "</dl>\n"
           . "<p>\n";

    if (isset($project["download64"])) {
        $dl64 = htmlspecialchars($project["download64"]);
        $html .= "\t<a class=\"button\" href=\"$dl64\">Download (64-bit)</a>\n";
    }

    if (isset($project["download32"])) {
        $dl32 = htmlspecialchars($project["download32"]);
        $html .= "\t<a class=\"button\" href=\"$dl32\">Download (32-bit)</a>\n";
    }

    if (isset($project["download"])) {
        $dl = htmlspecialchars($project["download"]);
        $html .= "\t<a class=\"button\" href=\"$dl\">Download</a>\n";
    }

    if (isset($project["source"])) {
      $source = htmlspecialchars($project["source"]);
      $sourceText = (strpos($source, "github.com") !== FALSE) ? "Follow on Github" : "Source";
      $html .= "\t<a target=\"_blank\" href=\"$source\">$sourceText</a>\n";
    }

    $html .= "</p>\n";
    echo $html;
}

include "data/projects.php";
?> 

<p>
    For a full list of projects, <a href="https://github.com/horsedrowner/">find me on GitHub</a>.
</p>

<?php
foreach ($projects as $project) {
    print_project($project);
}
?> 