<?php
if ( !isset($_SESSION) ) session_start();
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
try {
	$util = new RouterOS\Util($client = new RouterOS\Client("$host", "$user", "$pass"));
}
catch (Exception $e) {
	echo "Error Accessing Data: " . $e->getMessage();
}

$i = 0;
foreach ($util->setMenu('/ip hotspot user profile')->getAll() as $entry) {
	$i++;
	// foreach ($entry as $key=>$value) {
	// 	echo $key.'||';
	// }
	$nestedData=array();
	$nestedData['id'] = $i;
	$nestedData['name'] = $entry('name');
	$nestedData['session_timeout'] = $entry('session-timeout');
	$nestedData['keepalive_timeout'] = $entry('keepalive-timeout');
	$nestedData['shared_users'] = $entry('shared-users');
	$nestedData['rate_limit'] = $entry('rate-limit');
	$nestedData['mac_cookie_timeout'] = $entry('mac-cookie-timeout');
	$nestedData['validity'] = $entry('session-timeout');
	$nestedData['on_login'] = substr($entry('on-login'), 8, 3);
	$data[] = $nestedData;
}

if (!isset($data)) {
	echo null;
} else {
	$json_data = array( "data" => $data );
	echo json_encode($json_data);  // send data as json format
}
?>
