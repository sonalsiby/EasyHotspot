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
        elog('Demo user attempting SMS send. Echoed success.');
        echo '0'; return; 
    }

    include('../model/db_conn.php');

    $stmt = $DB_con->prepare("SELECT * FROM config_app WHERE host_id=:host_id");
    $stmt->execute(array(':host_id'=>$_SESSION['host_id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (($row['autosend_sms'] != '1') && (!isset($_POST['manual']))) {
        return;
    }

    $stmt = $DB_con->prepare("SELECT * FROM config_sms WHERE host_id=:host_id");
    $stmt->execute(array(':host_id'=>$_SESSION['host_id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $sms_api_url = $row['api_url'];
    $sms_param_name1 = $row['param_name1'];
    $sms_param_name2 = $row['param_name2'];
    $sms_param_name3 = $row['param_name3'];
    $sms_param_name4 = $row['param_name4'];
    $sms_param_name5 = $row['param_name5'];
    $sms_param_name6 = $row['param_name6'];
    $sms_param_value1 = $row['param_value1'];
    $sms_param_value2 = $row['param_value2'];
    $sms_param_value3 = $row['param_value3'];
    $sms_param_value4 = $row['param_value4'];
    $sms_param_value5 = $row['param_value5'];
    $sms_param_value6 = $row['param_value6'];

    $stmt = $DB_con->prepare("SELECT * FROM host WHERE id=:host_id");
    $stmt->execute(array(':host_id'=>$_SESSION['host_id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $company_name = $row['company'];

    $users = json_decode($_POST['data'], true);
    $user_data = $users['data'];

    $email = $user_data[0]['email'];
	$profile = $user_data[0]['profile'];
    $telephone = $user_data[0]['comment'];

    $groupeds = array();
    if (isset($_POST['manual'])) {
        elog('SMS [Manual]. Grouping in progress.');
        for ($i=0; $i < count($user_data); $i++) {
            $telephone = $user_data[$i]['comment'];
            if (!array_key_exists($telephone, $groupeds)) {
                $groupeds[$telephone] = array();
                array_push($groupeds[$telephone], $user_data[$i]);
                elog('Group '.($i+1).' -> "'.$telephone.'"');
            } else {
                array_push($groupeds[$telephone], $user_data[$i]);
            }
        }
    } else {
        elog('SMS [Autosend] in progress.');
        $groupeds[$telephone] = $user_data;
    }

    foreach ($groupeds as $key => $value) {
        $telephone = $key;
        $user_data = $value;
        $logm = $telephone .' -> ';
        $message = "Dear customer, Thank you for availing our WiFi Hotspot services, managed by Easy HotSpot. Login using (Username, Password):\n";
        for ($i=0; $i < count($user_data); $i++) {
            $logm .= '"'.$username.'", ';
            $username = $user_data[$i]['username'];
            $password = $user_data[$i]['password'];
            $message .= ($i+1)."(".$username.", ".$password.")\n";
        }
        $message .= "Usage Profile: ".$profile.". Happy browsing!\n[".$company_name."]";
        $message = urlencode($message);
        elog($logm);
            
        $curl_url = trim($sms_api_url);
        $curl_url .= '?'.trim($sms_param_name1).'='.trim($message);
        $curl_url .= '&'.trim($sms_param_name2).'='.trim($telephone);			
        $curl_url .= '&'.trim($sms_param_name3).'='.trim($sms_param_value3);
        $curl_url .= '&'.trim($sms_param_name4).'='.trim($sms_param_value4);			
        $curl_url .= '&'.trim($sms_param_name5).'='.trim($sms_param_value5);
        $curl_url .= '&'.trim($sms_param_name6).'='.trim($sms_param_value6);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $curl_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Cache-Control: no-cache"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        elog('SMS(s) sent.');
    }
    echo '0';
?>