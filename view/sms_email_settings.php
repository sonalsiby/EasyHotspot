<?php if ( !isset($_SESSION) ) session_start() ?>

<link rel="stylesheet" href="assets/css/toggle.css">
<link rel="stylesheet" href="assets/css/router_settings.css">

<div id="epage-sem-settings">
    <div class="epage-container">
        <div class="row no-gutter">
            <div class="col-lg-6">
                <h5>SMS Settings <?php echo $_SESSION['user_level'].'||'.$_SESSION['host_id']; ?></h5>
                <div class="eform-container">
                    <div class="form-group row">
                        <div class="input-group col-sm-12">
                            <div class="input-group-prepend bg-dark text-white">
                                <div class="input-group-text bg-dark text-white">
                                    API URL
                                </div>
                            </div>
                            <input type="text" id="es-api-url" class="form-control bg-dark text-white" placeholder="SMS API Access URL">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="input-group col-sm-7">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-dark text-white">
                                    Message Key
                                </div>
                            </div>
                            <input type="text" id="es-par-1" class="form-control bg-dark text-white" placeholder="Key Name">
                        </div>
                        <div class="input-group col-sm-5">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-dark text-white">
                                    Value
                                </div>
                            </div>
                            <input type="text" id="es-val-1" class="form-control bg-dark text-white" placeholder="Key Value">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="input-group col-sm-7">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-dark text-white">
                                    Number Key
                                </div>
                            </div>
                            <input type="text" id="es-par-2" class="form-control bg-dark text-white" placeholder="Key Name">
                        </div>
                        <div class="input-group col-sm-5">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-dark text-white">
                                    Value
                                </div>
                            </div>
                            <input type="text" id="es-val-2" class="form-control bg-dark text-white" placeholder="Key Value">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="input-group col-sm-7">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-dark text-white">
                                    Parameter 3
                                </div>
                            </div>
                            <input type="text" id="es-par-3" class="form-control bg-dark text-white" placeholder="Key Name">
                        </div>
                        <div class="input-group col-sm-5">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-dark text-white">
                                    Value
                                </div>
                            </div>
                            <input type="text" id="es-val-3" class="form-control bg-dark text-white" placeholder="Key Value">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="input-group col-sm-7">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-dark text-white">
                                    Parameter 4
                                </div>
                            </div>
                            <input type="text" id="es-par-4" class="form-control bg-dark text-white" placeholder="Key Name">
                        </div>
                        <div class="input-group col-sm-5">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-dark text-white">
                                    Value
                                </div>
                            </div>
                            <input type="text" id="es-val-4" class="form-control bg-dark text-white" placeholder="Key Value">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="input-group col-sm-7">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-dark text-white">
                                    Parameter 5
                                </div>
                            </div>
                            <input type="text" id="es-par-5" class="form-control bg-dark text-white" placeholder="Key Name">
                        </div>
                        <div class="input-group col-sm-5">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-dark text-white">
                                    Value
                                </div>
                            </div>
                            <input type="text" id="es-val-5" class="form-control bg-dark text-white" placeholder="Key Value">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="input-group col-sm-7">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-dark text-white">
                                    Parameter 6
                                </div>
                            </div>
                            <input type="text" id="es-par-6" class="form-control bg-dark text-white" placeholder="Key Name">
                        </div>
                        <div class="input-group col-sm-5">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-dark text-white">
                                    Value
                                </div>
                            </div>
                            <input type="text" id="es-val-6" class="form-control bg-dark text-white" placeholder="Key Value">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <h5>E-mail Settings</h5>
                <div class="eform-container">
                    <div class="form-group row">
                        <div class="input-group col-sm-6">
                            <div class="input-group-prepend bg-dark text-white">
                                <div class="input-group-text bg-dark text-white">
                                    Protocol
                                </div>
                            </div>
                            <select id="ee-protocol" class="form-control bg-dark text-white">
                                <option id="SMTP" value="SMTP">SMTP</option>
                                <option id="POP" value="POP">POP</option>
                                <option id="IMAP" value="IMAP">IMAP</option>
                            </select>
                        </div>
                        <div class="input-group col-sm-6">
                            <div class="input-group-prepend bg-dark text-white">
                                <div class="input-group-text bg-dark text-white">
                                    Security
                                </div>
                            </div>
                            <select id="ee-security" class="form-control bg-dark text-white">
                                <option id="TLS" value="TLS">TLS</option>
                                <option id="SSL" value="SSL">SSL</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="input-group col-sm-8">
                            <div class="input-group-prepend bg-dark text-white">
                                <div class="input-group-text bg-dark text-white">
                                    Host Name
                                </div>
                            </div>
                            <input id="ee-hostname" type="text" class="form-control bg-dark text-white" placeholder="Host name">
                        </div>
                        <div class="input-group col-sm-4">
                            <div class="input-group-prepend bg-dark text-white">
                                <div class="input-group-text bg-dark text-white">
                                    Port
                                </div>
                            </div>
                            <input id="ee-port" type="text" class="form-control bg-dark text-white" placeholder="Port">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="input-group col-sm-12">
                            <div class="input-group-prepend bg-dark text-white">
                                <div class="input-group-text bg-dark text-white">
                                    Require Authentication
                                </div>
                                
                            </div>
                            <select id="ee-auth" class="form-control bg-dark text-white" name="eauth">
                                <option id="Yes" value="Yes">Yes</option>
                                <option id="No" value="No">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="input-group col-sm-12">
                            <div class="input-group-prepend bg-dark text-white">
                                <div class="input-group-text bg-dark text-white">
                                    Username
                                </div>
                            </div>
                            <input id="ee-username" type="text" class="form-control bg-dark text-white" placeholder="Username">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="input-group col-sm-12">
                            <div class="input-group-prepend bg-dark text-white">
                                <div class="input-group-text bg-dark text-white">
                                    Password
                                </div>
                            </div>
                            <input id="ee-password" type="text" class="form-control bg-dark text-white" placeholder="Password">
                        </div>
                    </div>
                    <h6>Message auto-send on User creation</h6>
                    <div class="eform-container">
                        <div class="form-group row">
                            <div class="input-group col-sm-12">
                                <label class="col-sm-2 offset-sm-1">SMS</label>
                                <label class="switch col-sm-2">
                                    <input id="auto-sms" type="checkbox">
                                    <span class="slider"></span>
                                </label>
                            
                                <label class="col-sm-2 offset-sm-2">Email</label>
                                <label class="switch col-sm-2">
                                    <input id="auto-email" type="checkbox">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group col-sm-12">
            <button id="update-ems" class="btn btn-success btn-sm col-sm-4 col-md-2 offset-sm-8 offset-md-10">Update Settings</button>
        </div>
    </div>
</div>

<script type="text/javascript" src="assets/js/sms_email.js">