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

    if ($_SESSION['user_level'] <= 2) {
        $host_id = $_SESSION['host_id'];
        $date = date_create($_POST['date']);
        
        elog('Attempting to update validity for host "'.$_SESSION['host_name'].'" with ID "'.$host_id);
        $stmt = $DB_con->prepare("UPDATE host SET valid_till=:validity WHERE id=:host_id");
        $stmt->execute(array(':validity'=>date_format($date,"Y/m/d H:i:s"), ':host_id'=>$host_id));
        elog('Validity update successful.');
        
        echo '0';
    } else {
        elog('Validity update error. Permission denied.');
    }
?>