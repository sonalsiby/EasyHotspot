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
$client = new RouterOS\Client("$host", "$user", "$pass");

$printRequest = new RouterOS\Request('/ip/hotspot/user/print');
$printRequest->setArgument('.proplist', '.id,name,password,comment,profile,email,limit-uptime,limit-bytes-total');

$guest_list = $_POST['user_list'];

foreach ($guest_list as $guest) {
    $guest = trim($guest);
    $printRequest->setQuery(RouterOS\Query::where('name', $guest, RouterOS\Query::OP_EQ));
    foreach ($client->sendSync($printRequest)->getAllOfType(RouterOS\Response::TYPE_DATA) as $item) {
        $nestedData = array();
        $nestedData['email'] = $item->getProperty('email');
        $nestedData['username'] = $item->getProperty('name');
        $nestedData['profile'] = $item->getProperty('profile');
        $nestedData['password'] = $item->getProperty('password');
        $nestedData['comment'] = $item->getProperty('comment');
        $nestedData['limit_uptime'] = $item->getProperty('limit-uptime');
        $nestedData['limit_bytes_total'] = $item->getProperty('limit-bytes-total');
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
