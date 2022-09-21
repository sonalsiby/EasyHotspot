<?php
    require_once('controller/session.php');
    include('model/db_conn.php');
    if (!function_exists('elog')) {
        include('controller/_log.php');
    }
    if ( !isset($_SESSION) ) session_start();
    if ($_SESSION['host_id']==0) header('Location: controller/logout.php');

    $stmt = $DB_con->prepare("SELECT time_zone FROM config_app WHERE host_id=:host_id");
    $stmt->execute(array(':host_id'=>$_SESSION['host_id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $_SESSION['time_zone'] = $row['time_zone'];
    date_default_timezone_set($_SESSION['time_zone']);

    $stmt = $DB_con->prepare("SELECT valid_till,status,hotspot_name FROM host WHERE id=:host_id");
    $stmt->execute(array(':host_id' => $_SESSION['host_id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $_SESSION['valid_till'] = $row['valid_till'];
    $_SESSION['host_status'] = $row['status'];
    $_SESSION['host_name'] = $row['hotspot_name'];
    $difference_days = date_diff(date_create($_SESSION['valid_till']),date_create(date('d-m-Y')));
    $_SESSION['validity'] = $difference_days->format("%R%a");

    if (in_array($_SESSION['username'], ['manager', 'executive'])) {
        $_SESSION['demo'] = true;
        elog('User is demo user. Starting in demo mode');
    } else {
        $_SESSION['demo'] = false;
    }
    elog('Opening host. ID:'.$_SESSION['host_id'].'. NAME: '.$_SESSION['host_name'].'. STATUS: '.$_SESSION['host_status'].'. VAL: '.$_SESSION['validity']);
    elog('User "'.$_SESSION['username'].'" { '.$_SESSION['name'].' } logged in.');
?>

<!DOCTYPE html>

<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Easy Hotspot</title>
		<meta name="description" content="An interface for MikroTik Routers">
		<meta name="author" content="Sonal Siby">
		<meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.18/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.css"/>
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="assets/vendor/iziToast/css/iziToast.css">
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="icon" href="assets/img/logo.png">
    </head>
    <body>
        <header id="eheader">
            <div id="elogo-panel" class="elogo-panel">
                <img class="elogo-img" src="assets/img/logo.png">
                <span id="elogo-title" class="elogo-title">Easy Hotspot</span>
                <div class="euser-img-container">
                    <img class="euser-img" src="<?php $avatar = 'userdata/img/'.$_SESSION['id'].'-avatar.jpg';echo (file_exists($avatar) ? $avatar : 'userdata/img/user.png');?>">
                </div>
            </div>
        </header>
        <div id="emeter-container" class="emeter">
            <span id="epage-load" class="emeter-needle"></span>
        </div>

        <div id="enotepath" class="enotepath">
            <a id="etogglenav" href="#"><i class="fas fa-bars etogglenav"></i></a>
            <span id="erouter-label"> &nbsp;&nbsp;Router: <?php echo $_SESSION['host_name']; ?> </span> <span id="erouter-name"></span>
            <div class="euser-panel epull-right">
                <div class="edropdown-container float-right"> 
                    <li id="euser-name" class="euser-item edropdown"><a href="#"><i class="fas fa-user-astronaut"></i> &nbsp; <?php echo $_SESSION['name']; ?><i class="fas fa-sort-down float-right"></i></a></li>
                    <ul class="edropdown-sub edropdown-sub-expand-left">
                        <li class="euser-item edropdown-sub-item" id="eprofile"><a href="#"><i class="fas fa-id-card"></i> Account & Profile</a></li>
                        <li class="euser-item edropdown-sub-item"><a href="#"><i class="fas fa-warehouse"></i> Preferences</a></li>
                        <li class="euser-item edropdown-sub-item" id="elogout"><a href="#"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        <?php if ($_SESSION['user_level'] <= 2) {
                            echo '<li class="euser-item edropdown-sub-item" id="eadminp"><a href="#"><i class="fas fa-sign-out-alt"></i>Admin Panel</a></li>';
                        }?>
                    </ul>
                </div>
                <div class="edropdown-container float-right">
                    <li class="euser-item edropdown" id="enotify"><a id="ehelp" class="euser-item" href="#"><i class="fas fa-file-invoice-dollar"></i> &nbsp; Subscription<i class="fas fa-sort-down float-right"></i></a></li>
                    <ul class="edropdown-sub">
                        <li id="eh-validity" class="euser-item edropdown-sub-item"><i class="fas fa-user-clock"></i> <?php echo (0-$_SESSION['validity']); ?> Day(s) left</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <ul id="esidenav" class="esidenav">
                <li class="esidenav-item" id="edashboard"><a href="#"><i class="fas fa-home"></i>Dashboard</a></li>
                <li class="esidenav-item" id="eadd-single"><a href="#"><i class="fas fa-user"></i>Add Single User</a></li>
                <li class="esidenav-item" id="eadd-multiple"><a href="#"><i class="fas fa-user-friends"></i>Add Multiple Users</a></li>

                <div class="eside-dropdown-container">
                    <li id="euser-list" class="esidenav-item esidenav-dropdown"><a href="#"><i class="fas fa-wifi"></i>Hotspot Users <i class="fas fa-sort-down"></i></a></li>
                    <ul class="esidenav-sub">
                        <li class="esidenav-item esub-item" id="elist-active"><a href="#"><i class="fas fa-skiing"></i>Active Users</a></li>
                        <li class="esidenav-item esub-item" id="elist-uninit"><a href="#"><i class="fas fa-user-secret"></i>Uninitiated Users</a></li>
                        <li class="esidenav-item esub-item" id="elist-expired"><a href="#"><i class="fas fa-calendar-times"></i>Expired Users</a></li>
                    </ul>
                </div>
                <?php
            if ($_SESSION['user_level'] != 4) {
                    echo '<li class="esidenav-item" id="euser-profiles"><a href="#"><i class="fas fa-key"></i>User Profiles</a></li>';
                    }
                
                    if ((($_SESSION['user_level'] == 2) AND  ($_SESSION['host_id'] == 0)) OR ($_SESSION['user_level'] == 4)) {
                    echo "";
                    }
                else echo '<li class="esidenav-item" id="esystem-users"><a href="#"><i class="fas fa-user-shield"></i>System Users</a></li>';	 
            
            if ($_SESSION['user_level'] != 4) {
            echo '
                <div class="eside-dropdown-container">
                    <li id="elogs" class="esidenav-item esidenav-dropdown"><a href="#"><i class="fas fa-hdd"></i></i>Logs <i class="fas fa-sort-down"></i></a></li>
                    <ul class="esidenav-sub">
                        <li class="esidenav-item esub-item" id="elogs-all"><a href="#"><i class="fas fa-globe-asia"></i>All Logs</a></li>
                        <li class="esidenav-item esub-item" id="elogs-system"><a href="#"><i class="fas fa-ethernet"></i>System Log</a></li>
                        <li class="esidenav-item esub-item" id="elogs-hotspot"><a href="#"><i class="fas fa-satellite-dish"></i>Hotspot Log</a></li>
                        <li class="esidenav-item esub-item" id="elogs-dhcp"><a href="#"><i class="fas fa-network-wired"></i>DHCP Log</a></li>
                    </ul>
                </div>';
                }
                ?>

                </a></li>
                <?php  if ($_SESSION['user_level'] <= 2) { ?> 
                <div class="eside-dropdown-container">
                    <li id="esettings" class="esidenav-item esidenav-dropdown"><a href="#"><i class="fas fa-cogs"></i>Settings<i style="right:0;" class="fas fa-sort-down"></i></a></li>
                    <ul class="esidenav-sub">
                        <li class="esidenav-item esub-item" id="eapp-settings"><a href="#"><i class="fas fa-tools"></i>App Settings</a></li>
                        <li class="esidenav-item esub-item" id="esms-email-settings"><a href="#"><i class="fas fa-envelope-open-text"></i>SMS & E-mail</a></li>
                        <li class="esidenav-item esub-item" id="eprint-settings"><a href="#"><i class="fas fa-print"></i>Print Settings</a></li>
                    </ul>
                </div>
                <?php } ?>
            </ul>

            <div id="ebody-canvas" class="ebody-canvas">
                <div id="eloader-container" class="eloader-container">
                    <div id="eloader" class="eloader"></div>
                </div>
                <div id="edraw-area" class="edraw-area">
                    
                </div>
                <footer id="efooter">
                    <strong class="label label-success">Status: &nbsp</strong> <span id="estatus">...</span>
                </footer>
            </div>
        </div>
    </body>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.18/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/api/column().title().js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <script src="assets/vendor/iziToast/js/iziToast.js"></script>
    <script src="assets/js/ehs-ui.js"></script>
    <script src="assets/js/ehs-control.js"></script>

    <script>
        $.fn.dataTable.Api.register('column().title()', function() {
            var colheader = this.header();
            return $(colheader).text().trim();
        });
        //var validity = "<?php //echo (0-$_SESSION['validity']); ?>";
        var validity = 100;
        var status = "<?php echo ($_SESSION['host_status']); ?>";
        var demo =  "<?php echo ($_SESSION['demo']); ?>";
    </script>
</html>
