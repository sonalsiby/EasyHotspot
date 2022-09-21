<?php
if ( !isset($_SESSION) ) session_start();
if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
	header('Location: ../controller/logout.php');
}
if (!(($_SESSION['user_level']>=1) && ($_SESSION['user_level']<=4) && (isset($_SESSION['id'])))) {
	header('Location: ../controller/logout.php');
}
if (!function_exists('elog')) {
    include('../controller/_log.php');
}

include('db_conn.php');
$user_id = $_POST['user_id'];

elog('Attempting to link user with ID "'.$user_id.'" to hotspot "'.$_SESSION['host_name'].'" with ID "'.$_SESSION['host_id'].'".');

$stmt = $DB_con->prepare("SELECT id FROM user_host WHERE user_id=:user_id AND host_id=:host_id");
$stmt->execute(array(':user_id' => $user_id, ':host_id' => $_SESSION['host_id']));

if ($stmt->rowCount() == 0) {
    $stmt = $DB_con->prepare("INSERT INTO user_host (host_id, user_id) values(:host_id, :user_id)");
    $stmt->execute(array(':host_id' => $_SESSION['host_id'], ':user_id' => $user_id));
    elog('Successfully linked user to hotspot.');
} else {
    elog('User already linked to hotspot. Did nothing.');
}

?>