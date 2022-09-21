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
    elog('Attempting to update application settings for host "'.$_SESSION['host_name'].'".');
    $stmt = $DB_con->prepare("SELECT * FROM config_app WHERE host_id=:host_id");
    $stmt->execute(array(':host_id'=>$host_id));

    if ($stmt->rowCount() == 0) {
        elog('Coniguration not found. Creating default application settings for host "'.$_SESSION['host_name'].'" with ID "'.$host_id.'".');
        $stmt = $DB_con->prepare("INSERT INTO config_app (host_id, time_zone, date_format, autosend_sms, autosend_email, voucher_row) 
                            VALUES (:host_id, 'Asia/Kolkata', 'd/m/Y', '0', '0', '3')");
        $stmt->execute(array(':host_id'=>$host_id));
    }

    if (isset($_POST['auto-sms']) && isset($_POST['auto-email'])) {
        $stmt = $DB_con->prepare("UPDATE config_app SET autosend_sms=:auto_sms, autosend_email=:auto_email WHERE host_id=:host_id");
        $stmt->execute(array(':auto_sms'=>$_POST['auto-sms'], ':auto_email'=>$_POST['auto-email'], ':host_id'=>$host_id));
        elog('Updated AUTOSEND_SMS to "'.$_POST['auto-sms'].'" & AUTOSEND_EMAIL to "'.$_POST['auto-email'].'" for host "'.$_SESSION['host_name'].'".');

    } else if (isset($_POST['timezone']) && isset($_POST['date-format']) && isset($_POST['voucher-row'])) {
        $stmt = $DB_con->prepare("UPDATE config_app SET time_zone=:time_zone, date_format=:date_format, voucher_row=:voucher_row WHERE host_id=:host_id");
        $stmt->execute(array(':host_id'=>$host_id, ':time_zone'=>$_POST['timezone'], ':date_format'=>$_POST['date-format'], ':voucher_row'=>$_POST['voucher-row']));
        elog('Updated TIMEZONE to "'.$_POST['timezone'].'" DATE_FORMAT to "'.$_POST['date-format'].'" & VOUCHER_ROW to "'.$_POST['voucher-row'].'".');
    }

    elog('Application settings update success.');
    echo '0';
?>