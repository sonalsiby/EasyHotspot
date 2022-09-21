function setValues() {
    var req = $.ajax({
        method: "POST",
        async: true,
        url: 'model/db_fetch_app_sett.php'
    }).done(function(html) {
        var data = JSON.parse(html)['data'][0];
        $('#ev-per-row').val(data['voucher_row']);
        document.getElementById(data['time_zone']).selected = 'selected';
        document.getElementById(data['date_format']).selected = 'selected';
    });
    window.AXR.push(req);
}    

function uploadFile() {
    showLoader();
    var formData = new FormData();
    var ifile = document.getElementById('ecompany-file').files[0];
    formData.append('ecompany-file', ifile);
    $.ajax({
        method: "POST",
        async: true,
        data: formData,
        url: 'model/im_company.php',
        contentType: false,
        processData: false
    }).done(function(html) {
        loadContent('view/application_settings.php');
    });
}

function updateAppSettings() {
    $.ajax({
        async: true,
        method: "POST",
        data: {
            'timezone': document.getElementById('etimezone').value,
            'date-format': document.getElementById('edate-format').value,
            'voucher-row': $('#ev-per-row').val()
        },
        url: 'model/db_update_app_sett.php'
    }).done(function(html) {
        if (html == 0) {
            iziToast.success({
                title: 'Settings Update',
                message: 'Application settings saved successfully'
            });
            loadContent('view/application_settings.php');
        }
    });
}

$('#epage-app-settings').ready(function() {
    setValues();
    $('#ecompany-file').bind('change', uploadFile);
    $('#ebtn-update-app').on('click', updateAppSettings);
});