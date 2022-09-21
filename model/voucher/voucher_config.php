<?php
if ( !isset($_SESSION) ) session_start();
if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
    header('Location: ../controller/logout.php');
}
if (!(($_SESSION['user_level']>=1) && ($_SESSION['user_level']<=4) && (isset($_SESSION['id'])))) {
    header('Location: ../controller/logout.php');
}

$hotspot_id = $_SESSION['host_id'];
include('../db_conn.php');

$stmt = $DB_con->prepare('SELECT company, address, telephone, email FROM host WHERE id=:host_id');
$stmt->execute(array(':host_id'=>$hotspot_id));
$row  = $stmt->fetch(PDO::FETCH_ASSOC);
$company_name = $row['company'];
$company_address = $row['address'];
$company_phone = $row['telephone'];
$company_email = $row['email'];

?>
<style>
    .card-deck {
        margin: 10px;
    }
    #evl-logo {
        width: 30px;
        height: 30px;
        margin: 3px;
    }

    .evl-address {
        text-align: center;
        font-size: 0.7em
    }

    .evl-company {
        font-size: 1em;
        text-align: center;
    }

    .evl-title {
        font-size: 0.9em;
        text-align: center;
        font-weight: bold;
    }

    .evl-header {
        text-align: center;
    }
</style>

<?php

    $fl_name = 'templates/'.$hotspot_id.'-voucher.php';
    $voucher_logo = '../../../../userdata/img/'.$_SESSION['host_id'].'-company.jpg';

    if (isset($_POST['preview'])) {
        $username = 'use25l';
        $password = 'upa5lw';
        $profile = '4 Days';
        include 'tmp/temp.php';
    } else if ($_POST['type'] == 'voucher') {
        $stmt = $DB_con->prepare('SELECT voucher_row FROM config_app WHERE host_id=:host_id');
        $stmt->execute(array(':host_id'=>$hotspot_id));
        $row_count = $stmt->fetch(PDO::FETCH_ASSOC)['voucher_row'];
        
        echo '<link rel="stylesheet" href="../../../../assets/vendor/bootstrap/css/bootstrap.css">';
        $users = json_decode($_POST['users'], true);
        $user_data = $users['data'];

        $i = 0;
        while ($i < count($user_data)) {
            $j = $i;
            $exit = false;
            echo '<div class="card-deck">';
            while (($j < ($i + $row_count)) && ($j <= count($user_data)))  {
                if ($j == count($user_data)) {
                    while ($j < ($i + $row_count)) {
                        echo'<div class="card" style="border-style:none;background-color:#fff;">
                            <div class="card-body">
                                <div class="card-title"></div>
                                <div class="card-text text-center align-self-center"></div>
                            </div>
                        </div>';
                        $j++;
                    }
                    $exit = true;
                    break;
                }
                $username = $user_data[$j]['username'];
                $password = $user_data[$j]['password'];
                $profile = $user_data[$j]['profile'];
                include $fl_name;
                $j++;
            }
            echo '</div>';
            $i = $j;
            if ($exit) {
                echo'
                <script type="text/javascript" src="../../../../assets/vendor/jquery/jquery.js"></script>
                <script type="text/javascript" src="../../../../assets/vendor/bootstrap/js/bootstrap.js"></script>

                <script>
                    $(document).ready(function() {
                        window.print();
                    });
                </script>';
                break;
            }

        }
    } else {
        echo '<link rel="stylesheet" href="../../../../assets/vendor/bootstrap/css/bootstrap.css">';
        $users = json_decode($_POST['users'], true);
        $user_data = $users['data'];

        echo '
        <div class="evl-header">
            <div>
                <img id="evl-logo" src="'.$voucher_logo.'">
            </div>
            <div>
                <span class="evl-company"><strong>'.$company_name.'</strong></span>
            </div>
        </div>
        <div class="evl-address">
            '.$company_address.'<br>
            Phone: '.$company_phone.', E-mail: '.$company_email.'
        </div>
        <div class="evl-title">
            <span>WiFi Hotspot Users List</span>
        </div>';

        if ($_POST['type'] == 'list-1') {
            echo '
            <table class="table table-bordered">
                <caption>Profile: '.$profile = $user_data[0]['profile'].'</caption>
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Username</th>
                        <th scope="col">Password</th>
                        <th scope="col">Profile</th>
                    </tr>
                </thead>
                <tbody>';
            $i = 0;
            while ($i < count($user_data)) {
                $username = $user_data[$i]['username'];
                $password = $user_data[$i]['password'];
                $profile = $user_data[$i]['profile'];

                echo'<tr>
                    <th scope="row"><span style="font-size:18px;font-weight:bold">'.($i+1).'</span></th>
                    <td><span style="font-size:18px;font-weight:bold">'.$username.'</span></td>
                    <td><span style="font-size:18px;font-weight:bold">'.$password.'</span></td>
                    <td><span style="font-size:18px;font-weight:bold">'.$profile.'</span></td>
                </tr>';
                $i++;
            }
        } else if ($_POST['type'] == 'list-2') {
            echo '
            <table class="table table-bordered">
                <caption>Profile: '.$profile = $user_data[0]['profile'].'</caption>
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Username</th>
                        <th scope="col">Password</th>
                        <th scope="col">#</th>
                        <th scope="col">Username</th>
                        <th scope="col">Password</th>
                    </tr>
                </thead>
                <tbody>';
            $i = 0;
            while ($i < count($user_data)) {
                echo'<tr>
                    <td><span style="font-size:18px;font-weight:bold">'.($i+1).'</td>
                    <td><span style="font-size:18px;font-weight:bold">'.$user_data[$i]['username'].'</span></td>
                    <td><span style="font-size:18px;font-weight:bold">'.$user_data[$i]['password'].'</span></td>
                    <td><span style="font-size:18px;font-weight:bold">'.($i+2).'</td>
                    <td><span style="font-size:18px;font-weight:bold">'.$user_data[$i+1]['username'].'</span></td>
                    <td><span style="font-size:18px;font-weight:bold">'.$user_data[$i+1]['password'].'</span></td>
                </tr>';
                $i += 2;
            }
        }
        echo '</tbody>
        </table>

        <script type="text/javascript" src="../../../../assets/vendor/jquery/jquery.js"></script>
        <script type="text/javascript" src="../../../../assets/vendor/bootstrap/js/bootstrap.js"></script>

        <script>
            $(document).ready(function() {
                window.print();
            });
        </script>';
    }
?>

