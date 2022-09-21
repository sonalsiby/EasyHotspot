var user_table = null;
var deleteCount;
var routerFile;

function deleteSelected(to_delete) {
    setStatus('Initializing deletion process');
    deleteCount = to_delete.length;
    user_table.buttons().disable();
    for (var i=0; i < to_delete.length; i++) {
        $.ajax({
            async: true,
            method: "POST",
            data: {'username': to_delete[i]},
            url: 'controller/router_remove_user.php'
        }).done(function() {
            deleteCount--;
            setStatus('User Deletion :: ' + deleteCount + ' user(s) left to delete.');
            if (deleteCount == 0) {
                iziToast.destroy();
                hideLoader();
                setStatus('Selected users deleted')
                iziToast.success({
                    title: 'User Deletion',
                    message: 'Successfully removed selected user(s).'
                });
            }
        });
    }

    user_table.draw();

    toast = iziToast.show({
        theme: 'dark',
        icon: 'icon-person',
        title: 'Deleting Users',
        overlay: true,
        zindex: 999,
        message: 'The deletion process is running. Please wait...',
        position: 'center',
        progressBar: false,
        close: false,
        drag: false,
        timeout: false,
        inputs: [
            ['<button class="btn btn-danger" style="margin-right: 10px;" id="1">Cancel', 'click', function (instance, toast, input, e) {
                hideLoader();
                instance.hide({}, toast);
                window.stop();
            }, true]
        ]
    });
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

function fetchUsers(to_print, type, sms, email) {
    setStatus('Fetching user data from router ...');
    showLoader();
    var req = $.ajax({
        async: true,
        method: "POST",
        data: {
            'user_list': to_print
        },
        url: 'controller/router_fetch_user.php'
    }).done(function(html) {
        var fusers = JSON.parse(html)['data'];
        setStatus('Used data fetched');
        if (sms != null) {
            for (var i=0; i < fusers.length; i++) {
                if (!fusers[i]['comment']) {
                    fusers.splice(i, 1);
                    i--;
                }
            }
            data = {"data": fusers};
            $.ajax({
                async: true,
                method: "POST",
                data: {'data': JSON.stringify(data), 'manual': 'true'},
                url: 'controller/send_sms.php'
            }).done(function(html) {
                if (html == '0') {
                    iziToast.success({
                        title: 'SMS',
                        message: 'Credentials sent for ' + fusers.length + ' via SMS'
                    });
                }
            });
            hideLoader();
        }
        if (email != null) {
            for (var i=0; i < fusers.length; i++) {
                if (!fusers[i]['email']) {
                    fusers.splice(i, 1);
                    i--;
                }
            }
            data = {"data": fusers};
            $.ajax({
                async: true,
                method: "POST",
                data: {'data': JSON.stringify(data), 'manual': 'true'},
                url: 'controller/send_email.php'
            }).done(function(html) {
                if (html == '0') {
                    iziToast.success({
                        title: 'E-mail',
                        message: 'Credentials sent for ' + fusers.length + ' via e-mail'
                    });
                }
            });
            hideLoader();
        }
        if (type != null) {
            voucherConfig(html, type);
        }
    });
    window.AXR.push(req);
}

function validateSelection() {
    if (user_type == 'expired') {
        iziToast.error({
            timeout: 4000,
            title: 'Users expired',
            message: 'Cannot print vouchers for expired users.'
        });
        return null;
    }
    var selectedRows = user_table.rows( { selected: true } );
    var rowData = selectedRows.data();
    var columns = user_table.columns()[0].length;
    var colIndex = 0;
    for (var i=0; i<columns; i++) {
        if ((user_table.column(i).title()=='User') || (user_table.column(i).title()=='Name')) {
            colIndex = i;
            break;
        }
    }
    var printUsers = [];
    for (var i=0; i < selectedRows[0].length; i++) {
        printUsers.push(rowData[i][colIndex]); //Username
    }

    return printUsers;
}

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
                text: 'Select Visible',
                className: 'btn-sm',
                action: function(e, dt, node, config) {
                    var myFilteredRows = user_table.rows({"filter":"applied", "page":"current"});
                    myFilteredRows.select();
                }
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
                text: 'Delete',
                className: 'btn-sm delete',
                action: function(e, dt, node, config) {
                    var selectedRows = user_table.rows( { selected: true } );
                    var rowData = selectedRows.data();
                    iziToast.warning({
                        timeout: 20000,
                        overlay: true,
                        displayMode: 'once',
                        id: 'inputs',
                        zindex: 999,
                        title: 'Delete User',
                        message: 'Are you sure to delete the selected user(s) ?',
                        position: 'center',
                        close: false,
                        drag: false,
                        inputs: [
                            ['<button class="btn btn-primary" style="margin-right: 10px;" id="0">No', 'click', function (instance, toast, input, e) {
                                instance.hide({
                                    transitionOut: 'fadeOutUp',
                                    onClosing: function(instance, toast, closedBy){
                                    }
                                }, toast);
                            }],
                            ['<button class="btn btn-danger" style="margin-right: 10px;" id="1">Yes', 'click', function (instance, toast, input, e) {
                                instance.hide({}, toast);
                                showLoader();

                                var selectedRows = user_table.rows( { selected: true } );
                                var rowData = selectedRows.data();
                                var columns = user_table.columns()[0].length;
                                var colIndex = 0;
                                for (var i=0; i<columns; i++) {
                                    if ((user_table.column(i).title()=='User') || (user_table.column(i).title()=='Name')) {
                                        colIndex = i;
                                        break;
                                    }
                                }
                                var deleteUsers = [];
                                for (var i=0; i < selectedRows[0].length; i++) {
                                    deleteUsers.push(rowData[i][colIndex]); //Username
                                }
                                selectedRows.remove();
                                deleteSelected(deleteUsers);                                
                            }, true]
                        ]
                    });
                }
            },
            {
                text: 'Voucher',
                className: 'btn-sm bg-primary',
                action: function(e, dt, node, config) {
                    printUsers = validateSelection();
                    if (printUsers != null) {
                        fetchUsers(printUsers, 'voucher', null, null);
                    }
                }
            },
            {
                text: 'List1',
                className: 'btn-sm bg-success',
                action: function(e, dt, node, config) {
                    printUsers = validateSelection();
                    if (printUsers != null) {
                        fetchUsers(printUsers, 'list-1', null, null);
                    }
                }
            },
            {
                text: 'List2',
                className: 'btn-sm bg-success',
                action: function(e, dt, node, config) {
                    printUsers = validateSelection();
                    if (printUsers != null) {
                        fetchUsers(printUsers, 'list-2', null, null);
                    }
                }
            },
            {
                text: 'SMS',
                className: 'btn-sm bg-warning text-dark',
                action: function(e, dt, node, config) {
                    printUsers = validateSelection();
                    if (printUsers != null) {
                        fetchUsers(printUsers, null, true, null);
                    }
                }
            },
            {
                text: 'Email',
                className: 'btn-sm bg-warning text-dark',
                action: function(e, dt, node, config) {
                    printUsers = validateSelection();
                    if (printUsers != null) {
                        fetchUsers(printUsers, null, null, true);
                    }
                }
            }
        ],
    });

    user_table.buttons().container().appendTo( $('.col-md-6:eq(0)', user_table.table().container() ) );

    user_table.on('select deselect', function() {
        if ( user_table.rows( { selected: true } ).indexes().length === 0 ) {
            user_table.buttons().disable();
        }
        else {
            user_table.buttons().enable();
        }
    });

    user_table.on( 'draw', function() {
        //
    });

    user_table.on('processing.dt', function(e, settings, processing) {
        if (processing) {
            showLoader();
        } else {
            hideLoader();
        }
    });

    user_table.buttons().disable();

    if (user_type == 'uninitiated') {
        user_table.row(':eq(0)').remove().draw();
    }

    user_table.row(':eq(0)').select();
}

