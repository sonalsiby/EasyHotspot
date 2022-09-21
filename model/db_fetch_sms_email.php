<?php
    if ( !isset($_SESSION) ) session_start();
    if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
        header('Location: ../controller/logout.php');
    }
    if (!(($_SESSION['user_level']>=1) && ($_SESSION['user_level']<=4) && (isset($_SESSION['id'])))) {
        header('Location: ../controller/logout.php');
    }
    
    $host_id = $_SESSION['host_id'];
    include('db_conn.php');

    $stmt = $DB_con->prepare("SELECT * FROM config_email WHERE host_id=:id");
    $stmt->execute(array(':id'=>$host_id));

    if ($stmt->rowCount() == 0) {
        $stmt = $DB_con->prepare("SELECT * FROM config_email WHERE host_id=0");
        $stmt->execute(array());
    }
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $nestedData = array();
    foreach ($row as $key => $value) {
        $nestedData[$key] = $value;
    }
    $data[] = $nestedData;

    $stmt = $DB_con->prepare("SELECT * FROM config_sms WHERE host_id=:id");
    $stmt->execute(array(':id'=>$host_id));

    if ($stmt->rowCount() == 0) {
        $stmt = $DB_con->prepare("SELECT * FROM config_sms WHERE host_id=0");
        $stmt->execute(array());
    }
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $nestedData = array();
    foreach ($row as $key => $value) {
        $nestedData[$key] = $value;
    }
    $data[] = $nestedData;

    $json_data = array( "data" => $data );
    echo json_encode($json_data);

?>