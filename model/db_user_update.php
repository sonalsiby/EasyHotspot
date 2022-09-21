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
        elog('Demo user attempting to update user details. Echoed success.');
        echo '0'; return;
    }
    
    include('db_conn.php');

    $id = $_POST['id'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $user_level = $_POST['user_level'];
    $status = $_POST['status'];

    elog('Attempting to update details of user "'.$name.'" with ID "'.$id.'" & access level "'.$user_level.'".');

    if ($_SESSION['id']==$id) {
        $_SESSION['name'] = $_POST['name'];
        $_SESSION['address'] = $_POST['address'];
        $_SESSION['phone'] = $_POST['phone'];
    }

    if ($_SESSION['user_level'] != $user_level) {
        elog('Malicious attempt to change user level. Die.');
        echo '0';
    }

    $stmt = $DB_con->prepare("UPDATE user SET name=:name, address=:address, phone=:phone, status=:status, user_level=:user_level WHERE id=:id");
    $stmt->execute(array(':name'=>$name, ':address'=>$address, ':phone'=>$phone, ':status'=>$status, ':user_level'=>$user_level, ':id'=>$id));
    elog('User details updated successfully.');
    echo '0';
?>