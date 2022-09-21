<?php
    if ( !isset($_SESSION) ) session_start();
?>

<link rel="stylesheet" href="assets/css/add_multiple_users.css">

<?php
    $process_type = $_GET['type'];
    if ($process_type == 'edit') {
        $pid = $_POST['id'];
    }
?>

<div id="epage-add-user-profile">
    <div class="epage-container">
        <div class="row no-gutter">
            <div class="col-lg-6">
                <div class="eform-container">
                    <div>
                        <div class="form-group row">
                            <label class="form-label col-sm-2" for="em-profile-name">Profile Name</label>
                            <input class="form-control bg-dark text-white col-sm-5" type="text" id="em-profile-name" name="profile_name" placeholder="Profile Name" aria-describedby="basic-addon2" autofocus required>

                            <label class="col-sm-2" for="em-shared-users">Shared Users</label>
                            <input class="form-control bg-dark text-white col-sm-2" type="number" id="em-shared-users" name="shared_users" min="1" value="1" placeholder="2" required>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2" for="em-validity">Validity</label>
                            <input class="form-control bg-dark text-white col-sm-3" type="text" id="em-validity" name="profile_validity" placeholder="Profile validity" aria-describedby="basic-addon2" required>
                            
                            <label class="col-sm-2" for="em-on-expiry">On Expiry </label>
                            <select class="form-control bg-dark text-white col-sm-4" id="em-on-expiry" name="on_expiry">
                                <option id="0" value="0">Do Nothing</option>
                                <option id="rem" value="rem">Remove</option>
                                <option id="ntf" value="ntf">Notice only</option>
                                <option id="ntfc" value="ntfc">Notice & Record</option>
                                <option id="remc" value="remc">Remove & Record</option>
                            </select>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2" for="em-grace-period">Grace Period</label>
                            <input class="form-control bg-dark text-white col-sm-3" type="text" id="em-grace-period" name="grace_period" placeholder="Extra Time" aria-describedby="basic-addon2">
                            
                            <label class="col-sm-2" for="em-lock-user">Lock User</label>
                            <select class="form-control bg-dark text-white col-sm-4" id="em-lock-user" name="lock_user">
                                <option id="Yes" value="Yes">Yes</option>
                                <option id="No" value="No">No</option>
                            </select>
                        </div>
                        <div class="form-group row">
                            <div class="input-group col-sm-11">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-dark text-white">
                                        Rx Rate Limit
                                    </div>
                                </div>
                                <input type="hidden" id="rx_rate_limit" name="rx_rate_limit">
                                <input type="number" id="em-rx-rate" class="form-control bg-dark text-white" aria-label="Data Rx limit" placeholder="Download" min="1">
                                <select class="form-control bg-dark text-white" id="em-rx-unit">
                                    <option id="Kr" value="K">Kb/s</option>
                                    <option id="Mr" value="M">Mb/s</option>
                                    <option id="Gr" value="G">Gb/s</option>
                                </select>
                            </div>
                            <div class="input-group col-sm-11">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-dark text-white">
                                        Tx Rate Limit
                                    </div>
                                </div>
                                <input type="hidden" id="tx_rate_limit" name="tx_rate_limit">
                                <input type="number" id="em-tx-rate" class="form-control bg-dark text-white" aria-label="Data Tx limit" placeholder="Upload" min="1">
                                <select class="form-control bg-dark text-white" id="em-tx-unit">
                                    <option id="Kt" value="K">Kb/s</option>
                                    <option id="Mt" value="M">Mb/s</option>
                                    <option id="Gt" value="G">Gb/s</option>
                                </select>
                            </div>
                        </div>
                        Session | MAC Cookie | Keepalive
                        <div class="form-group row">
                            <div class="input-group col-sm-11">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-dark text-white">
                                        Timeouts
                                    </div>
                                </div>
                                <input type="text" id="em-session-timeout" name="session_timeout" class="form-control bg-dark text-white" aria-label="Uptime limit" placeholder="Session">
                                <input type="text" id="em-mac-timeout" name="mac_cookie_timeout" class="form-control bg-dark text-white" aria-label="Data limit" placeholder="MAC Cookie">
                                <input type="text" id="em-keepalive-timeout" name="keepalive_timeout" class="form-control bg-dark text-white" aria-label="Data limit" placeholder="Keep Alive">
                            </div>
                        </div>
                        <div class="form-group row">
                            <button class="btn btn-success btn-sm col-sm-3 offset-sm-8" id="eprocess-btn">Save Profile</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div id="es-help-text" class="col-lg-12">
                    <ul style="font-size: 13px;">
                        <li>Profile Name</li>
                            <p>Name to be gven to profile</p>
                        <li>Shared Users</li>
                            <p>Number of devices allowed to use the same username & password.</p>
                        <li>Validity</li>
                            <p>Should be specified in 'wdhms' format. Eg. 1w3d for 1 week and 3 days.</p>
                        <li>On Expiry</li>
                            <p>Router scrip to run on expiry.</p>
                        <li>Grace Period</li>
                            <p>Extra time allotted</p>
                        <li>Rx/Tx Limit</li>
                            <p>Upload & Download limits.</p>
                        <li>Timeouts</li>
                            <p>Expected in 'wdhms' format.</p>
                    </ul>
                </div>
            </div>
        </div>
    </div>  
</div>
<script type="text/javascript">
    var process_type = '<?php echo $process_type; ?>';
    var pid = '<?php echo $pid; ?>';
</script>
<script type="text/javascript" src="assets/js/add_user_profile.js">