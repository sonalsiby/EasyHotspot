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
	elog('Demo user attempting to add system user. Echoed success.');
	echo '2'; return; 
}

$username = trim(strtolower($_POST['username']));
$fullname = $_POST['fullname'];
$address = $_POST['address'];
$phone = $_POST['phone'];
$user_level = $_POST['user_level'];
$status = $_POST['status'];
$password = sha1('password');

elog('Adding system user "'.$username.'" with name "'.$fullname.'" and access level "'.$user_level.'". STATUS: "'.$status.'".');
if (($_SESSION['user_level'] >= 1) AND ($_SESSION['user_level'] <= 3) AND (filter_var($username, FILTER_VALIDATE_EMAIL))) { 
	include('../model/db_conn.php');

	$stmt = $DB_con->prepare("SELECT * FROM user WHERE username =:username");
	$stmt->execute(array(':username' => $username));
	$count = $stmt->rowCount();
	
	if ($count != 0) {
		elog('System user addition failed. Username exists.');
		echo '1'; //Username already exist
		}
	else
		{
		$stmt = $DB_con->prepare("insert into user (username, password, created_on, name, address, avatar, phone, email, user_level, status, host_id)
			values(:username, :password, NOW(), :fullname, :address, :avatar, :phone, :email, :user_level, :status, :host_id)");
		$stmt->execute(array(':username' => $username, ':password' => $password, ':fullname' => $fullname,
			':address' => $address, ':avatar' => 'avatar.jpg', ':phone' => $phone, ':email' => $username,
			 ':user_level' => $user_level, ':status' => $status, ':host_id' => $_SESSION['host_id']));
		$new_id = $DB_con->lastInsertId();
		$stmt = $DB_con->prepare("insert into user_host (user_id, host_id, status) values(:user_id, :host_id, :status)");
		$stmt->execute(array(':user_id' => $new_id, ':host_id' => $_SESSION['host_id'], ':status' => 'Active'));
		echo '2'; //Successfully added new user with password 'password'
		elog('Successfully created user "'.$username.'".');
	}

}
else
 	{
	elog('User addition failed. Permission denied.');
	echo '0'; //Not Authorised or NOT a valid username
}
?>
