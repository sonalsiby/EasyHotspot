var status;
function active() {
    $.ajax({
        async: true,
        method: "POST",
        url: 'model/db_change_status.php',
        data: {
            'file': 'host',
            'status': 'Active',
            'id': hotspot_id
        }
    }).done(function(html) {
        $('#ea-status').removeClass('btn-danger');
        $('#ea-status').addClass('btn-success');
        $('#ea-status').html('Active');
        status = 1;
    });
}

function inactive() {
    $.ajax({
        async: true,
        method: "POST",
        url: 'model/db_change_status.php',
        data: {
            'file': 'host',
            'status': 'Inactive',
            'id': hotspot_id
        }
    }).done(function(html) {
        $('#ea-status').removeClass('btn-success');
        $('#ea-status').addClass('btn-danger');
        $('#ea-status').html('Inactive');
        status = 0;
    });
}

function setValues(data) {
    $('#eacompany-name').val(data['company']);
    $('#eacompany-address').val(data['address']);
    $('#eacompany-contact').val(data['contact_person']);
    $('#eacompany-email').val(data['email']);
    $('#eacompany-phone').val(data['telephone']);
    $('#eacompany-address').val(data['address']);

    $('#eahotspot-name').val(data['hotspot_name']);
    $('#eahotspot-ip').val(data['host_ip']);
    $('#eahotspot-host').val(data['host']);
    $('#eahotspot-username').val(data['user']);
    $('#eahotspot-password').val(data['pass']);

    $('#eahotspot-creation').val(data['created_on']);
    $('#eahotspot-validity').val(data['valid_till']);
    $('#eahotspot-creator').val('Super Admin');

    if (data['status'] == 'Active') {
        active();
    } else {
        inactive();
    }
}

function validateEmail(email) {
    var re = new RegExp('/(.+)@(.+){2,}\.(.+){2,}/');
    return re.test(email);
}

function linkUser(e, dt, node, config) {
    iziToast.info({
        timeout: false,
        overlay: true,
        displayMode: 'once',
        id: 'access',
        zindex: 999,
        title: 'Grant Access',
        message: 'Type in username to add  ',
        position: 'center',
        drag: false,
        inputs: [
            ['<input id="add-username" type="email" style="margin-right: 10px;">', 'keyup', function (instance, toast, input, e) {
                if (input.value != '') {
                    if( /(.+)@(.+){2,}\.(.+){2,}/.test(input.value) ){
                        $('#add-username').css('background-color', 'orange');
                        if ($.active < 4) {
                            $.ajax({
                                async: true,
                                method: "POST",
                                data: {'str': input.value},
                                url: 'model/db_search_user.php'
                            }).done(function(html) {
                                response = JSON.parse(html)[0];
                                if (response['response'] != -1) {
                                    $('#add-username').css('background-color', 'lightgreen');
                                    $('#link-user').removeAttr('disabled');
                                    $('#link-user').removeClass('btn-warning');
                                    $('#link-user').addClass('btn-success');
                                }
                            });
                        }
                    } else {
                        $('#add-username').css('background-color', 'pink');
                    }
                }
                $('#link-user').attr('disabled', 'true');
                $('#link-user').removeClass('btn-success');
                $('#link-user').addClass('btn-warning');
            }],
            ['<button class="btn btn-warning" style="margin-right: 10px;" id="link-user" disabled> Link', 'click', function (instance, toast, input, e) {
                if (response['response'] == 1) {
                    iziToast.error({
                        title: 'Link User',
                        message: 'User is already linked to this hotspot'
                    });            
                } else {
                    $.ajax({
                        async: true,
                        method: 'POST',
                        data: {'user_id': response['user_id']},
                        url: 'model/db_link_user.php'
                    }).done(function(html) {
                        instance.hide({}, toast);
                        iziToast.success({
                            title: 'Link User',
                            message: 'Linked the user(s) successfully'
                        });
                        fetchUsers(hotspot_id);
                    });
                }
            }, true]
        ]
    });
}

