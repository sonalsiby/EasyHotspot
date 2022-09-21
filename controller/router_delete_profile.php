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
	elog('Demo user trying to delete router profile. Echoed success');
	echo '2'; return; 
}

header('Content-Type: application/json');
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
if ( !isset($_SESSION) ) session_start();
$util = new RouterOS\Util($client = new RouterOS\Client("$host", "$user", "$pass"));

$profile_name=strtolower($_POST['profile_name']);

elog('Deleting router profile "'.$profile_name.'".');

if ($_SESSION['user_level'] == 1) {
	
	if (!empty($profile_name)) {
		
		$printRequest = new RouterOS\Request('/ip hotspot user profile print');
		$printRequest->setArgument('.proplist', '.id,name');
		$printRequest->setQuery(RouterOS\Query::where('name', $profile_name)); 

		$idList = '';
		foreach ($client->sendSync($printRequest)->getAllOfType(RouterOS\Response::TYPE_DATA) as $item) {
			$idList .= ',' . $item->getProperty('.id');
		}
		$idList = substr($idList, 1);
		//$idList now contains a comma separated list of all IDs.

		$removeRequest = new RouterOS\Request('/ip hotspot user profile remove');
		$removeRequest->setArgument('numbers', $idList);
		$client->sendSync($removeRequest); 

		elog('Profile deleted from router.');
		echo 2; //Success

	} else {
		elog('Profile deletion error.');
		echo 1; //Profile name Empty
	}

} else {
	
	elog('Profile deletion error. Permission denied.');
	echo 0; //Not Authorised
}
?>
