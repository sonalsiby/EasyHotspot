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
        elog('Demo user trying to change status of something. Echoed success.');
        echo '0'; return; 
    }
    include('db_conn.php');
    $file = $_POST['file'];
    $id = $_POST['id'];
    $status = $_POST['status'];

    $stmt = $DB_con->prepare("UPDATE $file SET status=:status WHERE id=:id");
    $stmt->execute(array(':status'=>$status, ':id'=>$id));

    elog('Status Change: TABLE -> "'.$file.'" & ID -> "'.$id.'" & TO -> "'.$status.'".');
    echo '0';
?>