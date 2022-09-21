<?php
if (!isset($_SESSION)) { session_start(); }
if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
	header('Location: logout.php');
}
if (!(($_SESSION['user_level']>=1) && ($_SESSION['user_level']<=4) && (isset($_SESSION['id'])))) {
	header('Location: logout.php');
}

use PEAR2\Net\RouterOS;
require_once 'PEAR2/Autoload.php';

$host_id = $_SESSION['host_id'];
include('../model/db_conn.php');

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

try { //Try to access the router device
    $util = new RouterOS\Util($client = new RouterOS\Client("$host", "$user", "$pass"));
    $_SESSION['util'] = $util;
}
 catch (Exception $e) { //If not able to connect to the router with the settings in the settings file, 
	//require('hs_settings.php'); //Activate the window for setting hotspot settings like host-ip, username & password.
	echo "Unable to connect to Router $host, $user, $pass || ";
	echo $e->getMessage();
    exit;
    //die('Unable to connect to the router.');
    //Inspect $e if you want to know details about the failure.
}

//Read values from the router
$util=$_SESSION['util'];	
$util->setMenu('/system identity');
$_SESSION['system_identity'] = $util->get(null, 'name');

$util->setMenu('/system/clock');
$clock = $util->getAll();

$util->setMenu('/system/resource');
$resource = $util->getAll();
if (empty($_SESSION['tx_rx_factor'])) $_SESSION['tx_rx_factor'] = "100000";
//Read various values from the router for displaying on the Dashboard
$util->setMenu('/ip hotspot active');
$active_users = count($util);
$util->setMenu('/ip hotspot user');
$total_users = count($util);
$uninitiated_users = $util->count(RouterOS\Query::where('uptime', '0'));
$expired_users = $util->count(RouterOS\Query::where('limit-uptime', 'uptime', RouterOS\Query::OP_LT) ->not());  //NOT Proper, need additional processes

$util->setMenu('/interface');
$interface = $util->get(0, 'name');
//$interface = '';
//$hotspot = $util->get(5, 'name');
$hotspot = '';

$trequest = new RouterOS\Request('/interface monitor-traffic interface='.$interface.' once');
$trequest2result = $client->sendSync($trequest);

// $wrequest = new RouterOS\Request('/interface monitor-traffic interface='.$interface.' once');

//Tx / Rx values for the display of the progress bar at the bottom
$rx_value = $trequest2result->getProperty('rx-bits-per-second');
$tx_value = $trequest2result->getProperty("tx-bits-per-second");

$returnArray = array();
$returnArray['system_identity'] = $_SESSION['system_identity'];
$returnArray['interface'] = $interface;
$returnArray['active_users'] = $active_users;
$returnArray['total_users'] = $total_users;
$returnArray['uninitiated_users'] = $uninitiated_users;
$returnArray['expired_users'] = $expired_users;
$returnArray['cpu'] = $resource->getProperty('cpu');
$returnArray['cpu_load'] = $resource->getProperty('cpu-load');
$returnArray['cores'] = $resource->getProperty('cpu-count');
$returnArray['freq'] = $resource->getProperty('cpu-frequency');
$returnArray['board'] = $resource->getProperty('board-name');
$returnArray['version'] = $resource->getProperty('version');
$returnArray['free_space'] = $resource->getProperty('free-hdd-space');
$returnArray['total_space'] = $resource->getProperty('total-hdd-space');
$returnArray['free_memory'] = $resource->getProperty('free-memory');
$returnArray['total_memory'] = $resource->getProperty('total-memory');
$returnArray['uptime'] = $resource->getProperty('uptime');
$returnArray['time'] = $clock->getProperty('time');
$returnArray['rx'] = $rx_value;
$returnArray['tx'] = $tx_value;
$returnArray['wifi'] = $hotspot;

echo json_encode($returnArray);

?>
