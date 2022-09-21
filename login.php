<?php
if ( !isset($_SESSION) ) session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Kolkata');
include('controller/_log.php');

if (isset($_POST['btnLogin'])){
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);

	elog('User "'.$username.'" attempting to login.');

	if ((strtolower($username) == 'manager') OR (strtolower($username) == 'executive')) { $username = strtolower($username); $password = strtolower($password); }
	$password = sha1($password);
	include('model/db_conn.php');
	$stmt = $DB_con->prepare("SELECT id FROM user WHERE 1");
	$stmt->execute(array());
	$count = $stmt->rowCount();
	if( $count == 0 ) {
		$password = sha1('admin');
		$stmt = $DB_con->prepare("insert into `user` (username, password, created_on, name, address, avatar, phone, email, status, user_level)
			values ('admin', '$password', NOW(), 'Administrator', 'Easy Hotspot', 'admin.jpg','+91 9020 150 150', 'sibyperiyar@gmail.com', 'Active', 1)");
		$stmt->execute(array());

		$password = sha1('manager');
		$stmt = $DB_con->prepare("insert into `user` (username, password, created_on, name, address, avatar, phone, email, status, user_level, host_id)
			values ('manager', '$password', NOW(), 'Demo Manager', 'Easy Hotspot', 'admin.jpg','+91 9020 150 150', 'demomanager@gmail.com', 'Active', 3, 1)");
		$stmt->execute(array());

		$password = sha1('executive');
		$stmt = $DB_con->prepare("insert into `user` (username, password, created_on, name, address, avatar, phone, email, status, user_level, host_id)
			values ('executive', '$password', NOW(), 'Demo Executive', 'Easy Hotspot', 'admin.jpg','+91 9020 150 150', 'demoexecutive@gmail.com', 'Active', 4, 1)");
		$stmt->execute(array());

		$stmt = $DB_con->prepare("SELECT id FROM host WHERE 1");
		$stmt->execute(array());
		$count = $stmt->rowCount();
		if( $count == 0 ) {
			$stmt = $DB_con->prepare("INSERT INTO host (company, address, contact_person, telephone, email, host_ip, status, hotspot_name, host, user, pass, valid_till, created_on) 
                                VALUES (:company, :address, :contact_person, :telephone, :email, :host_ip, :status, :hotspot_name, :host, :user, :pass, :valid_till, 
                                NOW())");
        	$stmt->execute(array(':company'=>'Demo Company', ':address'=>'Eazy Hotspot Company Location', ':contact_person'=>'Administrator', ':telephone'=>'+91 815 707 6279',
                                ':email'=>'demo@eazyhotspot.in', ':host_ip'=>'45.123.0.41', ':status'=>'Active', ':host'=>'45.123.0.41', ':user'=>'zetozone',
		                        ':pass'=>'zeto111', ':valid_till'=>date('Y-m-d',strtotime('2022/1/1')), ':hotspot_name'=>'Easy Hotspot'));
		}
	}
	try
		{
		$stmt = $DB_con->prepare("SELECT * FROM user WHERE username=:username AND password =:password AND status =:status");
		$stmt->execute(array(':username' => strtolower($username), ':password' => strtolower($password), ':status' => 'Active'));
		$count = $stmt->rowCount();
	}
	catch(PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
	if( $count == 1 ) {
		elog('Login success for user "'.$username.'".');
		$row=$stmt->fetch(PDO::FETCH_ASSOC);
		//session_destroy();
		if ( !isset($_SESSION) ) session_start();
		$_SESSION['id']=$row['id'];
		$_SESSION['username']=$row['username'];
		$_SESSION['name']= $row['name'];
		$_SESSION['user_level']= $row['user_level'];
		$_SESSION['avatar']= $row['avatar'];
		$_SESSION['host_id']= $row['host_id'];
		$_SESSION['address'] = $row['address'];
		$_SESSION['phone'] = $row['phone'];
		$_SESSION['status'] = $row['status'];
			
		if ($_SESSION['user_level'] == 1 OR $_SESSION['user_level'] == 2) {
			elog('User "'.$username.'" is an admin. Redirect to admin panel.');
			echo '<script language="javascript">window.location.href = "admin.php"</script>';
			exit();
		}
		else {	
			elog('User "'.$username.'" is not an admin. Redirect to hotspot.');
			echo '<script language="javascript">window.location.href = "index.php"</script>';
			exit();
		}
	} else {
		elog('Login denied for user "'.$username.'". Invalid password or user inactive.');
		$err = 1;
	}
}
?>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Easy Hotspot Login">
        <meta name="author" content="Sonal Siby">
        <title>Easy Hotspot Login</title>

		<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/css/login.css" rel="stylesheet">
        <link rel="icon" href="assets/img/logo.png">
    </head>
    <body class="text-center" style="display:none;">
        <form class="form-signin" method="post">
            <img class="mb-4" src="assets/img/logo.png" alt="" width="72" height="72">
            <h1 class="h3 mb-3 font-weight-normal">Easy Hotspot Login</h1>

            <label for="username" class="sr-only">Email address</label>
            <input type="text" name="username" id="username" class="form-control" placeholder="Username" required autofocus>

            <label for="password" class="sr-only">Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>

            <div class="checkbox mb-3">
                <label>
                    <input type="checkbox" value="remember-me"> Remember me 
                </label>
            </div>
			<?php if (isset($err)) echo '<label class="text-danger text-sm"><small>No active user with given credentials. Please try again or contact admin if poblem persists.</small></label>'; ?>
            <button name="btnLogin" class="btn btn-outline-primary btn-block btn-sm" type="submit">Sign in</button>
            <p class="mt-5 mb-3 text-muted"><small>&copy; 2019 Zetozone Technologies. <br>Photo By <a href="https://unsplash.com/photos/rXXz5lSvD4k">Johannes Plenio</a></small></p>
        </form>
	</body>
	<!-- <script src="assets/vendor/jquery/jquery.js"></script> -->
	<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
	<script type="text/javascript" src="assets/js/login.js"></script>
	<script>
		$('#username').focus();
	</script>
</html>
    
