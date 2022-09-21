<?php
//Start session
if ( !isset($_SESSION) ) session_start();
// Check whether the session variables present or not, and assign them to Ordinary variables, if present.
if (!isset($_SESSION['user_level']) || (trim($_SESSION['user_level']) == '' || (trim($_SESSION['user_level']) > 4))) {
    header("location:login.php");
    exit();
}
// if ( !isset($_SESSION) ) header("location:login.php");
?>
