<?php
/**
 * A collection of functions related to string formatting and printing to HTML.
 */
class Pretty
{
	/**
	 * Prints the specified text.
	 */
	static function Write($text)
	{
		print htmlspecialchars($text);
	}

	/**
	 * Returns a string that represents the specified file size.
	 *
	 * @param int @size	The size of the file, in bytes.
	 * @return string 	A string that represents the specified file size.
	 */
	static function FormatFileSize($size)
	{
		if ($size > 1024 * 1024 * 1024)
			$str = number_format($size / (1024 * 1024 * 1024), 1) . "GiB";
		if ($size > 1024 * 1024)
			$str = number_format($size / (1024 * 1024), 1) . " MiB";
		else if ($size > 1024)
			$str = number_format($size / 1024, 1) . " KiB";
		else
			$str = number_format($size, 1) . " B";
		return $str;
	}

	/**
	 * Returns a string that represents the specified date.
	 *
	 * @param mixed $date	The date to format as string or as Unix timestamp.
	 * @return string 		A string that represents the specified date.
	 */
	static function FormatDate($date)
	{
		if (!ctype_digit($date))
		{
			if ($date instanceof DateTime)
				$date = $date->getTimestamp();
			else
				$date = strtotime($date);
		}
		$date = getdate($date);

		$format = "j M Y";
		if ($date["hours"] > 0 || $date["minutes"] > 0 || $date["seconds"] > 0) {
			$format .= ", G:i";
			if ($date["seconds"] > 0)
				$format .= ":s";
		}

		return date($format, $date[0]);
	}

	/**
	 * Returns the specified date or time as HTML markup.
	 *
	 * @param mixed $date 	The date to print as string or as Unix timestamp.
 	 * @return string  		A string that represents the specified date as HTML.
	 */
	static function DateTime($date)
	{
		$html = "<span class=\"datetime\" title=\"" . self::FormatDate($date) . "\">"
		      . self::RelativeTime($date) . "</span>";
		return $html;
	}

	/**
	 * Returns a string that presents the specified date, relative to the current time.
	 *
	 * @param mixed $date	The date to format as string or as Unix timestamp.
	 * @return string A string that represents the specified date, relative to the current time.
	 */
	static function RelativeTime($date)
	{
		if (!ctype_digit($date))
		{
			if ($date instanceof DateTime)
				$date = $date->getTimestamp();
			else
				$date = strtotime($date);
		}

		$diff = time() - $date;
		if ($diff == 0)
			return 'now';
		else if ($diff > 0)
		{
			// Past
			$day_diff = floor($diff / 86400);
			$month_diff = floor($day_diff / 30);
			if ($day_diff == 0)
			{
				if ($diff < 60) 	return 'just now';
				if ($diff < 120) 	return 'a minute ago';
				if ($diff < 3600) 	return floor($diff / 60) . ' minutes ago';
				if ($diff < 7200) 	return 'a hour ago';
				if ($diff < 86400) 	return floor($diff / 3600) . ' hours ago';
			}
			if ($day_diff == 1) return 'yesterday';
			if ($day_diff <  7) return $day_diff . ' days ago';
			if ($day_diff < 14) return 'a week ago';
			if ($day_diff < 31) return ceil($day_diff / 7) . ' weeks ago';
			if ($day_diff < 60) return 'last month';
			if ($month_diff < 12) return $month_diff . ' months ago';
			if ($month_diff < 24) return 'a year ago';

			return 'a long, long time ago';
		}
		else
		{
			// Future
			$diff = abs($diff);
			$day_diff = floor($diff / 86400);
			if($day_diff == 0)
			{
				if ($diff <   120) 	return 'in a minute';
				if ($diff <  3600) 	return 'in ' . floor($diff / 60) . ' minutes';
				if ($diff <  7200) 	return 'in an hour';
				if ($diff < 86400) 	return 'in ' . floor($diff / 3600) . ' hours';
			}
			if ($day_diff == 1) return 'tomorrow';
			if ($day_diff < 4) return date('l', $date);
			if ($day_diff < 7 + (7 - date('w'))) return 'next week';
			if (ceil($day_diff / 7) < 4) return 'in ' . ceil($day_diff / 7) . ' weeks';
			if (date('n', $date) == date('n') + 1) return 'next month';

			return self::FormatDate($date);
		}
	}

	/**
	 * Returns an HTML string that represents a YouTube video.
	 *
	 * @param string $v The video ID of the video.
     * @return string 	A string containing the HTML code for the YouTube video.
	 */
	static function YouTube($v, $width = 960, $height = 540, $start = NULL, $autoplay = false,
							$loop = false, $hide_controls = false)
	{
        $src = "http://www.youtube-nocookie.com/embed/$v?rel=0&hd=1&theme=light";

        if ($start) $src = $src."&start=$start";
        if ($autoplay) $src = $src."&autoplay=1";
        if ($loop) $src = $src."&playlist=$v&loop=1";
        if ($hide_controls) $src = $src."&controls=0&showinfo=0";

        $src = htmlentities($src);
        return "<iframe width=\"$width\" height=\"$height\" src=\"$src\" style=\"border: none;\"></iframe>\n";
    }

    /**
     * We can dance if we want to, we can leave your friends behind.
     * 'Cause your friends don't dance and if they don't dance, well they're no friends of mine.
     */
    static function SafetyDance()
    {
        print self::YouTube("vElbh2Ox1dA", 960, 540, 11, true, true, true);
    }

    static function PrintData($obj)
    {
    	if (!$obj) return;

    	print "<dl>\n";
    	foreach ($obj as $key => $value)
    	{
    		if ($value)
    		{
    			print "\t<dt>" . htmlspecialchars($key);
    			if (is_array($value))
    			{
    				foreach ($value as $item)
    				{
    					print "<dd>" . htmlspecialchars($item);
    				}
    			}
    			else
    			{
    				print "<dd>" . htmlspecialchars($value);
    			}
    			print "\n";
    		}
    	}
    	print "</dl>\n";
    }
}
