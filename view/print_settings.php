<?php
    if ( !isset($_SESSION) ) session_start();
?>
<link rel="stylesheet" href="assets/css/print_settings.css">

<div id="epage-print-settings">
    <div class="epage-container">
        <div class="row no-gutter">
            <div class=" col-lg-8">
                <h5>Voucher Print Configuration <?php echo $_SESSION['host_id']; ?></h5>
                <div class="eform-container">
                    <div class="form-group">
                        <div id="editor">

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <h5>Preview</h5>
                <div id="es-help-text" class="col-lg-12">
                    <div id="epreview-area">

                    </div>
                </div>
                <div>
                    <button id="refreshPreview" class="btn btn-sm btn-primary col-sm-5 offset-sm-1"><i class="fas fa-sync"></i> Refresh View</Button>
                    <button id="saveTemplate" class="btn btn-sm btn-success col-sm-5"><i class="fas fa-save"></i> Save Template</Button>
                </div>
            </div>
        </div>
    </div> 
</div>

<script type="text/javascript" src="assets/vendor/ace/ace.js"></script>
<script type="text/javascript" src="assets/js/print_settings.js"></script>