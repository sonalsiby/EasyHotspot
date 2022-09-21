<?php
    if ( !isset($_SESSION) ) session_start();
    if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
        header('Location: ../controller/logout.php');
    }
    if (!(($_SESSION['user_level']>=1) && ($_SESSION['user_level']<=4) && (isset($_SESSION['id'])))) {
        header('Location: ../controller/logout.php');
    }
    if (isset($_SESSION['demo'])) { echo '0'; return; }
    
    $hotspot_id = $_SESSION['host_id'];
    $data = $_POST['data'];
    if (isset($_POST['save'])) {
        $filename = 'templates/'.$hotspot_id.'-voucher.php';
        file_put_contents($filename, $data);
    } else {
        $filename = $_POST['filename'];
        file_put_contents('tmp/'.$filename, $data);
    }
    
    echo '0';
?>