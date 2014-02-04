<?php
$ip = $_SERVER["REMOTE_ADDR"];
if (isset($_SERVER["HTTP_CF_CONNECTING_IP"]))
	$ip = $_SERVER["HTTP_CF_CONNECTING_IP"];
else if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
	$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
?>
<dl>
    <dt>IP address
    <dd><?php echo $ip;?>	

    <dt>User agent
    <dd><?php echo $_SERVER["HTTP_USER_AGENT"];?> 
</dl>
