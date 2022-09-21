<?php
    if ( !isset($_SESSION) ) session_start();
    date_default_timezone_set($_SESSION['time_zone']);
?>

<link rel="stylesheet" href="assets/css/dashboard.css">

<div id="epage-dashboard">
    <?php //echo $_SESSION['user_level'].'||'.$_SESSION['host_id'].'||'.$_SESSION['time_zone']; ?>
    <div class="card-deck">
        <div class="card ecard-blue">
            <div class="card-title">System Date/Time &nbsp;<i class="far fa-clock"></i></div>
            <div class="eline-separator"></div>
            <div class="card-body">
                <div class="etime">
                    <span id="esthour" class="ehour">22</span>:<span id="estminute" class="eminute">35</span>:<span id="estsecond" class="esecond">59</span>
                </div>
                <div class="etimezone">
                    <span class="etzhours"><?php echo date_default_timezone_get(); ?></span> <span class="etzname"><em>  </em></span>
                </div>
                <div>
                    <span class="eday">Router Uptime: &nbsp;&nbsp;</span><span id="erouter-uptime" class="erouter-uptime"></span>
                </div>
            </div>
            <div class="eline-separator"></div>
            <div class="edate-container">
                <div class="edate">
                    <span id="estdate" class="edated">12</span> &nbsp;<span id="estmonth" class="emonth">4</span> &nbsp;<span id="estyear" class="eyear">2019</span>
                    &nbsp;<span id="estday" class="eday"><em>Monday</em></span>
                </div>
            </div>
        </div>
        <div class="card bg-dark">
            <div class="card-title">Users &nbsp;<i class="fas fa-history"></i></div>
            <div class="eline-separator"></div>
            <div class="card-body">
                <div class="euser-count">
                    <label class="euser-count-label">Active Users   ></label>
                    <label id="eactive-users">10</label>
                </div>
                <div class="euser-count">
                    <label class="euser-count-label">Total Users    ></label>
                    <label id="etotal-users">10</label>
                </div>
                <div class="euser-count">
                    <label class="euser-count-label">Uninitiated    ></label>
                    <label id="euninit-users">10</label>
                </div>
                <div class="euser-count">
                    <label class="euser-count-label">Uptime expired ></label>
                    <label id="eexpired-users">10</label>
                </div>
            </div>
        </div>
        <div class="card bg-warning">
            <div class="card-body">
                <canvas id="myChart" width="100%" height="100%"></canvas>
            </div>
        </div>
        <div class="card bg-secondary">
            
        </div>
    </div>
    <div class="card-deck">
        <div class="card ecard-red">
            <div class="card-title">Router system &nbsp;<i class="fas fa-microchip 2x"></i></div>
            <div class="eline-separator"></div>
            <div class="card-body">
                <div>
                    <span>Processor: </span> <span id="eproc-name" class="estminute"></span>
                </div>
                <div>
                    <span id="eproc-cores"></span> <span> Core(s) @ </span> <span id="eproc-freq"> </span> MHz
                </div>
                <div>
                    <span>HDD: </span> <span id="eused-space"></span> <span class="espace-unit"></span>/
                    <span id="etotal-space"></span> <span class="espace-unit"></span> Used
                </div>

            </div>
        </div>
        <div class="card bg-danger">
            <div class="card-title">Performance &nbsp;<i class="fas fa-digital-tachograph"></i></div>
            <div class="eline-separator"></div>
            <div class="card-body">
                <span>CPU Load > </span> <span id="ecpu-load"> 45 </span> %
                <div class="eperf-meter">
                    <span id="ecpu-perf-meter" class="eperf-meter-needle"></span>
                </div>
                <span>Memory > </span> <span id="eused-memory">3.5</span> <span class="ememunit">GB</span>/ 
                <span id="etotal-memory">4</span> <span class="ememunit">GB</span> (U/T)
                <div class="eperf-meter">
                    <span id="emem-perf-meter" class="eperf-meter-needle"></span>
                </div>
            </div>
        </div>
        <div class="card ecard-orange">
            <div class="card-title">Data Throughput (<span id="einterface"></span>) &nbsp;<i class="fas fa-exchange-alt"></i></i></div>
            <div class="eline-separator"></div>
            <div class="card-body" style="display: block;">
                <div class="col-sm-12">
                    <span id="erx-gauge" style="display: inline-block;">
                        RX
                    </span>
                    <span id="etx-gauge" style="display: inline-block;">
                        TX
                    </span>
                </div>
                <div class="espeed-container">
                    <span class="col-sm-6">
                        <span><i class="fas fa-download"></i></span><span id="erx-speed">35</span><span id="erx-unit"> Mb</span>/s
                    </span>
                    <span class="col-sm-6">
                        <span><i class="fas fa-upload"></i></span><span id="etx-speed">35</span><span id="etx-unit"> Mb</span>/s
                    </span>
                </div>
            </div>
        </div>
        <div class="card ecard-magenta">
            <div class="card-title">Router Information &nbsp;<i class="fas fa-info-circle"></i></div>    
            <div class="eline-separator"></div>
            <div class="card-body">
                <div>
                    <span class="einfo-label">Board: </span><span id="erouter-board" class="einfo-text einfo-board"></span>
                </div>
                <div>
                    <span class="einfo-label">Model: </span><span id="erouter-model" class="einfo-text einfo-board"></span>
                </div>
                <div>
                    <span class="einfo-label">Router OS: </span><span id="erouter-os" class="einfo-text einfo-board"></span>
                </div>
            </div>
        </div>
    </div>
    
</div>

<script src="assets/vendor/pureknob/pureknob.js"></script>
<script src="assets/js/dashboard.js"></script>