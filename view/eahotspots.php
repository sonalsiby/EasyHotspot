<?php
    if ( !isset($_SESSION) ) session_start();
    $hotspot_type = $_GET['type'];
    $_SESSION['host_id'] = 0;
?>


<link rel="stylesheet" href="assets/css/list_users.css">

<div class="epage-list-user" id="eahotspots">
    <h4 id="ea-hotspots-title">Hotspot List (<?php echo $hotspot_type;?>) <?php echo $_SESSION['user_level'].'||'.$_SESSION['host_id']; ?></h4>
    <div id="eahotspot-canvas">
        
    </div>
</div>

<script>    
    var hotspot_type="<?php echo $hotspot_type ?>";
</script>
<script type="text/javascript" src="assets/js/eahotspots.js">