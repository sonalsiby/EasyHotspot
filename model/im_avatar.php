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
if (isset($_SESSION['demo'])) { 
    elog('Demo user attempting to change avatar. Echoed success.');
    echo '0'; return; 
}

$filename = $_FILES['eavatar-file']['name'];
$location = "../userdata/img/".$filename;

elog('Attemtping avatar change.');

$target = "../userdata/img/".$_SESSION['id']."-avatar.jpg";
if (move_uploaded_file($_FILES['eavatar-file']['tmp_name'], $target)) {
    elog('Avatar updated.');
    echo '0';
} else {
    elog('Avatar update error.');
    echo '1';
}
?>