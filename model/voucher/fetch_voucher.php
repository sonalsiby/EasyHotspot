<?php

    if ( !isset($_SESSION) ) session_start();
    if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
        header('Location: ../controller/logout.php');
    }
    if (!(($_SESSION['user_level']>=1) && ($_SESSION['user_level']<=4) && (isset($_SESSION['id'])))) {
        header('Location: ../controller/logout.php');
    }
    
    $hotspot_id = $_SESSION['host_id'];
    $target = 'templates/'.$hotspot_id.'-voucher.php';

    if (!file_exists($target)) {
        copy('templates/default.php', $target);
    }

    $template = file_get_contents($target);
    echo $template;
?>