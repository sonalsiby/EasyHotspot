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
	elog('Demo user trying to remove user(s). Echoed succeess.');
	echo '0'; return; 
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
$util = new RouterOS\Util($client = new RouterOS\Client("$host", "$user", "$pass"));

$guest_name=trim($_POST['username']);

elog('Deleting user "'.$guest_name.'".');

$printRequest = new RouterOS\Request('/ip/hotspot/user/print');
$printRequest->setArgument('.proplist', '.id,name');
$printRequest->setQuery(RouterOS\Query::where('name', $guest_name));
$id = $client->sendSync($printRequest)->getProperty('.id');

$removeRequest = new RouterOS\Request('/ip/hotspot/user/remove');
$removeRequest->setArgument('numbers', $id);
$client->sendSync($removeRequest);

elog('Deleted user "'.$guest_name.'".');
?>
