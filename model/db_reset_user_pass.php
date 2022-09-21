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
        elog('Demo user attempting to reset somebody\'s password. Echoed success.');
        echo '0'; return; 
    }
    
    include('db_conn.php');
    $id = $_POST['id'];

    elog('Attempting to reset password of user with ID "'.$id.'".');
    $stmt = $DB_con->prepare("UPDATE user SET password=:password WHERE id=:id");
    $stmt->execute(array(':password'=>sha1('password'), ':id'=>$id));

    elog('Password successfully reset.');
    echo '0';
?>