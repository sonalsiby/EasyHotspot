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
    $hotspot_id = $_POST['hotspot_id'];

    elog('Attempting to delete hotspot with ID -> "'.$hotspot_id.'".');

    if ($_SESSION['user_level'] == 1) {
        $stmt = $DB_con->prepare("DELETE FROM host WHERE id=:id");
        $stmt->execute(array(':id'=>$hotspot_id));
        echo '0';
        elog('Deleted hotspot with ID -> "'.$hotspot_id.'".');
    }

    elog('Permission Denied. Malicious activity by user.');
?>