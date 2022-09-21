var eprofiles = null;
var eprofile_data = [];

function convertToBytes(value) {
    var unit = value.slice(-1);
    var size = Number(value.slice(0,-1));
    switch (unit) {
        case 'K':
            size *= (1024);
            break;

        case 'M':
            size *= (1024 * 1024);
            break;

        case 'G':
            size *= (1024 * 1024 * 1024);
            break;

        default: 
            return null;
    }
    return size;
}

function displayForPrint(html) {
    setStatus('Initiating print ...');
    var req = $.ajax({
        async: true,
        method: "POST",
        data: {
            'filename': 'temp_print'+id+'.php',
            'data': html
        },
        url: 'model/voucher/set_voucher.php'
    }).done(function(html) {
        hideLoader();
        setStatus('Print generated')
        loadContent('view/add_multiple_users.php');
        document.getElementById('ev-print').click();
    });
    window.AXR.push(req);
}

function voucherConfig(html, type) {
    setStatus('Generating vouchers ...');
    var req = $.ajax({
        async: true,
        method: "POST",
        data: {
            'users': html,
            'type': type
        },
        url: 'model/voucher/voucher_config.php'
    }).done(function (html) {
        displayForPrint(html);
    });
    window.AXR.push(req);
}

function addMultipleUsers() {
    var byte_limit;
    if ($('#em-username').val()=='') {
        iziToast.warning({
            title: 'Empty Username Prefix',
            message: 'Username prefix field cannot be empty. Please specify.'
        });
        return;
    }
    if ($('#em-data-limit').val() == '0') {
        byte_limit = 0;
    } else {
        byte_limit = convertToBytes($('#em-data-limit').val());
        if (!byte_limit) {
            iziToast.warning({
                title: 'Invalid Data Limit',
                message: 'Please use the correct format. Refer to help text for info.'
            });
            return;
        }
    }
    showLoader();
    setStatus('Creating users ...');
    var req = $.ajax({
        async: true,
        method: "POST",
        url: 'controller/router_add_multiple_users.php',
        data: {
            'no_of_users': $('#euser-count').val(),
            'pass_length': $('#epassword-length').val(),
            'user_prefix': $('#euname-prefix').val(),
            'limit_uptime': $('#es-uptime-limit').val(),
            'limit_bytes': byte_limit,
            'profile': $('#em-user-profile').val(),
            'same_pass': $('#epassword-comb').val(),
            'pass_type': $('#epassword-pattern').val(),
            'telephone': $('#ephone').val(),
            'email': $('#eemail').val(),
        }
    }).done(function(html) {
        if (html != -1) {
            setStatus('Users created');
            if ($('#eemail').val() != '') {
                $.ajax({
                    async: true,
                    method: "POST",
                    data: {'data': html},
                    url: 'controller/send_email.php'
                }).done(function(html) {
                    if (html == '0') {
                        iziToast.success({
                            title: 'Autosend E-mail',
                            message: 'Credentials sent to client via e-mail'
                        });
                    }
                });
            }
            if ($('#ephone').val() != '') {
                $.ajax({
                    async: true,
                    method: "POST",
                    data: {'data': html},
                    url: 'controller/send_sms.php'
                }).done(function(html) {
                    if (html == '0') {
                        iziToast.success({
                            title: 'Autosend SMS',
                            message: 'Credentials sent to client via SMS'
                        });
                    }
                });
            }
            iziToast.info({
                title: 'Print',
                message: 'Generate print now ?',
                overlay: true,
                drag: false,
                position: 'center',
                zindex: 999,
                inputs: [
                    ['<select id="em-print-style" class="" style="margin-right: 10px;">' + 
                        '<option value="voucher">Voucher</option>' + 
                        '<option value="list-1">List-1</option>' +
                        '<option value="list-2">List-2</option>'
                        , 'change', function (instance, toast, input, e) { },true],
                    ['<button class="btn btn-success btn-sm" style="margin-right: 10px;">Print', 'click', function (instance, toast, input, e) {
                        instance.hide({}, toast);
                        voucherConfig(html, $('#em-print-style').val());
                    },true]
                ]
            });
            loadContent('view/add_multiple_users.php');
        }
    });
    window.AXR.push(req);
}

function populateForm(eprofiles) {
    var select = document.getElementById('em-user-profile');
    for (var i=0; i<eprofiles.length; i++) {
        var opt = document.createElement('option');
        opt.value = eprofiles[i]['name'];
        opt.innerHTML = eprofiles[i]['name']
        try {
            select.appendChild(opt);
        } catch (err) {
            //
        }

        eprofile_data[eprofiles[i]['name']] = {
            'rate_limit': eprofiles[i]['rate_limit'],
            'uptime_limit': eprofiles[i]['validity'],
        }
    }
    setStatus('Profiles loaded');
    hideLoader();
}

function loadProfiles() {
    setStatus('Loading profiles from router ...');
    if (window.profiles == null) {
        var req = $.ajax({
            async: true,
            method: "POST",
            url: 'controller/router_list_profiles.php'
        }).done(function(html) {
            try {
                eprofiles = JSON.parse(html)['data'];
                window.profiles = eprofiles;
                populateForm(eprofiles);
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
    } else {
        eprofiles = window.profiles;
        populateForm(eprofiles);
    }
}

$('#epage-add-multiple-users').ready(function() {
    showLoader();
    loadProfiles();
    $('#em-add-multiple-users').on('click', addMultipleUsers);

    $('#epassword-comb').on('change', function() {
        if (this.value == '1') {
            $('#epassword-controls').fadeOut()
        } else {
            $('#epassword-controls').fadeIn();
        }
    });
});