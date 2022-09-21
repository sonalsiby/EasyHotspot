<?php
if ( !isset($_SESSION) ) session_start();
$DB_host = "localhost";
$DB_user = "root";
$DB_pass = "sonal";
$DB_name = "zetozone_ehsui";
try {
	$DB_con = new PDO("mysql:host={$DB_host}",$DB_user,$DB_pass);
	$DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbname = "`".str_replace("`","``",$DB_name)."`";
	$DB_con->query("use $dbname");
	$DB_con->query("SELECT id FROM user WHERE 1");
	}
	catch(PDOException $e) {
		$DB_con->query("CREATE DATABASE IF NOT EXISTS $dbname");
		$DB_con->query("use $dbname");
		$DB_con->query("CREATE TABLE `user` (
			`id` int(11) AUTO_INCREMENT NOT NULL,
			`username` varchar(50) NOT NULL,
			`password` varchar(100) NOT NULL,
			`created_on` datetime NOT NULL,
			`name` varchar(30) NOT NULL,
			`address` varchar(256) NOT NULL,
			`avatar` varchar(50) NOT NULL,
			`phone` varchar(22) NOT NULL,
			`email` varchar(50) NOT NULL,
			`status` varchar(10) NOT NULL,
			`user_level` int(1) NOT NULL,
			`host_id` int(11) NOT NULL,
			PRIMARY KEY (id)
		)");
		$DB_con->query("CREATE TABLE `host` (
			`id` int(11) AUTO_INCREMENT NOT NULL,
			`company` varchar(50) NOT NULL,
			`address` varchar(256) NOT NULL,
			`contact_person` varchar(30) NOT NULL,
			`telephone` varchar(22) NOT NULL,
			`email` varchar(35) NOT NULL,
			`host_ip` varchar(20) NOT NULL,
			`created_on` datetime NOT NULL,
			`valid_till` datetime NOT NULL,
			`logo_image` varchar(50) NOT NULL,
			`hotspot_name` varchar(50) NOT NULL,
			`status` varchar(10) NOT NULL,
			`host` varchar(64) NOT NULL,
			`user` varchar(15) NOT NULL,
			`pass` varchar(15) NOT NULL,
			PRIMARY KEY (id)
		)");
		$DB_con->query("CREATE TABLE `user_host` (
			`id` int(11) AUTO_INCREMENT NOT NULL,
			`user_id` int(5) NOT NULL,
			`host_id` int(5) NOT NULL,
			`status` varchar(10) NOT NULL,
			PRIMARY KEY (id)
		)");
		$DB_con->query("CREATE TABLE `config_app` (
			`id` int(11) AUTO_INCREMENT NOT NULL,
			`host_id` int(4) NOT NULL,
			`time_zone` varchar(30) NOT NULL,
			`date_format` varchar(30) NOT NULL,
			`autosend_sms` int(1) NOT NULL,
			`autosend_email` int(1) NOT NULL,
			`voucher_row` int(4) NOT NULL,
			PRIMARY KEY (id)
		)");
		$DB_con->query("CREATE TABLE `config_email` (
			`id` int(11) AUTO_INCREMENT NOT NULL,
			`host_id` int(4) NOT NULL,
			`protocol` varchar(4) NOT NULL,
			`smtp_authentication` varchar(3) NOT NULL,
			`host_name` varchar(50) NOT NULL,
			`username` varchar(50) NOT NULL,
			`password` varchar(64) NOT NULL,
			`smtp_security` varchar(5) NOT NULL,
			`port_no` varchar(5) NOT NULL,
			PRIMARY KEY (id)
		)");
		$DB_con->query("CREATE TABLE `config_print` (
			`id` int(11) AUTO_INCREMENT NOT NULL,
			`host_id` int(4) NOT NULL,
			`top_margin` decimal(5,2) NOT NULL,
			`bottom_margin` decimal(5,2) NOT NULL,
			`left_margin` decimal(5,2) NOT NULL,
			`right_margin` decimal(5,2) NOT NULL,
			`show_profile` varchar(3) NOT NULL,
			`show_password` varchar(3) NOT NULL,
			`show_email` varchar(3) NOT NULL,
			`show_telephone` varchar(3) NOT NULL,
			`show_data_limit` varchar(3) NOT NULL,
			`show_uptime_limit` varchar(3) NOT NULL,
			PRIMARY KEY (id)
		)");
		$DB_con->query("CREATE TABLE `config_sms` (
			`id` int(11) AUTO_INCREMENT NOT NULL,
			`host_id` int(4) NOT NULL,
			`api_url` varchar(100) NOT NULL,
			`param_name1` varchar(50) NOT NULL,
			`param_value1` varchar(50) NOT NULL,
			`param_name2` varchar(50) NOT NULL,
			`param_value2` varchar(50) NOT NULL,
			`param_name3` varchar(50) NOT NULL,
			`param_value3` varchar(50) NOT NULL,
			`param_name4` varchar(50) NOT NULL,
			`param_value4` varchar(50) NOT NULL,
			`param_name5` varchar(50) NOT NULL,
			`param_value5` varchar(50) NOT NULL,
			`param_name6` varchar(50) NOT NULL,
			`param_value6` varchar(50) NOT NULL,
			PRIMARY KEY (id)
		)");

		$DB_con->query("INSERT INTO `config_email` (`id`, `host_id`, `protocol`, `smtp_authentication`, `host_name`, `username`, `password`, `smtp_security`, `port_no`) VALUES
			(1, 1, 'SMTP', 'Yes', 'smtp.mail.com', 'username', 'password', 'TLS', '25')");
		$DB_con->query("INSERT INTO `config_app` (`id`, `host_id`, `time_zone`, `date_format`, `autosend_sms`, `autosend_email`, `voucher_row`) VALUES
			(1, 1, 'Asia/Kolkata', 'd/m/Y', 0, 0, 3)");
		$DB_con->query("INSERT INTO `config_sms` (`id`, `host_id`, `api_url`, `param_name1`, `param_value1`, `param_name2`, `param_value2`, `param_name3`, `param_value3`, `param_name4`, `param_value4`, `param_name5`, `param_value5`, `param_name6`, `param_value6`) VALUES
			(1, 1, 'sms.api.com', '', '', '', '', '', '', '', '', '', '', '', '')");
		$DB_con->query("INSERT INTO `user_host` (`id`, `user_id`, `host_id`, `status`) VALUES
			(1, 1, 1, 'Active')");
		echo "Error: " . $e->getMessage();
}
	/* Old Version, NOT creating DB if NOT Exist
	$DB_con = new PDO("mysql:host={$DB_host};dbname={$DB_name}",$DB_user,$DB_pass);
	$DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	*/
?>
