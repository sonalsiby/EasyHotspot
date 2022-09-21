<?php
if ( !isset($_SESSION) ) session_start();
if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
	header('Location: ../controller/logout.php');
}
if (!(($_SESSION['user_level']>=1) && ($_SESSION['user_level']<=4) && (isset($_SESSION['id'])))) {
	header('Location: ../controller/logout.php');
}
$hotspot_id = $_SESSION['host_id'];
include('db_conn.php');
$stmt = $DB_con->prepare("SELECT user_id, username, user_level FROM user LEFT JOIN user_host ON user.id = user_host.user_id WHERE user_host.host_id = :host_id AND user_level = 2");
$stmt->execute(array(':host_id' => $hotspot_id));

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $nestedData = array();
    foreach ($row as $key => $value) {
        $nestedData[$key] = $value;
    }
    $data[] = $nestedData;
}

$json_data = array( "data" => $data );
echo json_encode($json_data);
?>
