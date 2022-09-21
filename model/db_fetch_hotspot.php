<?php
if ( !isset($_SESSION) ) session_start();
if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
	header('Location: ../controller/logout.php');
}
if (!(($_SESSION['user_level']>=1) && ($_SESSION['user_level']<=4) && (isset($_SESSION['id'])))) {
	header('Location: ../controller/logout.php');
}
$hotspot_id = $_POST['id'];

include('db_conn.php');

$stmt = $DB_con->prepare("SELECT * FROM host WHERE id=:hid");
$stmt->execute(array(':hid' => $hotspot_id));

$row = $stmt->fetch(PDO::FETCH_ASSOC);
$nestedData = array();
foreach ($row as $key => $value) {
    $nestedData[$key] = $value;
}
$data[] = $nestedData;


$json_data = array( "data" => $data );
echo json_encode($json_data);  
?>
