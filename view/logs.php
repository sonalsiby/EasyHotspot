<?php if ( !isset($_SESSION) ) session_start(); ?>
<?php
    $log_type = $_GET['type'];
?>

<link rel="stylesheet" href="assets/css/logs.css">

<div class="epage-logs" id="epage-logs">
    <div id="edraw-logs">

    </div>
</div>

<script>
    var log_type="<?php echo $log_type ?>";
</script>

<script type="text/javascript" src="assets/js/logs.js">