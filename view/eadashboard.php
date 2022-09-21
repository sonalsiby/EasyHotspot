<link rel="stylesheet" href="assets/css/dashboard.css">

<?php
    if ( !isset($_SESSION) ) session_start(); 
    include('../model/db_conn.php');

    $stmt = $DB_con->prepare("SELECT * FROM host WHERE 1");
    $stmt->execute(array());
    $total_hosts = $stmt->rowCount();

    $stmt = $DB_con->prepare("SELECT valid_till FROM host WHERE 1");
    $stmt->execute(array());
    $expired = 0;
    $expiring = 0;
    for ($i=0; $i < $stmt->rowCount(); $i++) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $valid_till = $row['valid_till'];
        $difference_days = date_diff(date_create($valid_till),date_create(date('d-m-Y')));
        $validity = $difference_days->format("%R%a");
        $validity = 0 - $validity;
        if ($validity <= 0) {
            $expired++;
        } else if ($validity <= 7) {
            $expiring++;
        }
    }
?>

<div id="eadashboard">
    <?php //echo $_SESSION['user_level'].'||'.$_SESSION['host_id']; ?>
    <div class="card-deck">
        <div class="card bg-success">
            <div class="card-title">System Date/Time &nbsp;<i class="far fa-clock"></i></div>
            <div class="eline-separator"></div>
            <div class="card-body">
                <div class="etime">
                    <span id="esthour" class="ehour">22</span>:<span id="estminute" class="eminute">35</span>:<span id="estsecond" class="esecond">59</span>
                </div>
                <div class="etimezone">
                    <span id="etzhours" class="etzhours"> </span> <span class="etzname"><em>Offset from GMT</em></span>
                </div>
            </div>
            <div class="eline-separator"></div>
            <div class="edate-container">
                <div class="edate">
                    <span id="estdate" class="edated">12</span> &nbsp;<span id="estmonth" class="emonth">4</span> &nbsp;<span id="estyear" class="eyear">2019</span>
                    &nbsp;<span id="estday" class="eday"><em>Monday</em></span>
                </div>
            </div>
        </div>
        <div class="card bg-primary">
            <div class="card-title">Total Hotspots &nbsp;<i class="far fa-clock"></i></div>
            <div class="eline-separator"></div>
            <div class="card-body">
                <span class="ehour" style="font-size:5em;"><?php echo $total_hosts; ?></span>
            </div>
            <div class="card-footer">
                <button id="ehall" class="btn btn-outline-light btn-sm float-right">More...</button>
            </div>
        </div>
        <div class="card bg-warning text-dark">
            <div class="card-title">Expiring in 7 Days &nbsp;<i class="far fa-clock"></i></div>
            <div class="eline-separator"></div>
            <div class="card-body">
                <span class="ehour" style="font-size:5em;"><?php echo $expiring; ?></span>
            </div>
            <div class="card-footer">
                <button id="ebyex" class="btn btn-outline-dark btn-sm float-right">More...</button>
            </div>
        </div>
        <div class="card bg-danger">
            <div class="card-title">Expired Hotspots &nbsp;<i class="far fa-clock"></i></div>
            <div class="eline-separator"></div>
            <div class="card-body">
                <span id="expiry-block" class="ehour" style="font-size:5em;"><?php echo $expired; ?></span>
            </div>
            <div class="card-footer">
                <button id="eexp" class="btn btn-outline-light btn-sm float-right">More...</button>
            </div>
        </div>
        
    </div>
</div>

<script src="assets/js/eadashboard.js"></script>