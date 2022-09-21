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
	$printRequest = new RouterOS\Request('/ip hotspot user print');
	$printRequest->setArgument('.proplist','.id,server,name,profile,limit-uptime,limit-bytes-total,uptime,bytes-in,bytes-out');
	$printRequest->setQuery(RouterOS\Query::where('.id','*0', RouterOS\Query::OP_EQ) ->not()); 
	$idList = '';
	$i = 0;
	foreach ($client->sendSync($printRequest)->getAllOfType(RouterOS\Response::TYPE_DATA) as $item) {
		if (!empty($item->getProperty('limit-uptime'))) {
			if (!($item->getProperty('uptime') < $item->getProperty('limit-uptime'))) {
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
				$nestedData['total_uptime'] = $item->getProperty('uptime');
				$nestedData['total_bytes_used'] = ($item->getProperty('bytes-in') + $item->getProperty('bytes-out'));
				$nestedData['profile'] = $item->getProperty('profile');
				$nestedData['name'] = $item->getProperty('name');	
				$nestedData['limit_bytes_total'] = $limit_bytes_total;
				$nestedData['limit_uptime'] = $limit_uptime;
				
				$combinedString = $item->getProperty('comment');
				$splitArray = explode(';', $combinedString);
				$nestedData['telephone'] = $splitArray[0];
				$nestedData['date'] = $splitArray[1];
				$nestedData['time'] = $splitArray[2];
				$nestedData['by'] = $splitArray[3];
				$data[] = $nestedData;
			}
		}
	}
}
catch (Exception $e) {
	echo "Error Accessing Data: " . $e->getMessage();
}

if (!isset($data)) {
	echo null;
} else {
	$json_data = array( "data" => $data );
	echo json_encode($json_data);  // send data as json format
}
?>
