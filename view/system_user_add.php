<?php if ( !isset($_SESSION) ) session_start(); ?>
<?php
    $process_type = $_GET['type'];
?>
<link rel="stylesheet" href="assets/css/add_multiple_users.css">
<div id="epage-add-user-profile">
    <div class="epage-container">
        <div class="row no-gutter">
            <div class="col-lg-6">
                <div class="eform-container">
                    <div>
                        <div class="form-group row">
                            <label class="form-label col-sm-4" for="em-user-name">User Name <?php echo $_POST['id']; ?></label>
                            <input class="form-control bg-dark text-white col-sm-8" type="email" id="em-user-name" name="user_name" placeholder="Email ID as User Name" aria-describedby="basic-addon2" autofocus required>
			</div>	
			<div class="form-group row">
                            <label class="col-sm-4" for="em-fullname">Full Name</label>
                            <input class="form-control bg-dark text-white col-sm-8" type="text" id="em-fullname" name="full_name" placeholder="Full Name" required>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4" for="em-address">Address</label>
                            <input class="form-control bg-dark text-white col-sm-8" type="text" id="em-address" name="address" placeholder="Address" aria-describedby="basic-addon2" required>
			</div>
			<div class="form-group row">
                            <label class="col-sm-4" for="em-phone">Phone</label>
                            <input class="form-control bg-dark text-white col-sm-8" type="text" id="em-phone" name="phone" placeholder="Mobile Number" required>
                        </div>	
                        <div class="form-group row">    
                            <label class="col-sm-4" for="em-user-level">User Type </label>
                            <select class="form-control bg-dark text-white col-sm-8" id="em-user-level" name="user_level">
                            <?php
                            if (((($_SESSION['user_level'] == 1) OR ($_SESSION['user_level'] == 2)) AND $_SESSION['host_id'] != 0) OR ($_SESSION['user_level'] == 3)) {
                                echo '<option value="4">Hotspot Executive</option>';
                            } 
                            if ((($_SESSION['user_level'] == 1) OR ($_SESSION['user_level'] == 2)) AND $_SESSION['host_id'] != 0) {   
                                echo '<option value="3">Hotspot Manager</option>';
                            }
                            if ($_SESSION['user_level'] == 1) {
	                        echo '<option value="2">Administrator</option>';   
                                echo '<option value="1">Super Admin</option>';
                            }
                            ?>     
                            </select>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4" for="em-user-status">User Status</label>
                            <select class="form-control bg-dark text-white col-sm-8" id="em-user-status" name="user_status">
                                <option value="Active">Active</option>
				<option value="Inactive">Inactive</option>
                            </select>
			</div>	
			<div class="form-group row">
                <?php
                if ($process_type=='add') {
                    echo '<button class="btn btn-warning btn-sm col-sm-3 offset-sm-5" id="back-btn">Go Back</button>
                    <button class="btn btn-success btn-sm col-sm-3 offset-sm-1" id="eprocess-btn">Add User</button>';
                } else {
                    echo '<button class="btn btn-warning btn-sm col-sm-3 offset-sm-1" id="back-btn">Go Back</button>
                    <button class="btn btn-danger btn-sm col-sm-3 offset-sm-1" id="ereset-btn">Reset PW</button>
				    <button class="btn btn-success btn-sm col-sm-3 offset-sm-1" id="eprocess-btn">Add User</button>';
                }
                ?>
			</div>
		</div>
                </div>
            </div>
            <div class="col-lg-6">
                
            </div>
        </div>
    </div>  
</div>
<script type="text/javascript">
    var process_type = '<?php echo $process_type; ?>';
    var id = "<?php echo (!isset($_POST['id']) ? '0' : $_POST['id']); ?>";
</script>
<script type="text/javascript" src="assets/js/system_user_add.js">
