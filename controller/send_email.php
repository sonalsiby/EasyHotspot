<?php
    if ( !isset($_SESSION) ) session_start();
    if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
        header('Location: logout.php');
    }
    if (!(($_SESSION['user_level']>=1) && ($_SESSION['user_level']<=4) && (isset($_SESSION['id'])))) {
        header('Location: logout.php');
    }
    if (!function_exists('elog')) {
        include('_log.php');
    }
    if (isset($_SESSION['demo'])) { 
        elog('Demo user attempting e-mail send. Echoed success.');
        echo '0'; return; 
    }
    
    include('../model/db_conn.php');

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';

    $stmt = $DB_con->prepare("SELECT * FROM config_app WHERE host_id=:host_id");
    $stmt->execute(array(':host_id'=>$_SESSION['host_id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (($row['autosend_email'] != '1') && (!isset($_POST['manual']))) {
        return;
    }

    $stmt = $DB_con->prepare("SELECT * FROM host WHERE id=:host_id");
    $stmt->execute(array(':host_id'=>$_SESSION['host_id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // $company_name = $row['company'];
    $company_email = $row['email'];

    // $stmt = $DB_con->prepare("SELECT * FROM config_email WHERE host_id=:host_id");
    // $stmt->execute(array(':host_id'=>$_SESSION['host_id']));
    // $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // $protocol = $row['protocol'];
    // $auth = $row['smtp_authentication'];
    // $host_name = $row['host_name'];
    // $host_username = $row['username'];
    // $host_password = $row['password'];
    // $security = $row['smtp_security'];
    // $port_no = $row['port_no'];

    $protocol = 'SMTP';
    $auth = 'Yes';
    $host_name = 'mail.zetozone.com';
    $host_username = 'ehsui@zetozone.com';
    $host_password = '@ehsui123';
    $security = 'TLS';
    $port_no = '2525';

    $company_email = $host_username;

    $users = json_decode($_POST['data'], true);
    $user_data = $users['data'];

    $email = $user_data[0]['email'];
    $profile = $user_data[0]['profile'];
    $telephone = $user_data[0]['comment'];

    $groupede = array();
    if (isset($_POST['manual'])) {
        elog('Email [Manual]. Grouping in progress.');
        for ($i=0; $i < count($user_data); $i++) {
            $email = $user_data[$i]['email'];
            if (!array_key_exists($email, $groupede)) {
                $groupede[$email] = array();
                array_push($groupede[$email], $user_data[$i]);
                elog('Group '.($i+1).' -> "'.$email.'"');
            } else {
                array_push($groupede[$email], $user_data[$i]);
            }
        }
    } else {
        elog('Email [Autosend] in progress.');
        $groupede[$email] = $user_data;
    }

    foreach ($groupede as $key => $value) {
        $email = $key;
        $user_data = $value;
        $logm = $email .' -> ';
        $body = '<h3>WiFi Hotspot Service managed by Easy HotSpot</h3>';
        for ($i=0; $i < count($user_data); $i++) {
            $logm .= '"'.$username.'", ';
            $username = $user_data[$i]['username'];
            $password = $user_data[$i]['password'];
            $body .= '
                <hr>
                <strong>Username: </strong><em>'.$username.'</em><br>
                <strong>Password: </strong><em>'.$password.'</em><br>';
        }
        $body .= '<hr>Happy Browsing! <strong> '.$company_name.'</strong>';
        elog($logm);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        if (($_SESSION['user_level'] <= 4) and ($_SESSION['user_level'] >= 1)) {
            $mail = new PHPMailer(true);
            // $mail->SMTPDebug = 2;

            if ($protocol == 'SMTP') {
                // echo 'SMTP';
                $mail->isSMTP();
            }
            if ($auth == 'Yes') {
                // echo 'Yes';
                $mail->SMTPAuth = true;
            }
            if ($security == 'TLS') {
                // echo 'TLS';
                $mail->SMTPSecure = 'tls';
            } else if ($security == 'SSL') {
                // echo 'SSL';
                $mail->SMTPSecure = 'ssl';
            }

            $mail->Host = $host_name;
            $mail->Username = $host_username;
            $mail->Password = $host_password;
            $mail->Port = $port_no;
        
            $mail->From = $company_email;
            $mail->FromName = $company_name;
            $mail->addAddress($email);
            $mail->IsHTML(true);

            $mail->Subject = 'WiFi Hotspot Login details - '.$company_name;
            
            $mail->Body    = $body;
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            
            $mail->Send();
            elog('Mail(s) sent.');
        }
    }

    echo '0';
?>