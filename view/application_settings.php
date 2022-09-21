<?php if ( !isset($_SESSION) ) session_start(); ?>

<link rel="stylesheet" href="assets/css/router_settings.css">

<div id="epage-app-settings">
    <div class="epage-container">
        <div class="row no-gutter">
            <div class="col-lg-6">
                <h5>Application Settings</h5>
                <div class="eform-container">
                    <div class="form-group row">
                        <div class="input-group col-sm-12">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-dark text-white">
                                    Timezone
                                </div>
                            </div>
                            <select id="etimezone" class="form-control bg-dark text-white">
                                <option value="<?php echo $timezone; ?>"><?php echo $timezone; ?></option>
                            <?php
								$tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
								foreach ($tzlist as $key => $dtrow) { ?>
									<option id="<?php echo $dtrow; ?>" value="<?php echo $dtrow; ?>"><?php echo $dtrow; ?></option>
									<?php
                                } 
                            ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="input-group col-sm-12">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-dark text-white">
                                    Date Format
                                </div>
                            </div>
                            <select id="edate-format" class="form-control bg-dark text-white">
                                <option id="d-m-Y" value="d-m-Y"><?php echo date("d-m-Y"); ?></option>
                                <option id="d/m/Y" value="d/m/Y"><?php echo date("d/m/Y"); ?></option> 
                                <option id="l jS \of F Y" value="l jS \of F Y"><?php echo date("l jS \of F Y"); ?></option>
                                <option id="Y-m-d H:i:s" value="Y-m-d H:i:s"><?php echo date("Y-m-d H:i:s") ; ?></option>
                                <option id="Y/m/d" value="Y/m/d"><?php echo date("Y/m/d"); ?></option>
                                <option id="d-m-Y H:i:s" value="d-m-Y H:i:s"><?php echo date("d-m-Y H:i:s"); ?></option>
                                <option id="M-d-Y" value="M-d-Y"><?php echo date("M-d-Y"); ?></option>
                                <option id="Y/d/m" value="Y/d/m"><?php echo date("Y/d/m"); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="input-group col-sm-12">
                            <div class="input-group-prepend bg-dark text-white">
                                <div class="input-group-text bg-dark text-white">
                                    Vouchers per Row
                                </div>
                            </div>
                            <input type="number" id="ev-per-row" class="form-control bg-dark text-white" min="1" max="6">
                        </div>
                    </div>
                    <button id="ebtn-update-app" class="btn btn-success btn-sm col-sm-4 offset-sm-8">Update Settings</button>
                </div>
                
            </div>
            <div class="col-lg-6">
            <h5>Organization Logo</h5>
            <div class="eform-container">
                <div class="row justify-content-center col-sm-12 eimg-container">
                    <img class="img" style="max-width:100px;max-height:100px;"
                        src="<?php $avatar = '../userdata/img/'.$_SESSION['host_id'].'-company.jpg';
                            echo (file_exists($avatar) ? $avatar : 'assets/img/logo.png');
                            ?>">
                </div>
                <div class="row col-sm-12 justify-content-center">
                    <input id="ecompany-file" type="file" class="btn btn-primary btn-sm col-sm-5 ebtn" accept=".jpg, .jpeg">  
                    <!-- <button class="btn btn-primary btn-sm col-sm-4 ebtn">Remove Image</button> -->
                </div>
            </div>
            </div>
        </div>
       
    </div>
</div>

<script type="text/javascript" src="assets/js/application_settings.js">