var response;
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
                text: 'Link User',
                className: 'btn-sm btn-primary',
                action: linkUser,
            },
            {
                text: 'Unlink User',
                className: 'btn-sm btn-danger',
                action: function(e, dt, node, config) {
                    var selectedRows = user_table.rows( { selected: true } );
                    if (selectedRows[0].length == 0) {
                        iziToast.error({
                            title: 'Unlink Users',
                            message: 'No users selected to unlink.'
                        });
                        return;
                    }
                    
                    iziToast.warning({
                        title: 'Unlink User',
                        message: 'Are you sure to unlink the selected user(s) ?',
                        timeout: 10000,
                        overlay: true,
                        drag: false,
                        position: 'center',
                        zindex: 999,
                        inputs: [
                            ['<button class="btn btn-danger" style="margin-right: 10px;" id="unlink-user"> Unlink', 'click', function (instance, toast, input, e) {
                                var rowData = selectedRows.data();
                                var deleteCount = rowData.length;
                                for (var i=0; i<rowData.length; i++) {
                                    $.ajax({
                                        async: true,
                                        method: 'POST',
                                        data: {
                                            'user_id': rowData[i][0],
                                            'host_id': hotspot_id
                                        },
                                        url: 'model/db_unlink_user.php'
                                    }).done(function() {
                                        deleteCount--;
                                        if (deleteCount==0) {
                                            instance.hide({}, toast);
                                            fetchUsers(hotspot_id);
                                            iziToast.success({
                                                title: 'Unlink User',
                                                message: 'Selected user(s) unlinked successfully'
                                            });
                                        }
                                    });
                                }
                            }, true]
                        ]
                    });
                }
            },
        ]
    });

    user_table.buttons().container().appendTo($('.col-md-6:eq(0)', user_table.table().container()));
}

function generateTable(data) {
    setStatus('Generating table ...');
    var req = $.ajax({
        async: true,
        method:"POST",
        url: 'controller/generate_table.php',
        data: {'data': data}
    }).done(function(html) {
        $('#eauser-table-canvas').html(html);
        styleTable();
        setStatus('Table loaded');
        hideLoader();
    });
    window.AXR.push(req);
}

function fetchDetails(id) {
    $.ajax({
        async: true,
        method: "POST",
        url: 'model/db_fetch_hotspot.php',
        data: {'id': id}
    }).done(function(html) {
        setValues(JSON.parse(html)['data'][0]);
    });
}

function add_new_user_binding() {
    linkUser();
}

function fetchUsers(id) {
    $.ajax({
        async: true,
        method: "POST",
        url: 'model/db_fetch_users.php'
    }).done(function(html) {
        if (JSON.parse(html)['data']==null) {
            $('#eauser-table-canvas').html('No users associated with this hotspot. <a href="#" onclick="add_new_user_binding()">Click to add</a>');
        } else {
            generateTable(html);
        }
    });
}

function deleteHotspot() {
    iziToast.warning({
        title: 'Delete Hotspot',
        message: 'Are you sure to delete this hotspot ?',
        overlay: true,
        timeout: 8000,
        drag: false,
        position: 'center',
        zindex: 999,
        inputs: [
            ['<button class="btn btn-danger" style="margin-right: 10px;" id="delete-hotspot"> Delete', 'click', function (instance, toast, input, e) {
                $.ajax({
                    async: true,
                    method: 'POST',
                    data: {'hotspot_id': hotspot_id},
                    url: 'model/db_delete_hotspot.php'
                }).done(function(html) {
                    if (html==0) {
                        instance.hide({}, toast);
                        iziToast.success({
                            title: 'Hotspot Deletion',
                            message: 'Hotspot deleted successfully'
                        });
                        loadContent('view/eahotspots.php?type=all');
                    }
                });
            }, true]
        ]
    });
}

function iziAlert(message) {
    iziToast.error({
        title: 'Add Hotspot',
        message: message
    });
}

function updateHotspot() {
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
    params['active'] = status;

    $.ajax({
        async: true,
        method: "POST",
        url: 'model/db_hotspot_update.php',
        data: params,
    }).done(function(html) {
        if (html==0) {
            iziToast.success({
                title: 'Edit Hotspot',
                message: 'Hotspot details updated successfully'
            });
            loadContent('view/eahotspots.php?type=all');
        }
    });
}

