<?php 
    if ( !isset($_SESSION) ) session_start();
    if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
        header('Location: logout.php');
    }
    if (!(($_SESSION['user_level']>=1) && ($_SESSION['user_level']<=4) && (isset($_SESSION['id'])))) {
        header('Location: logout.php');
    }
    date_default_timezone_set('Asia/Kolkata');
    if (!function_exists('elog')) {
        include('../controller/_log.php');
    }
    if (isset($_SESSION['demo'])) { 
        elog('Demo user trying to add single user. Creating dummy array.');

        if (isset($_POST['name'])) $username = $_POST['name'];
        if (isset($_POST['psd'])) $password = $_POST['psd'];
        if (isset($_POST['telephone'])) $comment = $_POST['telephone'];
        if (isset($_POST['email'])) $email = $_POST['email'];
        if (isset($_POST['limit_uptime'])) $limit_uptime = $_POST['limit_uptime'];
        if (isset($_POST['limit_bytes'])) $limit_bytes = $_POST['limit_bytes'];
        if (isset($_POST['profile'])) $profile = $_POST['profile'];

        $nestedArray = array();
		$nestedArray['username'] = $username;
		$nestedArray['password'] = $password;
		$nestedArray['profile'] = $profile;
		$nestedArray['email'] = $email;
		$nestedArray['comment'] = $comment;
		$nestedArray['limit-uptime'] = $limit_uptime;
		$nestedArray['limit_bytes_total'] = $limit_bytes_total;
        $data[] = $nestedArray;

        $json_data = array( "data" => $data );
        echo json_encode($json_data);
        
        elog('Echoed Dummy Array.');
    }

    use PEAR2\Net\RouterOS;
    require_once 'PEAR2/Autoload.php';

    $host_id = $_SESSION['host_id'];
    include('../model/db_conn.php');

    date_default_timezone_set($_SESSION['time_zone']);

try {
	$stmt = $DB_con->prepare("SELECT * FROM host WHERE id =:host_id AND status =:status");
	$stmt->execute(array(':host_id' => $host_id, ':status' => 'Active'));
	if ($stmt->rowCount() == 1) {
		$row=$stmt->fetch(PDO::FETCH_ASSOC);
		$host = $row['host'];
		$user = $row['user'];
		$pass = $row['pass'];
	}
}
catch (Exception $e) {
	echo "Error Accessing Data: " . $e->getMessage();
}
    $util = new RouterOS\Util($client = new RouterOS\Client("$host", "$user", "$pass"));

    if (isset($_POST['name'])) $username = $_POST['name'];
    if (isset($_POST['psd'])) $password = $_POST['psd'];
    if (isset($_POST['telephone'])) $comment = $_POST['telephone'];
    if (isset($_POST['email'])) $email = $_POST['email'];
    if (isset($_POST['limit_uptime'])) $limit_uptime = $_POST['limit_uptime'];
    if (isset($_POST['limit_bytes'])) $limit_bytes = $_POST['limit_bytes'];
    if (isset($_POST['profile'])) $profile = $_POST['profile'];

    $comment .= ';'.date('d-m-Y').';'.date('H:i:s').';'.$_SESSION['name'].';';

    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (!(filter_var($email, FILTER_VALIDATE_EMAIL))) $email = "";

    if (empty($password)) $password = $username;
    if (empty($limit_uptime)) { $limit_uptime = 0; }

    elog('Adding single user with username "'.$username.'" and profile "'.$profile.'".');

    $util->setMenu('/ip hotspot user');
    $iv = count($util);

    if ((!empty($username)) and (!empty($password)) and (!empty($profile))) {
        
        if (intval($limit_bytes) != 0) {
            $limit_bytes_total = $limit_bytes;
        } else {
            $limit_bytes_total = 0;
        }

        $nestedArray = array();
		$nestedArray['username'] = $username;
		$nestedArray['password'] = $password;
		$nestedArray['profile'] = $profile;
		$nestedArray['email'] = $email;
		$nestedArray['comment'] = $comment;
		$nestedArray['limit-uptime'] = $limit_uptime;
		$nestedArray['limit_bytes_total'] = $limit_bytes_total;
        $data[] = $nestedArray;
        
        try {
            $util->add(
                array(
                    'name' => "$username",
                    'password' => "$password",
                    'disabled' => "no",
                    'email' => "$email",
                    'limit-uptime' => "$limit_uptime",
                    'limit-bytes-total' => "$limit_bytes_total",
                    'profile' => "$profile",
                    'comment' => "$comment",
                )
            );
        }
        catch (Exception $e) {
            echo -1;
        }

        elog('User addition successful.');
        $json_data = array( "data" => $data );
	    echo json_encode($json_data);
    }

?>
