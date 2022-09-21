<?php
    if ( !isset($_SESSION) ) session_start();
    if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
        header('Location: ../controller/logout.php');
    }
    if (!(($_SESSION['user_level']>=1) && ($_SESSION['user_level']<=4) && (isset($_SESSION['id'])))) {
        header('Location: ../controller/logout.php');
    }
    include('db_conn.php');

    $host_id = $_SESSION['host_id'];
    $search_str = $_POST['str'];

    $stmt = $DB_con->prepare("SELECT id,user_level FROM user WHERE username=:username");
    $stmt->execute(array(':username' => $search_str));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $user_id = $row['id'];
    $user_level = $row['user_level'];

    if ($user_level == 2) {
        $stmt = $DB_con->prepare("SELECT * FROM user_host WHERE user_id=:user_id AND host_id=:host_id");
        $stmt->execute(array(':user_id'=>$user_id, ':host_id'=>$host_id));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (($stmt->rowCount() > 0) && ($row['host_id']==$host_id)) {
            $host = $host_id;
            $response = '1'; // Already assigned to this host
        } else {
            $host = null; // Already assigned to a different host
            $response = '0';
        }
    } else {
        $host = null;
        $response = '-1';
    }

    $data = array();
    $data['response'] = $response;
    $data['user_id'] = $user_id;
    $data['host'] = $host;

    $raw_json[] = $data;
    echo json_encode($raw_json);

?>