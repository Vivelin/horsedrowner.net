<?php 
if (Debug::IsEnabled()) {
	$id = filter_input(INPUT_GET, "id") ?: "76561197994245359";

	$steam = new Steam($steamApiKey);
	$name   = $steam->GetName($id);
	$status = $steam->GetStatus($id);
	$class  = htmlspecialchars($steam->GetStatusSimple($id));

	if (!$name || !$status)
		print "<strong class=\"alert\">API call failed!</strong>\n";
	else {
		print "<strong class=\"$class\">";
		Pretty::Write($name . " - " . $status);
		print "</strong>";
	}
}