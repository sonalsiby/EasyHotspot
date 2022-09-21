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
        elog('Demo user attempting to reset his/her password. Echoed success.');
        echo '1'; return; 
    }

    include('db_conn.php');

    $id = $_SESSION['id'];
    $old = sha1($_POST['old']);
    $new = sha1($_POST['new']);

    elog('Password change attempt by user.');

    $stmt = $DB_con->prepare("UPDATE user SET password=:new WHERE id=:id AND password=:old");
    $stmt->execute(array(':old'=>$old, ':new'=>$new, ':id'=>$id));

    elog($stmt->rowCount().' row(s) affected. Echoed count.');
    echo $stmt->rowCount();
?>