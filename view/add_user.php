<?php if ( !isset($_SESSION) ) session_start(); ?>

<link rel="stylesheet" href="assets/css/add_user.css">

<div id="epage-add-user">
    <div class="epage-container">
        <div class="row no-gutter">
            <div class="col-lg-6">
                <h5>Add Single User</h5>
                <div class="eform-container">
                    <div class="form-group row">
                        <div class="input-group col-sm-12">
                            <div class="input-group-prepend bg-dark text-white">
                                <div class="input-group-text bg-dark text-white">
                                    Username
                                </div>
                            </div>
                            <input class="form-control bg-dark text-white" type="text" id="es-username" placeholder="Required username" aria-describedby="basic-addon2">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="input-group col-sm-12">
                            <div class="input-group-prepend bg-dark text-white">
                                <div class="input-group-text bg-dark text-white">
                                    Password
                                </div>
                            </div>
                            <input class="form-control bg-dark text-white" type="password" id="es-password" placeholder="(optional)">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="input-group col-sm-12">
                            <div class="input-group-prepend bg-dark text-white">
                                <div class="input-group-text bg-dark text-white">
                                    Bandwidth Profile
                                </div>
                            </div>
                            <select class="form-control bg-dark text-white" id="es-user-profile" aria-label="Select pre-defined profile">
                            
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="input-group col-sm-6">
                            <div class="input-group-prepend bg-dark text-white">
                                <div class="input-group-text bg-dark text-white">
                                    Uptime Limit
                                </div>
                            </div>
                            <input type="text" id="es-uptime-limit" class="form-control bg-dark text-white" aria-label="Uptime limit" placeholder="Uptime limit">
                        </div>
                        <div class="input-group col-sm-6">
                            <div class="input-group-prepend bg-dark text-white">
                                <div class="input-group-text bg-dark text-white">
                                    Data Limit
                                </div>
                            </div>
                            <input type="text" id="es-rate-limit" value="0" class="form-control bg-dark text-white" aria-label="Data limit" placeholder="Data limit">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="input-group col-sm-6">
                            <div class="input-group-prepend bg-dark text-white">
                                <div class="input-group-text bg-dark text-white">
                                    Phone
                                </div>
                            </div>
                            <input class="form-control bg-dark text-white" type="text" id="es-phone" pattern="[0-9]" placeholder="Mobile number" required>
                        </div>
                        <div class="input-group col-sm-6">
                            <div class="input-group-prepend bg-dark text-white">
                                <div class="input-group-text bg-dark text-white">
                                    E-mail
                                </div>
                            </div>
                            <input class="form-control bg-dark text-white" type="email" id="es-email" placeholder="E-mail address" required>
                        </div>
                    </div>
                    <button id="es-add-single-user" class="btn btn-success btn-sm">Create user</button>
                    <a id="ev-print" href="model/voucher/tmp/temp_print<?php echo $_SESSION['host_id'];?>.php" target="_blank" style="display:none;"></a>
                </div>
            </div>
            <div class="col-lg-6">
                <div id="es-help-text" class="col-lg-12">
                    <ul style="font-size: 13px;">
                        <li>Username</li>
                            <p>The username to be given for client login.</p>
                        <li>Password</li>
                            <p>If omitted, password is same as username.</p>
                        <li>User Profile</li>
                            <p>Router profile assigned to the new user.</p>
                        <li>Uptime Limit</li>
                            <p>Control user's uptime apart from validity.
                                Should be in 'wdhms' format. Eg. 2d5h for 2 days and 5 hours.</p>
                        <li>Data Limit</li>
                            <p>Limit data usage of user. 0 for unlimited. Else, enter number and unit. 
                                Eg. 10M for 10MB or 3G for 3GB</p>
                    </ul>
                </div>
            </div>
        </div>
    </div>  
</div>
<script>
    var id = "<?php echo $_SESSION['host_id'];?>";
</script>
<script type="text/javascript" src="assets/js/add_user.js"></script>