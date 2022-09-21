function processProfile() {
    console.log($('#em-session-timeout').val());
    showLoader();
    if (process_type == 'add') {
        var url = 'controller/router_add_profile.php';
    } else if (process_type == 'edit') {
        var url = 'controller/router_update_profile.php';
    }
    var req = $.ajax({
        async: true,
        method: "POST",
        url: url,
        data: {
            'profile_name': $('#em-profile-name').val(),
            'session_timeout': $('#em-session-timeout').val(),
            'shared_users': $('#em-shared-users').val(),
            'mac_cookie_timeout': $('#em-mac-timeout').val(),
            'keep_alive_timeout': $('#em-keepalive-timeout').val(),
            'rx_rate_limit': $('#rx_rate_limit').val(),
            'tx_rate_limit': $('#tx_rate_limit').val(),
            'validity': $('#em-validity').val(),
            'grace_period': $('#em-grace-period').val(),
            'on_expiry': $('#em-on-expiry').val(),
            'lock_user': $('#em-lock-user').val()
        }
    }).done(function(html) {
        if (html == '2') {
            iziToast.success({
                title: 'User Profile',
                message: 'Profile saved successfully.'
            });
            loadContent('view/user_profiles.php');
        }
    });
    window.AXR.push(req);
}

function retrieveProfile() {
    var req = $.ajax({
        async: true,
        method: "POST",
        url: 'controller/router_list_profiles.php'
    }).done(function(html) {
        try {
            var all = JSON.parse(html)['data'];
            var cur = null;
            for (var i=0; i < all.length; i++) {
                if (all[i]['name'] == pid) {
                    cur = all[i];
                    break;
                }
            }
            $('#em-profile-name').val(cur['name']);
            $('#em-session-timeout').val(cur['session_timeout']);
            $('#em-shared-users').val(cur['shared_users']);
            $('#em-mac-timeout').val(cur['mac_cookie_timeout']);
            $('#em-keepalive-timeout').val(cur['keepalive_timeout']);
            $('#em-validity').val(cur['validity']);
            $('#'+cur['on_login']).attr('selected', 'selected');

            var rxtx = cur['rate_limit'].split('/');
            var rx = rxtx[0].slice(0,-1);
            var rxu = rxtx[0].slice(-1);
            var tx = rxtx[1].slice(0,-1);
            var txu = rxtx[0].slice(-1);

            $('#em-rx-rate').val(rx);
            $('#em-tx-rate').val(tx);
            $('#'+rxu+'r').attr('selected', 'selected');
            $('#'+txu+'t').attr('selected', 'selected');

            hideLoader();
        } catch (err) {
            iziToast.error({
                title: 'Error',
                message: 'Router not responding. Please try again',
            });
            $('#edashboard').trigger('click');
            return;
        }
    });
    window.AXR.push(req);
}

function updateUnits() {
    $('#rx_rate_limit').val($('#em-rx-rate').val() + $('#em-rx-unit').val());
    $('#tx_rate_limit').val($('#em-tx-rate').val() + $('#em-tx-unit').val());
}

$('#epage-add-user-profile').ready(function() {
    if (process_type == 'edit') {
        showLoader();
        retrieveProfile();
        $('#em-profile-name').prop({disabled: true});
    }
    $('#em-rx-rate').on('change', updateUnits);
    $('#em-rx-unit').on('change', updateUnits);
    $('#em-tx-rate').on('change', updateUnits);
    $('#em-tx-unit').on('change', updateUnits);
    $('#eprocess-btn').on('click', processProfile);
    $('#em-lock-user').on('change', function() {
        if ($('#em-lock-user').val() == 'Yes') {
            $('#em-shared-users').val(1);
        }
    });
    $('#em-shared-users').on('change', function() {
        if ($('#em-shared-users').val() > 1) {
            $('#No').prop({selected: true});
        }
    });
    $('#em-shared-users').trigger('change');
});