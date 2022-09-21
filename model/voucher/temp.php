
<style>
    .card {
        color: black;
    }

    .card-body {
        padding: 20px;
        background-color: lightblue;
    }

    .card-footer {
        margin: 0;
        padding: 0;
    }

    #ev-logo {
        width: 30px;
        height: 30px;
        margin: 3px;
    }

    .ev-address {
        text-align: center;
        font-size: 0.7em
    }

    .ev-company {
        font-size: 1em;
        text-align: center;
    }

    .ev-title {
        font-size: 0.9em;
        text-align: center;
        font-weight: bold;
    }

    .ev-footer-text {
        font-size: 0.6em;
    }
</style>


<div class="card">
    <div class="card-body">
        <div class="card-title">
            <div class="row">
                <div class="">
                    <img id="ev-logo" src="<?php echo $voucher_logo; ?>">
                </div>
                <div>
                    <span class="ev-company"><strong>Zetozone Technologies (I) Pvt. Ltd.</strong></span>
                </div>
            </div>
            <div class="ev-address">
                Holiday Home Junction, Kumily, Idukki, Kerala<br>
                Phone: 9020150150, E-mail: sibyperiyar@gmail.com
            </div>
            <div class="ev-title">
                <span>WiFi Hotspot Voucher</span>
            </div>
        </div>
        <div class="card-text">
            <table class="table table-sm">
                <tr>
                    <th>Username</th>
                    <th>Password</th>
                </tr>
                <tr>
                    <td><?php echo $username; ?></td>
                    <td><?php echo $password; ?></td>
                </tr>
            </table>
        </div>
        <div class="card-footer">
            <span class="ev-footer-text col-sm-6">Validity : <span><?php echo $profile; ?></span></span>
            <span class="ev-footer-text col-sm-6">Login IP: <span>10.45.1.1</span></span>
        </div>
    </div>
</div>
