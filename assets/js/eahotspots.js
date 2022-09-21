var date = null;
var user_table = null;

function styleTable() {
    $('table').attr('class', 'table table-sm row-border hover table-dark');
    $('table').attr('id', 'euser-table');
    user_table = $('#euser-table').DataTable({
        select: true,
        processing: true,
        initComplete : function(settings, json) {
            hideLoader();
        },
        "buttons": {
            "dom": {
                "button": {
                    "tag": "button",
                    "className": "btn btn-primary btn-sm"
                }
            }
        },
        buttons: [
            {
                extend: 'colvis',
                className: 'btn-sm'
            },
            {
                extend: 'selectAll',
                className: 'btn-sm'
            },
            {
                extend: 'selectNone',
                className: 'btn-sm'
            },
            {
                extend: 'copy',
                className: 'btn-sm'
            },
            {
                extend: 'pdf',
                className: 'btn-sm'
            },
            {
                extend: 'print',
                className: 'btn-sm'
            },
        ]
    });

    user_table.buttons().container().appendTo($('.col-md-6:eq(0)', user_table.table().container()));

    $('#euser-table').on('dblclick', 'tr',  function() {
        var data = user_table.row(this).data()[0];
        loadContent('view/eahotspot_details.php', {'id': data});
    });

    user_table.on('select deselect', function() {
        if ( user_table.rows( { selected: true } ).indexes().length === 0 ) {
            user_table.buttons().disable();
        }
        else {
            user_table.buttons().enable();
        }
    });

    user_table.buttons().disable();
}

function generateTable(data) {
    setStatus('Generating table ...');
    var req = $.ajax({
        async: true,
        method:"POST",
        url: 'controller/generate_table.php',
        data: {'data': data}
    }).done(function(html) {
        $('#eahotspot-canvas').html(html);
        styleTable();
        setStatus('Table loaded');
        hideLoader();
    });
    window.AXR.push(req);
}

function initializeToast(data) {
    if(data.detail.class == 'expiry'){
        var now = new Date();
        var day = ("0" + now.getDate()).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);
        var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
        $('#ex-date').val(today);
    }
}

function fetchHotspots(date) {
    showLoader();
    setStatus('Fetching hotspot list ...');
    var req = $.ajax({
        aync: true,
        method: "POST",
        data: {
            'type': hotspot_type,
            'date': date
        },
        url: 'model/db_fetch_hotspots.php'
    }).done(function(html) {
        if (JSON.parse(html)['data']==null) {
            iziToast.error({
                title: 'List Router Users',
                message: 'No hotspots found in database'
            });
            $('#eahotspot-canvas').html("There are no hotspots to display for you. Please contact Super Admin to assign a hotspot.");
            hideLoader();
        } else {
            generateTable(html);
        }
    });
    window.AXR.push(req);
}

$('#eahotspots').ready(function() {
    // hotspot_type is assigned in list_users.php from GET
    document.addEventListener('iziToast-opened', initializeToast)
    if (hotspot_type == 'expiring') {
        showLoader();
        iziToast.warning({
            title: 'List Hotspots',
            message: 'Expiring by: ',
            class: 'expiry',
            overlay: true,
            drag: false,
            zindex: 999,
            displayMode: 'once',
            position: 'center',
            timeout: false,
            inputs: [
                ['<input type="date" id="ex-date" style="margin-right: 10px;">', 'change', function(instance, toast, input, e) {
                }],
                ['<button class="btn btn-primary" style="margin-right: 10px;" id="update-hotspot"> OK', 'click', function (instance, toast, input, e) {
                    instance.hide({}, toast);
                    date = $('#ex-date').val();
                    $('#ea-hotspots-title').html('Hotspot List (expiring by '+ date +')');
                    fetchHotspots(date);
                }, true]
            ]
        });
    } else {
        fetchHotspots(date);
    }
});