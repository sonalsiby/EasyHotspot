function active(bid) {
    var id = bid.slice(3);
    $.ajax({
        async: true,
        method: "POST",
        url: 'model/db_change_status.php',
        data: {
            'file': 'user',
            'status': 'Active',
            'id': id
        }
    }).done(function(html) {
        $('#'+bid).removeClass('btn-danger');
        $('#'+bid).addClass('btn-success');
        $('#'+bid).html('Active');
    });
}

function inactive(bid) {
    var id = bid.slice(3);
    $.ajax({
        async: true,
        method: "POST",
        url: 'model/db_change_status.php',
        data: {
            'file': 'user',
            'status': 'Inactive',
            'id': id
        }
    }).done(function(html) {
        $('#'+bid).removeClass('btn-success');
        $('#'+bid).addClass('btn-danger');
        $('#'+bid).html('Inactive');
    });
}

function deleteSysUser(username) {
    showLoader();
    var req = $.ajax({
        async: true,
        method: "POST",
        data: {'username': username},
        url: 'controller/system_user_delete.php'
    }).done(function(html) {
        iziToast.success({title: 'System User Account Deletion', message:'System User Account deleted successfuly.'});
        loadContent('view/system_users.php')
    });
    window.AXR.push(req);
}

function bindButtons() {
    var btns = $("[id^=dl]");
    var i;
    for (i=0; i<btns.length; i++) {
        $(btns[i]).on('click', function() {
            var dl_id = this.id.slice(3);
            iziToast.warning({
                timeout: 8000,
                overlay: true,
                displayMode: 'once',
                id: 'inputs',
                zindex: 999,
                title: 'Delete a System User',
                message: 'Are you sure to delete the system user ?',
                position: 'center',
                close: false,
                drag: false,
                inputs: [
                    ['<button class="btn btn-primary" style="margin-right: 10px;" id="0">No', 'click', function (instance, toast, input, e) {
                        instance.hide({
                            transitionOut: 'fadeOutUp',
                        }, toast, 'buttonName');
                    }],
                    ['<button class="btn btn-danger" style="margin-right: 10px;" id="1">Yes', 'click', function (instance, toast, input, e) {
                        deleteSysUser(dl_id);
                        instance.hide({}, toast);
                    }, true]
                ]
            });
        });
    }
    var btns = $("[id^=st]");
    for (i=0; i<btns.length; i++) {
        $(btns[i]).on('click', function() {
            if ($('#'+this.id).html() == 'Active') {
                inactive(this.id);
            } else {
                active(this.id);
            }
        });
    }
    var btns = $("[id^=zx]");
    for (i=0; i<btns.length; i++) {
        $(btns[i]).on('click', function() {
            var zxid = this.id.slice(3);
            loadContent('view/system_user_add.php?type=edit', {'id': zxid});
        });
    }
}

function retrieveUsers() {
    var req = $.ajax({
        async: true,
        method: "POST",
        url: 'controller/system_users.php'
    }).done(function(html) {
        loadContent('view/system_users.php', {'users': html});
    });
    window.AXR.push(req);
}

$('#epage-system-users').ready(function() {
    if (!is_loaded) {
        showLoader();
        retrieveUsers();
    } else {
        bindButtons();
        hideLoader();
        $('#eadd-user').on('click', function() {
            loadContent('view/system_user_add.php?type=add');
        });
    }
});