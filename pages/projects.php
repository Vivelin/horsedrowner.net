<?php
function print_project($project) 
{
    $html = "<h1>" . htmlspecialchars($project["name"]) . "</h1>\n"
          . "<p>" . $project["description"] . "</p>\n"
          . "<dl>\n"
          . "\t<dt>Status:\n\t<dd>" . htmlspecialchars($project["status"]) . "\n";

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

    /* URL-encoding and array checking... woo. */
    $dl64 = "";
    if (isset($project["download64"]))
        $dl64 = " href=\"" . htmlspecialchars($project["download64"]) . "\"";

    $dl32 = "";
    if (isset($project["download32"]))
        $dl32 = " href=\"" . htmlspecialchars($project["download32"]) . "\"";

    $source = htmlspecialchars($project["source"]);
    if (strpos($source, "github.com") !== FALSE) {
        $sourceText = "Follow on Github";
    } else {
        $sourceText = "Source";
    }

    $html .= "</dl>\n"
           . "<p>\n"
           . "\t<a class=\"button\"" . $dl64 . ">Download (64-bit)</a>\n"
           . "\t<a class=\"button\"" . $dl32 . ">Download (32-bit)</a>\n"
           . "\t<a target=\"_blank\" href=\"" . $source . "\">" . $sourceText . "</a>\n"
           . "</p>";

    $html .= "\n";
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