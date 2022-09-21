<?php
    if ( !isset($_SESSION) ) session_start();
    $_SESSION['host_id'] = 0;
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
                <h5>Add New Hotspot</h5>
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">General</a>
                        <a class="nav-item nav-link" id="nav-details-tab" data-toggle="tab" href="#nav-details" role="tab" aria-controls="nav-details" aria-selected="false">Hotspot</a>
                        <a class="nav-item nav-link" id="nav-psrofile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Subscription</a>
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
                                <input class="form-control bg-dark text-white" type="text" id="eacompany-name" placeholder="Company name" aria-describedby="basic-addon2" required setfocus>
                            </div>
                            <div class="input-group col-sm-12">
                                <div class="input-group-prepend bg-dark text-white">
                                    <div class="input-group-text bg-dark text-white">
                                        Address
                                    </div>
                                </div>
                                <textarea class="form-control bg-dark text-white" type="text" rows="3" id="eacompany-address" placeholder="Company address" aria-describedby="basic-addon2"></textarea>
                            </div>
                            <div class="input-group col-sm-12">
                                <div class="input-group-prepend bg-dark text-white">
                                    <div class="input-group-text bg-dark text-white">
                                        Contact
                                    </div>
                                </div>
                                <input class="form-control bg-dark text-white" type="text" id="eacompany-contact" placeholder="Contact person" aria-describedby="basic-addon2">
                            </div>
                            <div class="input-group col-sm-12">
                                <div class="input-group-prepend bg-dark text-white">
                                    <div class="input-group-text bg-dark text-white">
                                        E-mail
                                    </div>
                                </div>
                                <input class="form-control bg-dark text-white" type="email" id="eacompany-email" placeholder="Contact e-mail" aria-describedby="basic-addon2" >
                            </div>
                            <div class="input-group col-sm-12">
                                <div class="input-group-prepend bg-dark text-white">
                                    <div class="input-group-text bg-dark text-white">
                                        Phone
                                    </div>
                                </div>
                                <input class="form-control bg-dark text-white" type="text"  placeholder="Contact phone" id="eacompany-phone" aria-describedby="basic-addon2" >
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
                                <input class="form-control bg-dark text-white" type="text" id="eahotspot-name" placeholder="Hotspot name" aria-describedby="basic-addon2" >
                            </div>
                            <div class="input-group col-sm-12">
                                <div class="input-group-prepend bg-dark text-white">
                                    <div class="input-group-text bg-dark text-white">
                                        Host IP
                                    </div>
                                </div>
                                <input class="form-control bg-dark text-white" type="text" id="eahotspot-ip" placeholder="IP of host" aria-describedby="basic-addon2" >
                            </div>
                            <div class="input-group col-sm-12">
                                <div class="input-group-prepend bg-dark text-white">
                                    <div class="input-group-text bg-dark text-white">
                                        Hostname
                                    </div>
                                </div>
                                <input class="form-control bg-dark text-white" type="text" id="eahotspot-host" placeholder="Alternative host name" aria-describedby="basic-addon2" >
                            </div>
                            <div class="input-group col-sm-12">
                                <div class="input-group-prepend bg-dark text-white">
                                    <div class="input-group-text bg-dark text-white">
                                        Username
                                    </div>
                                </div>
                                <input class="form-control bg-dark text-white" type="text" id="eahotspot-username" placeholder="Host username" aria-describedby="basic-addon2" >
                            </div>
                            <div class="input-group col-sm-12">
                                <div class="input-group-prepend bg-dark text-white">
                                    <div class="input-group-text bg-dark text-white">
                                        Password
                                    </div>
                                </div>
                                <input class="form-control bg-dark text-white" type="text" placeholder="Host password" id="eahotspot-password" aria-describedby="basic-addon2" >
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
                                <input class="form-control bg-dark text-white" type="text" id="eahotspot-creation" placeholder="Automatic" aria-describedby="basic-addon2" disabled>
                            </div>
                            <div class="input-group col-sm-12">
                                <div class="input-group-prepend bg-dark text-white">
                                    <div class="input-group-text bg-dark text-white">
                                        Created By
                                    </div>
                                </div>
                                <input class="form-control bg-dark text-white" type="text" id="eahotspot-creator" placeholder="Super Admin" aria-describedby="basic-addon2" disabled>
                            </div>
                            <div class="input-group col-sm-12">
                                <div class="input-group-prepend bg-dark text-white">
                                    <div class="input-group-text bg-dark text-white">
                                        Valid Till
                                    </div>
                                </div>
                                <input class="form-control bg-dark text-white" type="date" placeholder="Choose date" id="eahotspot-validity" aria-describedby="basic-addon2" >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="input-group col-sm-4">
                        <button id="ea-status" class="btn btn-success btn-sm">Status: Active</button>
                    </div>
                    
                    <div class="input-group col-sm-4">
                        <button id="ea-back" class="btn btn-warning btn-sm">Go Back to List</button>
                    </div>

                    <div class="input-group col-sm-4">
                        <button id="ea-add" class="btn btn-primary btn-sm">Add Hotspot</button>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <!-- <h5>Authorised Users</h5>
                <div id="eauser-table-canvas">

                </div> -->
            </div>
        </div>
    </div>  
</div>

<script type="text/javascript" src="assets/js/eahotspot_add_new.js"></script>