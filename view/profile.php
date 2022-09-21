<?php 
    if ( !isset($_SESSION) ) session_start(); 
?>

<link rel="stylesheet" href="assets/css/profile.css">

<div id="epage-profile">
    <div class="epage-container">
        <div class="row no-gutter">
            <div class="col-lg-6">
                <h5>Welcome, <?php echo $_SESSION['name']; ?></h5>
                <div class="eform-container">
                    <div class="form-group row">
                        <div class="input-group col-sm-12">
                            <div class="input-group-prepend bg-dark text-white">
                                <div class="input-group-text bg-dark text-white">
                                    Username
                                </div>
                            </div>
                            <input type="text" class="form-control bg-dark text-white" id="ep-username" placeholder="Username" value="<?php echo $_SESSION['username']; ?>" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="input-group col-sm-12">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-dark text-white">
                                    Usertype
                                </div>
                            </div>
                            <select class="form-control bg-dark text-white" id="ep-user-level" disabled>
                                <option value="1" <?php if ($_SESSION['user_level']==1) { echo 'selected'; } ?> >Super Admin</option>
                                <option value="2" <?php if ($_SESSION['user_level']==2) { echo 'selected'; } ?> >Administrator</option>
                                <option value="3" <?php if ($_SESSION['user_level']==3) { echo 'selected'; } ?> >Manager</option>
                                <option value="4" <?php if ($_SESSION['user_level']==4) { echo 'selected'; } ?> >Operator</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="input-group col-sm-12">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-dark text-white">
                                    Full Name
                                </div>
                            </div>
                            <input type="text" class="form-control bg-dark text-white" id="ep-name" placeholder="Full Name" value="<?php echo $_SESSION['name']; ?>" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="input-group col-sm-12">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-dark text-white">
                                    Address
                                </div>
                            </div>
                            <textarea type="text" class="form-control bg-dark text-white" id="ep-address" placeholder="Address" disabled><?php echo $_SESSION['address']; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="input-group col-sm-12">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-dark text-white">
                                    Phone
                                </div>
                            </div>
                            <input type="text" class="form-control bg-dark text-white" id="ep-phone" placeholder="Phone" value="<?php echo $_SESSION['phone']; ?>" disabled>
                        </div>
                    </div>
                    <div class="form-group col-sm-12">
                        <button id="ebtn-change" class="btn btn-warning btn-sm col-sm-5 offset-sm-1">Change Password</button>
                        <button id="ebtn-update" class="btn btn-success btn-sm col-sm-4 offset-sm-1">Enable Editing</button>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <h5>Account Avatar</h5>
                <div class="eform-container">
                    <div class="row justify-content-center col-sm-12 eimg-container">
                        <img class="img" style="max-width:100px;max-height:100px;" 
                            src="<?php $avatar = '../userdata/img/'.$_SESSION['id'].'-avatar.jpg';
                            echo (file_exists($avatar) ? $avatar : 'assets/img/logo.png');
                            ?>">
                    </div>
                    <div class="row col-sm-12 justify-content-center">
                        <input id="eavatar-file" type="file" class="btn btn-primary btn-sm col-sm-5 ebtn" accept=".jpg, .jpeg">
                        <!-- <button class="btn btn-primary btn-sm col-sm-4 ebtn">Remove Image</button> -->
                    </div>
                    </div class="form-group row">
                        <span>Status: </span><em id="ep-status" style="color:yellow;"><?php echo $_SESSION['status']; ?></em>
                    </div>
                    <div id="imagers">

                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
<script>
    var id = '<?php echo $_SESSION['id']; ?>';
</script>

<script type="text/javascript" src="assets/js/profile.js"></script>