function generateTable(data) {
    setStatus('Generating table ...');
    var req = $.ajax({
        async: true,
        method:"POST",
        url: 'controller/generate_table.php',
        data: {'data': data}
    }).done(function(html) {
        $('#edraw-table').html(html);
        styleTable();
        setStatus('Table loaded');
    });
    window.AXR.push(req);
}

$('#epage-list-user').ready(function() {
    // user_type is assigned in list_users.php from GET
    switch (user_type) {
        case 'active':
            routerFile = 'controller/router_active_users.php';
            break;

        case 'all':
            routerFile = 'controller/router_all_users.php';
            break;

        case 'uninitiated':
            routerFile = 'controller/router_uninitiated_users.php';
            break;

        case 'expired':
            routerFile = 'controller/router_expired_users.php';
            break;
    }
    showLoader();
    setStatus('Fetching user list from router ...');
    var req = $.ajax({
        aync: true,
        method: "POST",
        url: routerFile
    }).done(function(html) {
        if (!html) {
            iziToast.info({
                title: 'List Router Users',
                message: 'No users of selected type found on router'
            });
            $('#edraw-table').html("There are no users to display");
            hideLoader();
        } else {
            try {
                JSON.parse(html);
                generateTable(html);
            } catch (err) {
                iziToast.error({
                    title: 'Error',
                    message: 'Router not responding. Please try again',
                });
                $('#edashboard').trigger('click');
                return;
            }
        }
    });
    window.AXR.push(req);
});