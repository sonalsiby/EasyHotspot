<?php
if (!function_exists('elog')) {
    include('_log.php');
}
session_start();
session_unset(); 
session_destroy();
elog('Logging out. Session destroyed.');
header('location:../login.php');
?>