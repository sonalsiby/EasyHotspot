<?php
if ( !isset($_SESSION) ) session_start();
if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
	header('Location: logout.php');
}
if (!(($_SESSION['user_level']>=1) && ($_SESSION['user_level']<=4) && (isset($_SESSION['id'])))) {
	header('Location: logout.php');
}
date_default_timezone_set('Asia/Kolkata');
if (!function_exists('elog')) {
    include('_log.php');
}
if (isset($_SESSION['demo'])) { 
	elog('Demo user trying to add multiple users. Creating dummy array.');
}

use PEAR2\Net\RouterOS;
require_once 'PEAR2/Autoload.php';
$host_id = $_SESSION['host_id'];
include('../model/db_conn.php');

date_default_timezone_set($_SESSION['time_zone']);

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

if (isset($_POST['no_of_users'])) $no_of_users = $_POST['no_of_users'];
if (isset($_POST['pass_length'])) $passLength = $_POST['pass_length'];
if (isset($_POST['user_prefix'])) $user_prefix = $_POST['user_prefix'];
if (isset($_POST['limit_uptime'])) $limit_uptime = $_POST['limit_uptime'];
if (isset($_POST['limit_bytes'])) $limit_bytes = $_POST['limit_bytes'];
if (isset($_POST['profile'])) $profile = $_POST['profile'];
if (isset($_POST['same_pass'])) $same_pass = $_POST['same_pass'];
if (isset($_POST['pass_type'])) $pass_type = $_POST['pass_type'];
if (isset($_POST['telephone'])) $comment = $_POST['telephone'];
if (isset($_POST['email'])) $email = $_POST['email'];

$email = filter_var($email, FILTER_SANITIZE_EMAIL);
if (!(filter_var($email, FILTER_VALIDATE_EMAIL))) $email = "";
if (empty($limit_uptime)) { $limit_uptime = 0; }

$comment .= ';'.date('d-m-Y').';'.date('H:i:s').';'.$_SESSION['name'].';';

elog('Adding '.$no_of_users.' users with prefix "'.$user_prefix.'" and profile "'.$profile.'".');

$util->setMenu('/ip hotspot user');

switch ($pass_type) {
	case "s":
		$passAlphabet = "abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz";
		$user_prefix = strtolower($user_prefix);
		break;
	case "c":
		$passAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$user_prefix = strtoupper($user_prefix);
		break;
	case "n":
		$passAlphabet = "123456789123456789123456789123456789123456789123456789";
		break;
	case "sc":
		$passAlphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		break;
	case "sn":
		$passAlphabet = "abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz123456789123456789123456789";
		$user_prefix = strtolower($user_prefix);
		break;
	case "cn":
		$passAlphabet = "123456789123456789123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789123456789123456789";
		$user_prefix = strtoupper($user_prefix);
		break;
	case "scn":
		$passAlphabet = "abcdefghijklmnopqrstuvwxyz123456789123456789123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789";
		break;
}

$passAlphabetLimit = strlen($passAlphabet)-1;
	
if($_SESSION['user_level'] >= 1 and $_SESSION['user_level'] <= 4) {		

	for($i=0; $i < $no_of_users; $i++){
		//$passAlphabet = 'abcdefghikmnpqrstuvxyz23456789';
		//$passAlphabetLimit = strlen($passAlphabet)-1;
		$pass = '';
		$uid = '';
		//Password generation
		for ($j = 0; $j < $passLength; ++$j) {
			$pass .= $passAlphabet[mt_rand(0, $passAlphabetLimit)];
		}
		$pass = str_shuffle($pass);
		//Username generation
		for ($j = 0; $j < $passLength; ++$j) {
			$uid .= $passAlphabet[mt_rand(0, $passAlphabetLimit)];
		}
		//Adding prefix to username
		$username = $user_prefix.$uid;
		
		//username & password same or different
		if ($same_pass == 2) {	$password = $pass; } else { $password = $username; }
		
		$limit_bytes_total = $limit_bytes;
		
		$nestedArray = array();
		$nestedArray['username'] = $username;
		$nestedArray['password'] = $password;
		$nestedArray['profile'] = $profile;
		$nestedArray['email'] = $email;
		$nestedArray['comment'] = $comment;
		$nestedArray['limit-uptime'] = $limit_uptime;
		$nestedArray['limit_bytes_total'] = $limit_bytes_total;
		$data[] = $nestedArray;
		
		if (!isset($_SESSION['demo'])) { 
			
			try {
				$util->add(
					array(
						'name' => "$username",
						'password' => "$password",
						'disabled' => "no",
						'email' => "$email",
						'limit-uptime' => "$limit_uptime",
						'limit-bytes-total' => "$limit_bytes_total",
						'profile' => "$profile",
						'comment' => "$comment",
					)
				);
			}
			catch (Exception $e) {
				// echo -1;
			}
		}		
	}
	$json_data = array( "data" => $data );
	if (isset($_SESSION['demo'])) {
		elog('Dummy array echoed.');
	} else {
		elog('User addition successful.');
	}
	echo json_encode($json_data);
}
else
	{
	elog('Not Authorized');
	echo -1; // Not an Authorised User
}
?>
