<?php if ( !isset($_SESSION) ) session_start(); ?>

<link rel="stylesheet" href="assets/css/add_multiple_users.css">

<div id="epage-add-multiple-users">
    <div class="epage-container">
        <div class="row no-gutter">
            <div class="col-lg-6">
                <h5> Create Multiple Users </h5>
                <div class="eform-container">
                    <div class="form-group row">
                        <div class="input-group col-sm-12">
                            <div class="input-group-prepend bg-dark text-white">
                                <div class="input-group-text bg-dark text-white">
                                    Username Prefix
                                </div>
                            </div>
                            <input type="text" id="euname-prefix" class="form-control bg-dark text-white" placeholder="Prefix" autofocus>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="input-group col-sm-6">
                            <div class="input-group-prepend bg-dark text-white">
                                <div class="input-group-text bg-dark text-white">
                                    User Count
                                </div>
                            </div>
                            <input type="number" id="euser-count" class="form-control bg-dark text-white" min="2" value="2">
                        </div>
                        <div class="input-group col-sm-6">
                            <div class="input-group-prepend bg-dark text-white">
                                <div class="input-group-text bg-dark text-white">
                                    Password
                                </div>
                            </div>
                            <select type="text" id="epassword-comb" class="form-control bg-dark text-white">
                                <option value="1">Username</option>
                                <option value="2">Different</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row" id="epassword-controls" style="display:none;">
                        <div class="input-group col-sm-6">
                            <div class="input-group-prepend bg-dark text-white">
                                <div class="input-group-text bg-dark text-white">
                                    Password Length
                                </div>
                            </div>
                            <input type="number" id="epassword-length" class="form-control bg-dark text-white" min="3" value="4" max="8">
                        </div>
                        <div class="input-group col-sm-6">
                            <div class="input-group-prepend bg-dark text-white">
                                <div class="input-group-text bg-dark text-white">
                                    Password Type
                                </div>
                            </div>
                            <select id="epassword-pattern" class="form-control bg-dark text-white">
                                <option value="sn">abcd1234</option>
                                <option value="s">abcd</option>
                                <option value="c">DCBA</option>
                                <option value="sc">BaCd</option>
                                <option value="cn">1342ABCD</option>
                                <option value="scn">1423aBcD</option>
                                <option value="n">1234</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="input-group col-sm-12">
                            <div class="input-group-prepend bg-dark text-white">
                                <div class="input-group-text bg-dark text-white">
                                    Bandwidth Profile
                                </div>
                            </div>
                            <select id="em-user-profile" class="form-control bg-dark text-white">

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
                            <input type="text" id="em-up-limit" class="form-control bg-dark text-white" placeholder="Uptime">
                        </div>
                        <div class="input-group col-sm-6">
                            <div class="input-group-prepend bg-dark text-white">
                                <div class="input-group-text bg-dark text-white">
                                    Data Limit
                                </div>
                            </div>
                            <input type="text" id="em-data-limit" class="form-control bg-dark text-white" placeholder="Allowed Data" value="0">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="input-group col-sm-6">
                            <div class="input-group-prepend bg-dark text-white">
                                <div class="input-group-text bg-dark text-white">
                                    Phone
                                </div>
                            </div>
                            <input type="text" id="ephone" class="form-control bg-dark text-white" placeholder="Phone number">
                        </div>
                        <div class="input-group col-sm-6">
                            <div class="input-group-prepend bg-dark text-white">
                                <div class="input-group-text bg-dark text-white">
                                    E-mail
                                </div>
                            </div>
                            <input type="email" id="eemail" class="form-control bg-dark text-white" placeholder="E-mail address">
                        </div>
                    </div>
                    <div class="form-group row">
                        
                        <div class="col-sm-4">
                            <button id="em-add-multiple-users" class="btn btn-sm btn-success">Create Users</button>
                        </div>
                    </div>
                    <a id="ev-print" href="model/voucher/tmp/temp_print<?php echo $_SESSION['host_id'];?>.php" target="_blank" style="display:none;"></a>
                </div>
            </div>
            <div class="col-lg-6">
                <div id="es-help-text" class="col-lg-12">
                    <ul style="font-size: 13px;">
                        <li>Username Prefix</li>
                            <p>This phrase will be added to the beginning of every username.</p>
                        <li>User Count</li>
                            <p>Number of usernames to generate in the series.</p>
                        <li>Password Length</li>
                            <p>Length of password phrase preferred.</p>
                        <li>Password & Username</li>
                            <p> Can either be equal to username or a different pattern.</p>
                        <li>Password Type</li>
                            <p>Password generation pattern</p>
                        <li>Bandwidth Profile</li>
                            <p>Apply data rate limit according to pre-defined router profile.</p>
                        <li>Uptime Limit</li>
                            <p>Should be in 'wdhms' format. Eg. 2d5h for 2 days and 5 hours.</p>
                        <li>Data Limit</li>
                            <p>Enter number and unit. Eg. 10M for 10MB or 3G for 3GB. 0 for Unlimited</p>
                    </ul>
                </div>
            </div>
        </div>
    </div>  
</div>
<script>
    var id = "<?php echo $_SESSION['host_id'];?>";
</script>
<script type="text/javascript" src="assets/js/add_multiple_users.js"></script>