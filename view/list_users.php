<?php
    if ( !isset($_SESSION) ) session_start();
    $euser_type = $_GET['type'];
?>

<link rel="stylesheet" href="assets/css/list_users.css">

<div class="epage-list-user" id="epage-list-user">
    <h4>List of <?php echo $euser_type; ?> hotspot users</h4>
    <div id="edraw-table">
        
    </div>
    <a id="ev-print" href="model/voucher/tmp/temp_print<?php echo $_SESSION['host_id'];?>.php" target="_blank" style="display:none;"></a>
</div>
<script>
    var user_type = "<?php echo $euser_type ?>";
    var id = "<?php echo $_SESSION['host_id'];?>";
</script>
<script type="text/javascript" src="assets/js/list_users.js">