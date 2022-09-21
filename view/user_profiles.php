<?php if ( !isset($_SESSION) ) session_start(); ?>

<link rel="stylesheet" href="assets/css/user_profiles.css">

<script type="text/javascript">
    var is_loaded = <?php if (isset($_POST['profiles']))  { echo 'true'; } else { echo 'false';} ?>;
</script>

<?php
    if (isset($_POST['profiles'])) {
        $raw_profiles = json_decode($_POST['profiles'], true);
        $profile_data = $raw_profiles['data'];
    }   
?>

<div id="epage-user-profiles">
    <h4>Router User Profiles</h4>

<?php
    if (isset($_POST['profiles'])) {
        $i = 0;
        while ($i <= count($profile_data)) {
            $j = $i;
            $exit = false;
            echo '<div class="card-deck">';
            while (($j < ($i + 4)) && ($j <= count($profile_data)))  {
                if ($j == count($profile_data)) {
                    echo'<div class="card bg-success col-md-3">
                        <div class="card-body">
                            <div class="card-title">
                                <h5> <strong>Add New Profile</strong> </h5>
                                <div class="eline-separator"></div>
                            </div>
                            <div class="card-text text-center align-self-center">
                                <a href="#" id="eadd-profile" style="text-decoration: none; color: white;"><i class="fa fa-plus fa-5x" aria-hidden="true"></i></a>
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
                            <h5> <strong>'.$profile_data[$j]['name'].'</strong> </h5>
                            <div class="eline-separator"></div>
                        </div>
                        <div class="card-text">
                            <div>
                                <label class="">Session Timeout: </label> <span class="">'.$profile_data[$j]['session_timeout'].'</span>
                            </div>
                            <div>
                                <label class="">Keep Alive Timeout: </label> <span class="">'.$profile_data[$j]['keepalive_timeout'].'</span>
                            </div>
                            <div>
                                <label class="">Shared Users: </label> <span class="">'.$profile_data[$j]['shared_users'].'</span>
                            </div>
                            <div>
                                <label class="">Rate Limit (U/D): </label> <span class="">'.$profile_data[$j]['rate_limit'].'</span>
                            </div>
                            <div>
                                <label class="">MAC Cookie Timeout: </label> <span class="">'.$profile_data[$j]['mac_cookie_timeout'].'</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="form-inline">
                            <div class="row col-sm-12">
                                <button id="cv-'.$profile_data[$j]['name'].'" class="btn btn-sm btn-warning">Edit</button>
                                <button id="dl-'.$profile_data[$j]['name'].'" class="btn btn-sm btn-danger">Delete</button>
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

<script type="text/javascript" src="assets/js/user_profiles.js">