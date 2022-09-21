<?php
if ( !isset($_SESSION) ) session_start();
if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
	header('Location: ../controller/logout.php');
}
if (!(($_SESSION['user_level']>=1) && ($_SESSION['user_level']<=4) && (isset($_SESSION['id'])))) {
	header('Location: ../controller/logout.php');
}
$hotspot_type = $_POST['type'];
$reference = $_POST['date'];

include('db_conn.php');

if ($_SESSION['user_level'] == 2) {
	$stmt = $DB_con->prepare("SELECT host.id, host.company, host.telephone, host.email, hotspot_name, valid_till FROM host LEFT JOIN user_host ON host.id = user_host.host_id WHERE user_host.user_id =:user_id");
	$stmt->execute(array(':user_id' => $_SESSION['id']));
} else {
    $stmt = $DB_con->prepare("SELECT id, company, telephone, email, hotspot_name, valid_till FROM host");
    $stmt->execute(array());
}

if ($hotspot_type == 'all') {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $nestedData = array();
        foreach ($row as $key => $value) {
            $nestedData[$key] = $value;
        }
        $data[] = $nestedData;
    }
} else {

    if ($hotspot_type == 'expired') {
        $threshold = 0;

    } else if ($hotspot_type == 'expiring') {
        $difference_days = date_diff(date_create($reference),date_create(date('d-m-Y')));
        $validity = $difference_days->format("%R%a");
        $threshold = 0 - $validity;
    }

    for ($i=0; $i < $stmt->rowCount(); $i++) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $valid_till = $row['valid_till'];
        $difference_days = date_diff(date_create($valid_till),date_create(date('d-m-Y')));
        $validity = $difference_days->format("%R%a");
        $validity = 0 - $validity;

        if ($validity <= $threshold) {
            $nestedData = array();
            foreach ($row as $key => $value) {
                $nestedData[$key] = $value;
            }
            $data[] = $nestedData;
        }
    }
}

$json_data = array( "data" => $data );
echo json_encode($json_data);  

?>
