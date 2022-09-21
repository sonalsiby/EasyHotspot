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
        loadContent('view/add_user.php');
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

function addSingleUser() {
    var byte_limit;
    if ($('#es-username').val()=='') {
        iziToast.warning({
            title: 'Empty Username',
            message: 'Username field cannot be empty. Please specify.'
        });
        return;
    }
    if ($('#es-rate-limit').val() == '0') {
        byte_limit = 0;
    } else {
        byte_limit = convertToBytes($('#es-rate-limit').val());
        if (!byte_limit) {
            iziToast.warning({
                title: 'Invalid Data Limit',
                message: 'Please use the correct format. Refer to help text for info.'
            });
            return;
        }
    }
    showLoader();
    var req = $.ajax({
        async: true,
        method: "POST",
        url: 'controller/router_add_user.php',
        data: {
            'name': $('#es-username').val(),
            'psd': $('#es-password').val(),
            'telephone': $('#es-phone').val(),
            'email': $('#es-email').val(),
            'limit_uptime': $('#es-uptime-limit').val(),
            'limit_bytes': byte_limit,
            'profile': $('#es-user-profile').val(),
        }
    }).done(function(html) {
        setStatus('Users created');
        if (html != -1) {
            if ($('#es-email').val() != '') {
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
            if ($('#es-phone').val() != '') {
                $.ajax({
                    async: true,
                    method: "POST",
                    data: {'data': html},
                    url: 'controller/send_sms.php'
                }).done(function(html) {
                    console.log(html);
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
                timeout: false,
                inputs: [
                    ['<select id="ea-print-style" class="" style="margin-right: 10px;">' + 
                        '<option value="voucher">Voucher</option>' + 
                        '<option value="list-1">List-1</option>' +
                        '<option value="list-2">List-2</option>'
                        , 'change', function (instance, toast, input, e) { },true],
                    ['<button class="btn btn-success btn-sm" style="margin-right: 10px;">Print', 'click', function (instance, toast, input, e) {
                        instance.hide({}, toast);
                        voucherConfig(html, $('#ea-print-style').val());
                    },true]
                ]
            });
            loadContent('view/add_user.php');
        };
    });
    window.AXR.push(req);
}

function populateForm(eprofiles) {
    var select = document.getElementById('es-user-profile');
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
    
    setStatus('Profiles loaded');
    hideLoader();
}

$('#epage-add-user').ready(function() {
    showLoader();
    loadProfiles();
    $('#es-add-single-user').on('click', addSingleUser);
});