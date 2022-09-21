var status = 0; // 0 - inactive

function validateEmail(email) {
    var re = new RegExp('/(.+)@(.+){2,}\.(.+){2,}/');
    return re.test(email);
}

function addHotspot() {
    var params = {};

    var company_name = $('#eacompany-name').val();
    if (!company_name) {
        iziAlert('Company name cannot be empty');
        return;
    }
    params['company_name'] = company_name;
    var company_address = $('#eacompany-address').val();
    if (!company_address) {
        iziAlert('Company address cannot be empty');
        return;
    }
    params['company_address'] = company_address;
    var company_contact = $('#eacompany-contact').val();
    if (!company_contact) {
        iziAlert('Company contact cannot be empty');
        return;
    }
    params['company_contact'] = company_contact;
    var company_email = $('#eacompany-email').val();
    if (!company_email) {
        iziAlert('Company email cannot be empty');
        return;
    }
    params['company_email'] = company_email;
    var company_phone = $('#eacompany-phone').val();
    if (!company_phone) {
        iziAlert('Company phone cannot be empty');
        return;
    }
    params['company_phone'] = company_phone;
    var hotspot_name = $('#eahotspot-name').val();
    if (!hotspot_name) {
        iziAlert('Hotspot name cannot be empty');
        return;
    }
    params['hotspot_name'] = hotspot_name;
    var hotspot_ip = $('#eahotspot-ip').val();
    if (!hotspot_ip) {
        iziAlert('Hotspot IP cannot be empty');
        return;
    }
    params['hotspot_ip'] = hotspot_ip;
    var hotspot_host = $('#eahotspot-host').val();
    if (!hotspot_host) {
        iziAlert('Hostname cannot be empty');
        return;
    }
    params['hotspot_host'] = hotspot_host;
    var hotspot_username = $('#eahotspot-username').val();
    if (!hotspot_username) {
        iziAlert('Hotspot username cannot be empty');
        return;
    }
    params['hotspot_username'] = hotspot_username;
    var hotspot_password = $('#eahotspot-password').val();
    if (!hotspot_password) {
        iziAlert('Hotspot password cannot be empty');
        return;
    }
    params['hotspot_password'] = hotspot_password;
    var validity = $('#eahotspot-validity').val();
    console.log(validity);
    params['validity'] = validity;
    params['active'] = status;

    $.ajax({
        async: true,
        method: "POST",
        url: 'model/db_add_hotspot.php',
        data: params,
    }).done(function(html) {
        if (html==0) {
            iziToast.success({
                title: 'Add Hotspot',
                message: 'Hotspot added successfully'
            });
            loadContent('view/eahotspots.php?type=all');
        }
    });
}

function iziAlert(message) {
    iziToast.error({
        title: 'Add Hotspot',
        message: message
    });
}

function active() {
    $('#ea-status').removeClass('btn-danger');
    $('#ea-status').addClass('btn-success');
    $('#ea-status').html('Status: Active');
    status = 1;
}

function inactive() {
    $('#ea-status').removeClass('btn-success');
    $('#ea-status').addClass('btn-danger');
    $('#ea-status').html('Status: Inactive');
    status = 0;
}

$('#eapage-hotspot-details').ready(function() {
    $('#ea-back').on('click', function() {
        loadContent('view/eahotspots.php');
    });
    $('#ea-status').on('click', function() {
        if (status==1) {
            inactive();
        } else {
            active();
        }
    });
    $('#ea-add').on('click', function() {
        addHotspot();
    });
    inactive();
});