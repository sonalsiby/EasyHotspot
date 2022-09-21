<?php
if ( !isset($_SESSION) ) session_start();
if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
	header('Location: logout.php');
}
if (!(($_SESSION['user_level']>=1) && ($_SESSION['user_level']<=4) && (isset($_SESSION['id'])))) {
	header('Location: logout.php');
}
include('../model/db_conn.php');
$my_level = $_SESSION['user_level'];

if (($my_level != 1) || ($_SESSION['username'] != 'admin')) {
	$my_level = $_SESSION['user_level'] + 1;
}

try {
	$stmt = $DB_con->prepare("SELECT * FROM user WHERE user_level >= $my_level AND host_id =:current_host");
	$stmt->execute(array(':current_host' => $_SESSION['host_id']));
	$count = $stmt->rowCount();
	while($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
		$nestedData=array();
		$nestedData['id'] = $row['id'];
		$nestedData['username'] = $row['username'];
		$nestedData['created_on'] = $row['created_on'];
		$nestedData['name'] = $row['name'];
		$nestedData['address'] = $row['address'];
		$nestedData['avatar'] = $row['avatar'];
		$nestedData['phone'] = $row['phone'];
		$nestedData['email'] = $row['email'];
		$nestedData['user_level'] = $row['user_level'];
		$nestedData['status'] = $row['status'];
		$data[] = $nestedData;
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
