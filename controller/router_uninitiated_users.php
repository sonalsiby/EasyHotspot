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
$util->setMenu('/ip hotspot user');
$i = 0;
foreach ($util->getAll() as $item) {
	if ($item->getProperty('uptime') == 0) {
		$i++;
		if ($item->getProperty('limit-bytes-total')) {
			$limit_bytes_total = $item->getProperty('limit-bytes-total').' Bytes';
		}
		else { $limit_bytes_total = 'Unlimited'; }
		
		if ($item->getProperty('limit-uptime')) {
			$limit_uptime = $item->getProperty('limit-uptime');
		}
		else { $limit_uptime = 'Not Limited'; }
		
		if (!empty($item->getProperty('limit-uptime'))) {
			if (!($item->getProperty('uptime') < $item->getProperty('limit-uptime'))) {
				$expired = "Yes";
			} else $expired = "No";
		}
	
		$nestedData=array();
		$nestedData['id'] = $i;
		$combinedString = $item->getProperty('comment');
		$splitArray = explode(';', $combinedString);
		$nestedData['telephone'] = $splitArray[0];
		$nestedData['date'] = $splitArray[1];
		$nestedData['time'] = $splitArray[2];
		$nestedData['by'] = $splitArray[3];
		$nestedData['email'] = $item->getProperty('email');
		$nestedData['profile'] = $item->getProperty('profile');
		$nestedData['name'] = $item->getProperty('name');	
		$nestedData['limit_bytes_total'] = $limit_bytes_total;
		$nestedData['limit_uptime'] = $limit_uptime;
		$data[] = $nestedData;
	}
}
if (!isset($data)) {
	echo null;
} else {
	$json_data = array( "data" => $data );
	echo json_encode($json_data);  // send data as json format
}
?>