function confirmUpdate() {
    iziToast.info({
        title: 'Edit Hotspot',
        message: 'Are you sure to save changes ?',
        overlay: true,
        position: 'center',
        drag: false,
        timeout: false,
        displayMode: 'once',
        zindex: 999,
        inputs: [
            ['<button class="btn btn-warning" style="margin-right: 10px;" id="discard-changes"> Discard', 'click', function (instance, toast, input, e) {
                instance.hide({}, toast);
                loadContent('view/eahotspots.php?type=all');
            }, true],
            ['<button class="btn btn-primary" style="margin-right: 10px;" id="update-hotspot"> Update', 'click', function (instance, toast, input, e) {
                instance.hide({}, toast);
                updateHotspot();

            }, true]
        ]
    });
}

function initializeToast(data) {
    if(data.detail.class == 'validity'){
        var now = new Date();
        var day = ("0" + now.getDate()).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);
        var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
        $('#cdate').val(today);
        $('#cdate').attr('min', today);
    }
}

function changeValidity() {
    iziToast.info({
        title: 'Change Validity',
        message: '#',
        class: 'validity',
        overlay: true,
        drag: false,
        zindex: 999,
        displayMode: 'once',
        position: 'center',
        timeout: false,
        inputs: [
            ['<input type="date" id="cdate" style="margin-right: 10px;">', 'change', function(instance, toast, input, e) {
            }],
            ['<button class="btn btn-primary" style="margin-right: 10px;" id="update-hotspot"> Update', 'click', function (instance, toast, input, e) {
                $.ajax({
                    async: true,
                    method: "POST",
                    data: {'date': $('#cdate').val()},
                    url: 'model/db_update_validity.php'
                }).done(function(html) {
                    instance.hide({}, toast);
                    iziToast.success({
                        title: 'Subscription',
                        message: 'Validity updated successfully'
                    });
                    fetchDetails(hotspot_id);
                });
            }, true]
        ]
    });
}

function editHotspot() {
    $('#eacompany-name').removeAttr('disabled');
    $('#eacompany-address').removeAttr('disabled');
    $('#eacompany-contact').removeAttr('disabled');
    $('#eacompany-email').removeAttr('disabled');
    $('#eacompany-phone').removeAttr('disabled');
    $('#eacompany-address').removeAttr('disabled');

    $('#eahotspot-name').removeAttr('disabled');
    $('#eahotspot-ip').removeAttr('disabled');
    $('#eahotspot-host').removeAttr('disabled');
    $('#eahotspot-username').removeAttr('disabled');
    $('#eahotspot-password').removeAttr('disabled');

    $('#ea-edit').removeClass('btn-primary');
    $('#ea-edit').addClass('btn-success');
    $('#ea-edit').html('Save');

    $('#ea-validity').attr('disabled', true);
    iziToast.info({
        title: 'Edit Hotspot',
        message: 'Hotspot editing enabled'
    });
    $('#eacompany-name').focus();
    $('#ea-edit').off('click');
    $('#ea-edit').on('click', confirmUpdate);
}

function resetHost() {
    $.ajax({
        async: true,
        method: "POST",
        url: 'controller/reset_host_id.php'
    });
}

$('#eapage-hotspot-details').ready(function() {
    $('#ea-back').on('click', pageBack);
    $('#ea-access').on('click', function() {
        $(document).unbind('page-change', resetHost);
        window.location = "index.php";
    });
    $('#ea-status').on('click', function() {
        if (status==1) {
            inactive();
        } else {
            active();
        }
    });
    $('#ea-delete').on('click', deleteHotspot);
    $('#ea-edit').on('click', editHotspot);
    $('#ea-validity').on('click', changeValidity);
    $(document).bind('page-change', resetHost);
    fetchDetails(hotspot_id);
    fetchUsers(hotspot_id);

    document.addEventListener('iziToast-opened', initializeToast);
});

