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
	elog('Demo user trying to delete system user. Echoed success.');
	echo '2'; return; 
}

include('../model/db_conn.php');

$username = $_POST['username'];
$stmt = $DB_con->prepare("SELECT user_level FROM user WHERE username=:username");
$stmt->execute(array('username'=>$username));

elog('Deleting user "'.$username.'".');

if ($stmt->fetch(PDO::FETCH_ASSOC)['user_level'] > $_SESSION['user_level']) {
	
	if (($_SESSION['username'] != $username) AND ($username != 'admin')) {
		$stmt = $DB_con->prepare("delete from user where username =:id");
		$stmt->execute(array(':id' => $username));
		elog('Deleted user "'.$username.'".');
		echo 2; //Success
	}
	else
		{
		elog('Deletion error.');
		echo 1; //Deletion of Superadmin and Self destruction not allowed
	}
}
else
	{
		elog('Deletion error. Permission denied.');
		echo 0; //Not Authorised
}
?>
