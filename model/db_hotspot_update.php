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

    $company_name = $_POST['company_name'];
    $company_address = $_POST['company_address'];
    $company_contact = $_POST['company_contact'];
    $company_email = $_POST['company_email'];
    $company_phone = $_POST['company_phone'];
    $hotspot_name = $_POST['hotspot_name'];
    $hotspot_ip = $_POST['hotspot_ip'];
    $hotspot_host = $_POST['hotspot_host'];
    $hotspot_username = $_POST['hotspot_username'];
    $hotspot_password = $_POST['hotspot_password'];

    elog('Attempting to edit/update hotspot "'.$hotspot_name.'".');
    elog('Values -> Router "'.$hotspot_name.'" of "'.$company_name.'" at "'.$hotspot_ip.'" ['.$hotspot_host.'] and STATUS : "'.$status.'".');
    elog('Validity -> "'.$validity.'" Username -> "'.$hotspot_username.'" Password -> "'.$hotspot_password.'".');
    elog('Not logging trivial values [e-mail, phone, address, contact_person...]');

    if ($_SESSION['user_level'] == 1) {

        $stmt = $DB_con->prepare("UPDATE host SET company=:company, address=:address, contact_person=:contact_person, telephone=:telephone, email=:email, 
                                host_ip=:host_ip, host=:host, hotspot_name=:hotspot_name, user=:user, pass=:pass WHERE id=:host_id");
        $stmt->execute(array(':company'=>$company_name, ':address'=>$company_address, ':contact_person'=>$company_contact, ':telephone'=>$company_phone,
                                ':email'=>$company_email, ':host_ip'=>$hotspot_ip, ':host'=>$hotspot_host, ':user'=>$hotspot_username,
                                ':pass'=>$hotspot_password, ':host_id'=>$_SESSION['host_id'], ':hotspot_name'=>$hotspot_name));
        elog('Updated hotspot "'.$hotspot_name.'".');
        echo '0';

    } else {
        elog('Error in updating hotspot.');
        echo '1';
    }
?>