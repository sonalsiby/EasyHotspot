<link rel="stylesheet" href="../assets/css/user_profiles.css">

<script type="text/javascript">
    var is_loaded = <?php if (isset($_POST['users']))  { echo 'true'; } else { echo 'false';} ?>;
</script>

<?php
    if ( !isset($_SESSION) ) session_start(); 
    if (isset($_POST['users'])) {
        $sys_user_data = json_decode($_POST['users'], true);
        $system_users = $sys_user_data['data'];
    }   
?>

<div id="epage-system-users">
    <h4>System Users <?php echo $_SESSION['user_level'].'||'.$_SESSION['host_id']; ?></h4>

<?php
    if (isset($_POST['users'])) {
        $i = 0;
        while ($i <= count($system_users)) {
            $j = $i;
            $exit = false;
            echo '<div class="card-deck">';
            while (($j < ($i + 4)) && ($j <= count($system_users)))  {
                if ($j == count($system_users)) {
                    echo'<div class="card bg-success col-md-3">
                        <div class="card-body">
                            <div class="card-title">
                                <h5> <strong>Add New User</strong> </h5>
                                <div class="eline-separator"></div>
                            </div>
                            <div class="card-text text-center align-self-center">
                                <a href="#" id="eadd-user" style="text-decoration: none; color: white;"><i class="fa fa-plus fa-5x" aria-hidden="true"></i></a>
                            </div>
                        </div>
                    </div>';
                    $j++;
                    while ($j < ($i + 4)) {
                        echo'<div class="card bg-dark col-md-3">
                            <div class="card-body">
                                <div class="card-title"></div>
                                <div class="card-text text-center align-self-center"></div>
                            </div>
                        </div>';
                        $j++;
                    }
                    $exit = true;
                    break;
                }
                echo '
                <div class="card bg-dark col-md-3">
                    <div class="card-body">
                        <div class="card-title">
                            <h5> <strong>User : '.$system_users[$j]['username'].'</strong> </h5>
                            <div class="eline-separator"></div>
                        </div>
                        <div class="card-text">
                            <div>
                                <label class="">Created On: </label> <span class="">'.date('d-m-Y H:i:s', strtotime($system_users[$j]['created_on'])).'</span>
                            </div>
                            <div>
                                <label class="">Name: </label> <span class="">'.$system_users[$j]['name'].'</span>
                            </div>
                            <div>
                                <label class="">Address: </label> <span class="">'.$system_users[$j]['address'].'</span>
                            </div>
                            <div>
                                <label class="">Phone: </label> <span class="">'.$system_users[$j]['phone'].'</span>
                            </div>
                            <div>
                                <label class="">Email: </label> <span class="">'.$system_users[$j]['email'].'</span>
                            </div>
                            <div>
                                <label class="">Status: </label> <span class="">'.$system_users[$j]['status'].'</span>
                            </div>';
                            switch ($system_users[$j]['user_level']) {
				case 1 :
					$user_type = 'Super Admin';
					break;
				case 2 :
					$user_type = 'Administrator';
					break;
				case 3 :
					$user_type = 'Hotspot Manager';
					break;
				case 4 :
					$user_type = 'Hotspot Executive';
				}
			echo '
                            <div>
                                <label class="">User Type: </label> <span class="">'.$user_type.'</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="form-inline">
                            <div class="row">';
                                if ($system_users[$j]['status']=='Active') {
                                    echo '<button id="st-'.$system_users[$j]['id'].'" class="btn btn-sm btn-success">Active</button>';
                                } else {
                                    echo '<button id="st-'.$system_users[$j]['id'].'" class="btn btn-sm btn-danger">Inactive</button>';
                                }

                                echo '<button id="zx-'.$system_users[$j]['id'].'" class="btn btn-sm btn-primary">Edit</button>
                                <button id="dl-'.$system_users[$j]['username'].'" class="btn btn-sm btn-danger">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>';
                $j++;
            }
            echo '</div>';
            $i = $j;
            if ($exit) {
                break;
            }
        }
    }
?>
</div>

<script type="text/javascript" src="assets/js/system_users.js">
