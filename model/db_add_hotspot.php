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
    $validity = $_POST['validity'];

    $actve = $_POST['active'];
    $status = ($active==1 ? 'Active' : 'Inactive');

    elog('Adding hotspot "'.$hotspot_name.'" for "'.$company_name.'" at "'.$hotspot_ip.'" ['.$hotspot_host.'] and STATUS : "'.$status.'".');
    elog('Validity -> "'.$validity.'" Username -> "'.$hotspot_username.'" Password -> "'.$hotspot_password.'".');

    if ($_SESSION['user_level'] == 1) {

        $stmt = $DB_con->prepare("INSERT INTO host (company, address, contact_person, telephone, email, host_ip, status, hotspot_name, host, user, pass, valid_till, created_on) 
                                VALUES (:company, :address, :contact_person, :telephone, :email, :host_ip, :status, :hotspot_name, :host, :user, :pass, :valid_till, 
                                NOW())");
        $stmt->execute(array(':company'=>$company_name, ':address'=>$company_address, ':contact_person'=>$company_contact, ':telephone'=>$company_phone,
                                ':email'=>$company_email, ':host_ip'=>$hotspot_ip, ':status'=>$status, ':host'=>$hotspot_host, ':user'=>$hotspot_username,
                                ':pass'=>$hotspot_password, ':valid_till'=>$validity, ':hotspot_name'=>$hotspot_name));
        elog('Successfully added host "'.$hotspot_name.'".');
        echo '0';
    } else {
        elog('Error adding hotspot');
        echo '1';
    }

?>