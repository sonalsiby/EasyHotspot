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

    $host_id = $_SESSION['host_id'];
    elog('Attempting to update EMAIL & SMS settings for host "'.$_SESSION['host_name'].'" with ID "'.$host_id.'".');

    $api_url = $_POST['api_url'];
    $par_1 = $_POST['param_name1'];
    $par_2 = $_POST['param_name2'];
    $par_3 = $_POST['param_name3'];
    $par_4 = $_POST['param_name4'];
    $par_5 = $_POST['param_name5'];
    $par_6 = $_POST['param_name6'];
    $val_1 = $_POST['param_value1'];
    $val_2 = $_POST['param_value2'];
    $val_3 = $_POST['param_value3'];
    $val_4 = $_POST['param_value4'];
    $val_5 = $_POST['param_value5'];
    $val_6 = $_POST['param_value6'];

    $host_name = $_POST['host_name'];
    $port_no = $_POST['port_no'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $protocol = $_POST['protocol'];
    $security = $_POST['smtp_security'];
    $auth = $_POST['smtp_authentication'];

    $stmt = $DB_con->prepare("SELECT * FROM config_email WHERE host_id=:id");
    $stmt->execute(array(':id'=>$host_id));

    if ($stmt->rowCount() == 0) {
        $stmt = $DB_con->prepare("INSERT INTO config_email (host_id, protocol, host_name, port_no, username, password, 
                            smtp_security, smtp_authentication) VALUES (:host_id, :protocol, :host_name, :port_no, :username, :password, 
                            :smtp_security, :smtp_authentication)");
        $stmt->execute(array(':host_id'=>$host_id, ':protocol'=>$protocol, ':host_name'=>$host_name, ':port_no'=>$port_no, ':username'=>$username, 
                            ':password'=>$password, ':smtp_security'=>$security, ':smtp_authentication'=>$auth));
        elog('No EMAIL configuration found. Resetting to defaults.');
    } else {
        $stmt = $DB_con->prepare("UPDATE config_email SET protocol=:protocol, host_name=:host_name, port_no=:port_no, username=:username, password=:password,
                            smtp_security=:security, smtp_authentication=:auth WHERE host_id=:id");
        $stmt->execute(array(':id'=>$host_id, ':protocol'=>$protocol, ':host_name'=>$host_name, ':port_no'=>$port_no,
                            ':username'=>$username, ':password'=>$password, ':security'=>$security, ':auth'=>$auth));
        elog('EMAIL configuration found. Updated values');
    }

    $stmt = $DB_con->prepare("SELECT * FROM config_sms WHERE host_id=:id");
    $stmt->execute(array(':id'=>$host_id));

    if ($stmt->rowCount() == 0) {
        $stmt = $DB_con->prepare("INSERT INTO config_sms (host_id, api_url, param_name1, param_name2, param_name3, param_name4,
                            param_name5, param_name6, param_value1, param_value2, param_value3, param_value4, param_value5, 
                            param_value6) VALUES (:host_id, :api_url, :param_name1, :param_name2, :param_name3,:param_name4, 
                            :param_name5, :param_name6, :param_value1, :param_value2, :param_value3, :param_value4, :param_value5, 
                            :param_value6)");
        $stmt->execute(array(':host_id'=>$host_id, ':api_url'=>$api_url, ':param_name1'=>$par_1, ':param_name2'=>$par_2, ':param_name3'=>$par_3,
                            ':param_name4'=>$par_4, ':param_name5'=>$par_5, ':param_name6'=>$par_6, ':param_value1'=>$val_1, ':param_value2'=>$val_2,
                            ':param_value3'=>$val_3, ':param_value4'=>$val_4, ':param_value5'=>$val_5, ':param_value6'=>$val_6));
        elog('No SMS configuration found. Resetting to defaults.');
    } else {
        $stmt = $DB_con->prepare("UPDATE config_sms SET api_url=:api_url, param_name1=:param_name1, param_name2=:param_name2, param_name3=:param_name3,
                            param_name4=:param_name4, param_name5=:param_name5, param_name6=:param_name6, param_value1=:param_value1, param_value2=:param_value2,
                            param_value3=:param_value3, param_value4=:param_value4, param_value5=:param_value5, param_value6=:param_value6 WHERE host_id=:host_id");
        $stmt->execute(array(':host_id'=>$host_id, ':api_url'=>$api_url, ':param_name1'=>$par_1, ':param_name2'=>$par_2, ':param_name3'=>$par_3,
                            ':param_name4'=>$par_4, ':param_name5'=>$par_5, ':param_name6'=>$par_6, ':param_value1'=>$val_1, ':param_value2'=>$val_2,
                            ':param_value3'=>$val_3, ':param_value4'=>$val_4, ':param_value5'=>$val_5, ':param_value6'=>$val_6));
        elog('SMS configuration found. Updated values');
    }

    echo '0';
?>