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
$host_id = $_POST['host_id'];

elog('Attempting to unlink user with ID "'.$user_id.'" from host "'.$_SESSION['host_name'].'" with ID "'.$host_id.'".');

$stmt = $DB_con->prepare("DELETE FROM user_host WHERE user_id=:user_id AND host_id=:host_id");
$stmt->execute(array(':user_id' => $user_id, ':host_id' => $host_id));

elog($stmt->rowCount() .' row(s) affected.');
echo $host_id;

?>