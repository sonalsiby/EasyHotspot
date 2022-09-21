<?php
    require_once('controller/session.php');
    if ( !isset($_SESSION) ) session_start();
    if ($_SESSION['user_level']>=3) {
        header('Location: controller/logout.php');
    }
    $_SESSION['host_id'] = 0;
?>

<!DOCTYPE html>

<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>EHS Admin</title>
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
                <span id="elogo-title" class="elogo-title">EHS Admin</span>
                <div class="euser-img-container">
                    <img class="euser-img" src="<?php 
                        $avatar = 'userdata/img/'.$_SESSION['id'].'-avatar.jpg';
                        echo (file_exists($avatar) ? $avatar : 'userdata/img/user.png'); ?>">
                </div>
            </div>
        </header>
        <div id="emeter-container" class="emeter">
            <span id="epage-load" class="emeter-needle"></span>
        </div>

        <div id="enotepath" class="enotepath">
            <a id="etogglenav" href="#"><i class="fas fa-bars etogglenav"></i></a>
            <!-- <span id="erouter-label"> &nbsp;&nbsp; </span> <span id="erouter-name"></span> -->
            <div class="euser-panel epull-right">
                <a id="ehelp" class="euser-item" href="#"><i class="fas fa-life-ring"></i></a>
                <a id="enotify" class="euser-item" href="#"><i class="fas fa-envelope"></i></a>
                <div class="edropdown-container epull-right"> 
                    <li id="euser-name" class="euser-item edropdown"><a href="#"><i class="fas fa-user-astronaut"></i> &nbsp; <?php echo $_SESSION['name']; ?><i class="fas fa-angle-down"></i></a></li>
                    <ul class="edropdown-sub edropdown-sub-expand-left">
                        <li class="euser-item edropdown-sub-item" id="eprofile"><a href="#"><i class="fas fa-id-card"></i>Account & Profile</a></li>
                        <li class="euser-item edropdown-sub-item"><a href="#"><i class="fas fa-warehouse"></i>Preferences</a></li>
                        <li class="euser-item edropdown-sub-item" id="elogout"><a href="#"><i class="fas fa-sign-out-alt"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <ul id="esidenav" class="esidenav">
                <li class="esidenav-item" id="eadashboard"><a href="#"><i class="fas fa-home"></i>Dashboard</a></li>
                <div class="eside-dropdown-container">
                    <li id="eahotspots" class="esidenav-item esidenav-dropdown"><a href="#"><i class="fas fa-wifi"></i>Hotspots <i class="fas fa-sort-down"></i></a></li>
                    <ul class="esidenav-sub">
                        <li class="esidenav-item esub-item" id="eahotspots-all"><a href="#"><i class="fas fa-globe-asia"></i>All Hotspots</a></li>
                        <li class="esidenav-item esub-item" id="eahotspots-add"><a href="#"><i class="fas fa-plus-square"></i>Add New</a></li>                    
                    </ul>
                </div>

                <div class="eside-dropdown-container">
                    <li id="easubscriptions" class="esidenav-item esidenav-dropdown"><a href="#"><i class="fas fa-calendar"></i>Subscriptions <i class="fas fa-sort-down"></i></a></li>
                    <ul class="esidenav-sub">
                        <li class="esidenav-item esub-item" id="eahotspots-expired"><a href="#"><i class="fas fa-calendar-times"></i>Expired<span id="expiry-badge" class="badge badge-light" style="margin-left: 50px;"></span></a></li>
                        <li class="esidenav-item esub-item" id="eahotspots-by-expiry"><a href="#"><i class="fas fa-calendar-day"></i>By Expiry</a></li>
                    </ul>
                </div>
                
                <?php
                if (($_SESSION['user_level'] == 2) AND  ($_SESSION['host_id'] == 0)) {
                    echo "";
                } else {
                    echo '<li class="esidenav-item" id="easystem-users"><a href="#"><i class="fas fa-user-friends"></i>System Users</a></li>';
                }	 
                ?>
            </ul>

            <div id="ebody-canvas" class="ebody-canvas">
                <div id="eloader-container" class="eloader-container">
                    <div id="eloader" class="eloader"></div>
                </div>
                <div id="edraw-area" class="edraw-area">
                    
                </div>
                <footer id="efooter">
                    <strong class="label label-success">Status: &nbsp</strong> <span id="estatus">Loaded</span>
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
    <script src="assets/vendor/iziToast/js/iziToast.js"></script>
    <script src="assets/js/ehs-ui.js"></script>
    <script src="assets/js/ehs-control.js"></script>

    <script>
        $.fn.dataTable.Api.register('column().title()', function() {
            var colheader = this.header();
            return $(colheader).text().trim();
        });
    </script>  
</html>
