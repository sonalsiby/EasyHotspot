var eml_set;
var sms_set;
var app_set;
var complet = 0;

function updateAutoSend() {
    var sm = ($('#auto-sms').prop('checked') ? 1 : 0);
    var em = ($('#auto-email').prop('checked') ? 1 : 0);
    $.ajax({
        async: true,
        method: 'POST',
        url: 'model/db_update_app_sett.php',
        data: {
            'auto-sms': sm,
            'auto-email': em
        }
    });
}

function setValues() {
    $('#es-api-url').val(sms_set['api_url']);
    $('#es-par-1').val(sms_set['param_name1']);
    $('#es-par-2').val(sms_set['param_name2']);
    $('#es-par-3').val(sms_set['param_name3']);
    $('#es-par-4').val(sms_set['param_name4']);
    $('#es-par-5').val(sms_set['param_name5']);
    $('#es-par-6').val(sms_set['param_name6']);
    $('#es-val-1').val(sms_set['param_value1']);
    $('#es-val-2').val(sms_set['param_value2']);
    $('#es-val-3').val(sms_set['param_value3']);
    $('#es-val-4').val(sms_set['param_value4']);
    $('#es-val-5').val(sms_set['param_value5']);
    $('#es-val-6').val(sms_set['param_value6']);
    
    $('#ee-hostname').val(eml_set['host_name']);
    $('#ee-port').val(eml_set['port_no']);
    $('#ee-username').val(eml_set['username']);
    $('#ee-password').val(eml_set['password']);

    $('#'+eml_set['protocol']).attr('selected', true);
    $('#'+eml_set['smtp_security']).attr('selected', true);
    $('#'+eml_set['smtp_authentication']).attr('selected', true);

    if (app_set['autosend_email'] == 1) {
        $('#auto-email').prop('checked', true);
    }
    if (app_set['autosend_sms'] == 1) {
        $('#auto-sms').prop('checked', true);
    }
}

function loadDone() {
    setValues();
    hideLoader();
    setStatus('Settings loaded');
}

function fetchValues() {
    showLoader();
    setStatus('Retrieving values...');
    var req = $.ajax({
        async: true,
        method: 'POST',
        url: 'model/db_fetch_sms_email.php'
    }).done(function(html) {
        var combined = JSON.parse(html)['data'];
        eml_set = combined[0];
        sms_set = combined[1];
        complet++;
        if (complet == 2) {
            loadDone();
        }
    });
    window.AXR.push(req);
    req = $.ajax({
        async: true,
        method: 'POST',
        url: 'model/db_fetch_app_sett.php'
    }).done(function(html) {
        app_set = JSON.parse(html)['data'][0];
        complet++;
        if (complet == 2) {
            loadDone();
        }
    });
    window.AXR.push(req);
}

function updateSettings() {
    var params = {
        'api_url': $('#es-api-url').val(),
        'param_name1': $('#es-par-1').val(),
        'param_name2': $('#es-par-2').val(),
        'param_name3': $('#es-par-3').val(),
        'param_name4': $('#es-par-4').val(),
        'param_name5': $('#es-par-5').val(),
        'param_name6': $('#es-par-6').val(),
        'param_value1': $('#es-val-1').val(),
        'param_value2': $('#es-val-2').val(),
        'param_value3': $('#es-val-3').val(),
        'param_value4': $('#es-val-4').val(),
        'param_value5': $('#es-val-5').val(),
        'param_value6': $('#es-val-6').val(),

        'host_name': $('#ee-hostname').val(),
        'port_no': $('#ee-port').val(),
        'username': $('#ee-username').val(),
        'password': $('#ee-password').val(),
        'protocol': $('#ee-protocol').val(),
        'smtp_security': $('#ee-security').val(),
        'smtp_authentication': $('#ee-auth').val(),
    }
    $.ajax({
        async: true,
        method: 'POST',
        url: 'model/db_update_sms_email.php',
        data: params
    }).done(function(html) {
        if (html==0) {
            iziToast.success({
                title: 'Settings Update',
                message: 'Values saved successfully'
            });
            loadContent('view/sms_email_settings.php');
        }
    });
}

$('#epage-sem-settings').ready(function() {
    fetchValues();
    $('#update-ems').on('click', updateSettings);
    $('#auto-sms').on('change', updateAutoSend);
    $('#auto-email').on('change', updateAutoSend);
});