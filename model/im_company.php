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
    elog('Demo user attempting to change company image. Echoed success.');
    echo '0'; return; 
}

$filename = $_FILES['ecompany-file']['name'];
$location = "../userdata/img/".$filename;

elog('Attemtping company image change for "'.$_SESSION['host_name'].'".');

$target = "../userdata/img/".$_SESSION['host_id']."-company.jpg";
if (move_uploaded_file($_FILES['ecompany-file']['tmp_name'], $target)) {
    elog('Image change success');
    echo '0';
} else {
    elog('Image change failed');
    echo '1';
}
?>