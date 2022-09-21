<?php
    if ( !isset($_SESSION) ) session_start();
    $hotspot_id = $_POST['id'];
    $_SESSION['host_id'] = $hotspot_id;
?>


<link rel="stylesheet" href="assets/css/add_user.css">
<style>
    .input-group {
        margin-top: 10px;
        margin-bottom: 10px;
    }

    .nav-item {
        color: white;
    }
</style>

<div id="eapage-hotspot-details">
    <div class="epage-container">
        <div class="row no-gutter">
            <div class="col-lg-6">
                <h5>Hotspot Details <?php echo $_SESSION['user_level'].'||'.$_SESSION['host_id']; ?></h5>
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">General</a>
                        <a class="nav-item nav-link" id="nav-details-tab" data-toggle="tab" href="#nav-details" role="tab" aria-controls="nav-details" aria-selected="false">Hotspot</a>
                        <a class="nav-item nav-link" id="nav-psrofile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Subscription</a>
                        <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">Invoices</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        <div class="eform-container">
                            <div class="input-group col-sm-12">
                                <div class="input-group-prepend bg-dark text-white">
                                    <div class="input-group-text bg-dark text-white">
                                        Company
                                    </div>
                                </div>
                                <input class="form-control bg-dark text-white" type="text" id="eacompany-name" aria-describedby="basic-addon2" disabled>
                            </div>
                            <div class="input-group col-sm-12">
                                <div class="input-group-prepend bg-dark text-white">
                                    <div class="input-group-text bg-dark text-white">
                                        Address
                                    </div>
                                </div>
                                <textarea class="form-control bg-dark text-white" type="text" rows="3" id="eacompany-address" aria-describedby="basic-addon2" disabled></textarea>
                            </div>
                            <div class="input-group col-sm-12">
                                <div class="input-group-prepend bg-dark text-white">
                                    <div class="input-group-text bg-dark text-white">
                                        Contact
                                    </div>
                                </div>
                                <input class="form-control bg-dark text-white" type="text" id="eacompany-contact" aria-describedby="basic-addon2" disabled>
                            </div>
                            <div class="input-group col-sm-12">
                                <div class="input-group-prepend bg-dark text-white">
                                    <div class="input-group-text bg-dark text-white">
                                        E-mail
                                    </div>
                                </div>
                                <input class="form-control bg-dark text-white" type="text" id="eacompany-email" aria-describedby="basic-addon2" disabled>
                            </div>
                            <div class="input-group col-sm-12">
                                <div class="input-group-prepend bg-dark text-white">
                                    <div class="input-group-text bg-dark text-white">
                                        Phone
                                    </div>
                                </div>
                                <input class="form-control bg-dark text-white" type="text" id="eacompany-phone" aria-describedby="basic-addon2" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-details" role="tabpanel" aria-labelledby="nav-details-tab">
                        <div class="eform-container">
                            <div class="input-group col-sm-12">
                                <div class="input-group-prepend bg-dark text-white">
                                    <div class="input-group-text bg-dark text-white">
                                        Hotspot
                                    </div>
                                </div>
                                <input class="form-control bg-dark text-white" type="text" id="eahotspot-name" aria-describedby="basic-addon2" disabled>
                            </div>
                            <div class="input-group col-sm-12">
                                <div class="input-group-prepend bg-dark text-white">
                                    <div class="input-group-text bg-dark text-white">
                                        Host IP
                                    </div>
                                </div>
                                <input class="form-control bg-dark text-white" type="text" id="eahotspot-ip" aria-describedby="basic-addon2" disabled>
                            </div>
                            <div class="input-group col-sm-12">
                                <div class="input-group-prepend bg-dark text-white">
                                    <div class="input-group-text bg-dark text-white">
                                        Hostname
                                    </div>
                                </div>
                                <input class="form-control bg-dark text-white" type="text" id="eahotspot-host" aria-describedby="basic-addon2" disabled>
                            </div>
                            <div class="input-group col-sm-12">
                                <div class="input-group-prepend bg-dark text-white">
                                    <div class="input-group-text bg-dark text-white">
                                        Username
                                    </div>
                                </div>
                                <input class="form-control bg-dark text-white" type="text" id="eahotspot-username" aria-describedby="basic-addon2" disabled>
                            </div>
                            <div class="input-group col-sm-12">
                                <div class="input-group-prepend bg-dark text-white">
                                    <div class="input-group-text bg-dark text-white">
                                        Password
                                    </div>
                                </div>
                                <input class="form-control bg-dark text-white" type="text" id="eahotspot-password" aria-describedby="basic-addon2" disabled>
                            </div>
                        </div>
                    </div>  
                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <div class="eform-container">
                            <div class="input-group col-sm-12">
                                <div class="input-group-prepend bg-dark text-white">
                                    <div class="input-group-text bg-dark text-white">
                                        Created On
                                    </div>
                                </div>
                                <input class="form-control bg-dark text-white" type="text" id="eahotspot-creation" aria-describedby="basic-addon2" disabled>
                            </div>
                            <div class="input-group col-sm-12">
                                <div class="input-group-prepend bg-dark text-white">
                                    <div class="input-group-text bg-dark text-white">
                                        Created By
                                    </div>
                                </div>
                                <input class="form-control bg-dark text-white" type="text" id="eahotspot-creator" aria-describedby="basic-addon2" disabled>
                            </div>
                            <div class="input-group col-sm-12">
                                <div class="input-group-prepend bg-dark text-white">
                                    <div class="input-group-text bg-dark text-white">
                                        Valid Till
                                    </div>
                                </div>
                                <input class="form-control bg-dark text-white" type="text" id="eahotspot-validity" aria-describedby="basic-addon2" disabled>
                            </div>
                            <div class="input-group">
                                <button id="ea-validity" class="btn btn-primary btn-sm col-sm-12">Change Validity</button>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                    </div>
                </div>
                <?php 
                if ($_SESSION['user_level'] == 1) {
                echo '<div class="form-group row">
                    <div class="input-group col-sm-2">
                        <button id="ea-status" class="btn btn-success btn-sm">Active</button>
                    </div>
                    <div class="input-group col-sm-2">
                        <button id="ea-edit" class="btn btn-primary btn-sm"> Edit </button>
                    </div>
                    <div class="input-group col-sm-2">
                        <button id="ea-delete" class="btn btn-danger btn-sm">Delete</button>
                    </div>
                    <div class="input-group col-sm-2">
                        <button id="ea-access" class="btn btn-primary btn-sm">Access</button>
                    </div>
                    <div class="input-group col-sm-3">
                        <button id="ea-back" class="btn btn-warning btn-sm">Go Back</button>
                    </div>
                </div>';
                } else {
                    echo '<div class="form-group row">
                    <div class="input-group col-sm-4">
                        <button id="ea-status" class="btn btn-success btn-sm">Active</button>
                    </div>
                    <div class="input-group col-sm-4">
                        <button id="ea-access" class="btn btn-primary btn-sm">Access</button>
                    </div>
                    <div class="input-group col-sm-4">
                        <button id="ea-back" class="btn btn-warning btn-sm">Go Back</button>
                    </div>
                </div>';
                }
                ?>
            </div>
            <?php 
            if ($_SESSION['user_level']==1) {
                echo '<div class="col-lg-6">
                    <h5>Authorised Users</h5>
                    <div id="eauser-table-canvas">

                    </div>
                </div>';
            }
            ?>
        </div>
    </div>  
</div>

<script>
    var hotspot_id="<?php echo $hotspot_id ?>";
</script>
<script type="text/javascript" src="assets/js/eahotspot_details.js"></script>