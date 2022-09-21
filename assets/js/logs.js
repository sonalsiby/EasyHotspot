var log_table = null;

function styleLogTable() {
    $('table').attr('class', 'table table-sm row-border hover table-dark');
    $('table').attr('id', 'euser-table');
    log_table = $('#euser-table').DataTable({
        select: true,
        processing: true,
        initComplete : function(settings, json) {
            hideLoader();
        },
        buttons: [
            'colvis', 'selectAll', 'selectNone','copy', 'pdf', 'print'
        ],
    });
    log_table.buttons().container().appendTo( $('.col-md-6:eq(0)', log_table.table().container() ) );

    log_table.on('select deselect', function() {
        if (log_table.rows( { selected: true } ).indexes().length === 0) {
            log_table.buttons().disable();
        } else {
            log_table.buttons().enable();
        }
    });

    log_table.on('processing.dt', function(e, settings, processing) {
        if (processing) {
            showLoader();
        } else {
            hideLoader();
        }
    });

    switch (log_type) {
        case 'all':
            break;

        case 'system':
            log_table.search('system').draw();
            break;

        case 'hotspot':
            log_table.search('hotspot').draw();
            break;

        case 'dhcp':
            log_table.search('dhcp').draw();
            break;
    }
}

function generateLogs(data, log_type) {
    setStatus('Generating log tables ...');
    var req = $.ajax({
        async: true,
        method:"POST",
        url: 'controller/generate_table.php',
        data: {'data': data}
    }).done(function(html) {
        $('#edraw-logs').html(html);
        styleLogTable();
        setStatus('Logs loaded successfully');
    });
    window.AXR.push(req);
}


$('epage-logs').ready(function() {
    showLoader();
    setStatus('Fetching logs from router ...');
    var req = $.ajax({
        async: true,
        method: "POST",
        url: 'controller/router_logs.php'
    }).done(function(html) {
        try {
            JSON.parse(html);
        } catch (err) {
            iziToast.error({
                title: 'Error',
                message: 'Router not responding. Please try again',
            });
            $('#edashboard').trigger('click');
            return;
        }
        generateLogs(html, log_type);
    }).fail(function() {
        setStatus('Log retrieval failed');
    });
    window.AXR.push(req);
});