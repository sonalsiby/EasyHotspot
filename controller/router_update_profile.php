<?php
if ( !isset($_SESSION) ) session_start();
if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
	header('Location: logout.php');
}
if (!(($_SESSION['user_level']>=1) && ($_SESSION['user_level']<=4) && (isset($_SESSION['id'])))) {
	header('Location: logout.php');
}
if (!function_exists('elog')) {
	include('_log.php');
}
if (isset($_SESSION['demo'])) { 
	elog('Demo user trying to edit/update profile. Echoed success.');
	echo '2'; return; 
}

$host_id = $_SESSION['host_id'];
include('../model/db_conn.php');
use PEAR2\Net\RouterOS;
require_once 'PEAR2/Autoload.php';

try {
	$stmt = $DB_con->prepare("SELECT * FROM host WHERE id =:host_id AND status =:status");
	$stmt->execute(array(':host_id' => $host_id, ':status' => 'Active'));
	if ($stmt->rowCount() == 1) {
		$row=$stmt->fetch(PDO::FETCH_ASSOC);
		$host = $row['host'];
		$user = $row['user'];
		$pass = $row['pass'];
	}
}
catch (Exception $e) {
	echo "Error Accessing Data: " . $e->getMessage();
}

if (($_SESSION['user_level'] == 1) || ($_SESSION['user_level'] == 2 ) || ($_SESSION['user_level'] == 3 )) {
	$util = new RouterOS\Util($client = new RouterOS\Client("$host", "$user", "$pass"));

	$profile_name=strtolower($_POST['profile_name']);
	$session_timeout=$_POST['session_timeout'];
	$shared_users=$_POST['shared_users'];
	$mac_cookie_timeout=$_POST['mac_cookie_timeout'];
	$keepalive_timeout=$_POST['keepalive_timeout'];
	$rx_rate_limit=$_POST['rx_rate_limit'];
	$tx_rate_limit=$_POST['tx_rate_limit'];

	$validity = $_POST['validity'];
	$grace_period = $_POST['grace_period'];
	$on_expiry = $_POST['on_expiry'];
	$price = $_POST['price'];
	$lock_user = $_POST['lock_user'];
	
	$rate_limit = $rx_rate_limit.'/'.$tx_rate_limit;
	if (empty($rx_rate_limit))  $rx_rate_limit = "256k";
	if (empty($tx_rate_limit))  $tx_rate_limit = "128k";
	if (empty($shared_users))  $shared_users = 1;

	if ($price == "") {$price = "0";}
	if($lock_user == Enable){$mac_bind = ';[:local mac $"mac-address"; /ip hotspot user set mac-address=$mac [find where name=$user]]';} else {$mac_bind = "";}

	$login_script = "";
	elog('Editing profile "'.$profile_name.'" :: MAC Timeout "'.$mac_cookie_timeout.'" :: VAL "'.$validity.'" & Shared Users "'.$shared_users.'".');

	switch ($on_expiry) {
		case "rem":
			$login_script = ':put (",rem,'.$price.','.$validity.','.$grace_period.',,'.$lock_user.',");{:local date [/system clock get date ];:local time [/system clock get time ];:local uptime ('.$validity.');[/system scheduler add disabled=no interval=$uptime name=$user on-event="[/ip hotspot active remove [find where user=$user]];[/ip hotspot user set limit-uptime=1s [find where name=$user]];[/sys sch re [find where name=$user]];[/sys script run [find where name=$user]];[/sys script re [find where name=$user]]" start-date=$date start-time=$time];[/system script add name=$user source=":local date [/system clock get date ];:local time [/system clock get time ];:local uptime ('.$grace_period.');[/system scheduler add disabled=no interval=\$uptime name=$user on-event= \"[/ip hotspot user remove [find where name=$user]];[/ip hotspot active remove [find where user=$user]];[/sys sch re [find where name=$user]]\"]"]';
			break;
		case "ntf":
			$login_script = ':put (",ntf,'.$price.','.$validity.',,,'.$lock_user.',"); {:local date [/system clock get date ];:local time [/system clock get time ];:local uptime ('.$validity.');[/system scheduler add disabled=no interval=$uptime name=$user on-event= "[/ip hotspot user set limit-uptime=1s [find where name=$user]];[/ip hotspot active remove [find where user=$user]];[/sys sch re [find where name=$user]]" start-date=$date start-time=$time]';
			break;
		case "remc":
			$login_script = ':put (",remc,'.$price.','.$validity.','.$grace_period.',,'.$lock_user.',"); {:local price ('.$price.');:local date [/system clock get date ];:local time [/system clock get time ];:local uptime ('.$validity.');[/system scheduler add disabled=no interval=$uptime name=$user on-event="[/ip hotspot active remove [find where user=$user]];[/ip hotspot user set limit-uptime=1s [find where name=$user]];[/sys sch re [find where name=$user]];[/sys script run [find where name=$user]];[/sys script re [find where name=$user]]" start-date=$date start-time=$time];[/system script add name=$user source=":local date [/system clock get date ];:local time [/system clock get time ];:local uptime ('.$grace_period.');[/system scheduler add disabled=no interval=\$uptime name=$user on-event= \"[/ip hotspot user remove [find where name=$user]];[/ip hotspot active remove [find where user=$user]];[/sys sch re [find where name=$user]]\"]"];:local bln [:pick $date 0 3]; :local thn [:pick $date 7 11];[:local mac $"mac-address"; /system script add name="$date-|-$time-|-$user-|-$price-|-$address-|-$mac-|-'.$validity.'" owner="$bln$thn" source=$date comment=Zetozone]';
			break;
		case "ntfc":
			$login_script = ':put (",ntfc,'.$price.','.$validity.',,,'.$lock_user.',"); {:local price ('.$price.');:local date [/system clock get date ];:local time [/system clock get time ];:local uptime ('.$validity.');[/system scheduler add disabled=no interval=$uptime name=$user on-event= "[/ip hotspot user set limit-uptime=1s [find where name=$user]];[/ip hotspot active remove [find where user=$user]];[/sys sch re [find where name=$user]]" start-date=$date start-time=$time];:local bln [:pick $date 0 3]; :local thn [:pick $date 7 11];[:local mac $"mac-address"; /system script add name="$date-|-$time-|-$user-|-$price-|-$address-|-$mac-|-'.$validity.'" owner="$bln$thn" source=$date comment=Zetozone]';
			break;
		case "0":
			if ($price != "" ){
				$login_script = ':put (",,'.$price.',,,noexp,'.$lock_user.',")';
			}	
			break;
	}
	$login_script .= $mac_bind;
	
	if (!empty($profile_name)) {
		
		$printRequest = new RouterOS\Request('/ip/hotspot/user/profile/print');
		$printRequest->setArgument('.proplist', '.id');
		$printRequest->setQuery(RouterOS\Query::where('name', $profile_name));
		$id = $client->sendSync($printRequest)->getProperty('.id');

		$setRequest = new RouterOS\Request('/ip/hotspot/user/profile/set');
		$setRequest->setArgument('numbers', $id);
		$setRequest->setArgument('rate-limit', $rate_limit);

		$setRequest->setArgument('shared-users', $shared_users);
		$setRequest->setArgument('idle-timeout', $session_timeout);
		$setRequest->setArgument('status-autorefresh', "1m");
		$setRequest->setArgument('transparent-proxy', "yes");
		$setRequest->setArgument('on-login', "$login_script");
		
		$client->sendSync($setRequest);

		elog('Profile update successful.');
		echo 2; //Success
	}
	else
		{
		elog('Profile update error.');
		echo 1; //Profile name Improper
	}
}
else
	{
		elog('Profile update error. Permission denied.');
		echo 0; //Not Authorised
}
?>