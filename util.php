<?php
    date_default_timezone_set('Europe/Amsterdam');

    function startsWith($haystack, $needle) {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    function endsWith($haystack, $needle) {
        $length = strlen($needle);
        $start  = $length * -1; //negative
        return (substr($haystack, $start) === $needle);
    }
    
    function getExtension($file) {
        $pos = strrpos($file, ".");
        if ($pos) {
            return (substr($file, $pos+1));
        }
        else {
            return "";
        }
    }
    
    function getFilenameWithoutExtension($file) {
        $pos = strrpos($file, ".");
        if ($pos) {
            return (substr($file, 0, $pos));
        }
        else {
            return $file;
        }
    }
    
    function getDirectoryList ($directory) {
        $results = array();
        $handler = opendir($directory);

        while ($file = readdir($handler)) {
            if (getExtension($file) === "php") {
                $results[] = getFilenameWithoutExtension($file);
            }
        }

        closedir($handler);
        sort($results);
        return $results;
    }
    
    function encode_email($e) {
        for ($i = 0; $i < strlen($e); $i++) { $output .= '&#'.ord($e[$i]).';'; }
        return $output;
    }

    function is_spambot($ip) {
        $url = "http://www.stopforumspam.com/api?ip=$ip&f=serial";
        $data = file_get_contents($url);
        $spamdata = unserialize($data);
        if ($spamdata["success"] == 1 && $spamdata["ip"]["appears"] == 1) {
            return true;
        }
        else {
            return false;
        }
    }
        
    function plural($num) {
        if ($num <> 1)
            return "s";
    }
         
    function time_elapsed_string($ptime) {
        $etime = time() - $ptime;
        
        if ($etime < 1) {
            return 'now';
        }
        
        $a = array( 12 * 30 * 24 * 60 * 60  =>  'year',
                    30 * 24 * 60 * 60       =>  'month',
                    7  * 24 * 60 * 60       =>  'week',
                    24 * 60 * 60            =>  'day',
                    60 * 60                 =>  'hour',
                    60                      =>  'minute',
                    1                       =>  'second'
                    );
        
        foreach ($a as $secs => $str) {
            $d = $etime / $secs;
            if ($d >= 1) {
                $r = round($d);
                return $r . ' ' . $str . ($r > 1 ? 's' : '') . ' ago';
            }
        }
    }
    
    function reltime($timestr) {
        return time_elapsed_string(strtotime($timestr));
    }
    
    function datetime($str) {
        $reltime = reltime($str);
        $html = <<<HTML
<span class="datetime" title="$str">$reltime</span>      
  
HTML;
        return $html;
    }
    
    function youtube($v, $width = 960, $height = 540, $start = NULL, $autoplay = false, $loop = false, $hide_controls = false) {
        $src = "http://www.youtube-nocookie.com/embed/$v?rel=0&hd=1&theme=light";
        
        if ($start) $src = $src."&start=$start";
        if ($autoplay) $src = $src."&autoplay=1";
        if ($loop) $src = $src."&playlist=$v&loop=1";
        if ($hide_controls) $src = $src."&controls=0&showinfo=0";
        
        $src = htmlentities($src);
        return "<iframe width=\"$width\" height=\"$height\" src=\"$src\" style=\"border: none;\"></iframe>\n";
    }
    
    function safety_dance() {
        echo youtube("vElbh2Ox1dA", 960, 540, 11, true, true, true);
    }