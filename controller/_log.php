<?php
    if (!isset($_SESSION)) {
        session_start();
    } else {
        if (isset($_SESSION['time_zone'])) {
            date_default_timezone_set($_SESSION['time_zone']);
        }
    }

    function elog($message, $opt=null) {
        $root = $_SERVER['DOCUMENT_ROOT'].'/logs/';
        $directory = $root.date('F Y');
        $referer = $_SERVER['HTTP_REFERER'];

        if (!isset($_SESSION['host_name'])) {
            $directory .= '/General';
        } else {
            $directory .= '/'.$_SESSION['host_name'];
            $referer = 'USER: '.$_SESSION['username'].' ID: '.$_SESSION['id']. ' HOST: '.$_SESSION['host_name'];
        }

        $filename = $directory.'/'.date('D d-M-Y').'.log';

        if (!(file_exists($directory) && is_dir($directory))) {
            mkdir($directory, 0777, true);
        }

        $handle = fopen($filename, 'a+');
        $line = '[ ' . date('d-M-Y') . ' ' . date('H:i:s') . ' ] { ' . $referer . ' | '.$opt.' } -> ' . $message."\n";
        fwrite($handle, $line);
        fclose($handle); 
    }

    if (isset($_POST['message'])) {
        elog($_POST['message'], $_POST['optional']);
    }
?>