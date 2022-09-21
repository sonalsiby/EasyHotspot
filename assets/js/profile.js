function getNew(old) {
    var ne1;
    var ne2;
    iziToast.warning({
        title: 'New Password',
        message: '',
        drag: false,
        overlay: true,
        timeout: false,
        position: 'center',
        displayMode: 'once',
        inputs: [
            ['<input id="chk-p1" type="password" style="margin-right: 10px;" placeholder="New password">', 'keyup', function (instance, toast, input, e) {
            }],
            ['<input id="chk-p2" type="password" style="margin-right: 10px;" placeholder="Verify password">', 'keyup', function (instance, toast, input, e) {
            }],
            ['<button class="btn btn-danger" style="margin-right: 10px;">OK', 'click', function (instance, toast, input, e) {
                ne1 = $('#chk-p1').val();
                ne2 = $('#chk-p2').val();
                if (ne1 != ne2) {
                    iziToast.error({
                        title: 'No Match',
                        message: 'Passwords do not match'
                    });
                } else {
                    $.ajax({
                        async: true,
                        method : "POST",
                        url: 'model/db_set_user_pass.php',
                        data: {
                            'old': old,
                            'new': ne1
                        }
                    }).done(function(html) {
                        if (html==1) {
                            iziToast.success({
                                title: 'Password Change',
                                message: 'Password changed successfully'
                            });
                        } else {
                            iziToast.error({
                                title: 'Password Change',
                                message: 'Password change failed. Invalid password.'
                            });
                        }
                    });
                    instance.hide({}, toast);
                }
            }, true]
        ]
    });
}

function changePassword() {
    var old;
    iziToast.info({
        title: 'Current Password',
        message: '',
        drag: false,
        overlay: true,
        timeout: false,
        position: 'center',
        displayMode: 'once',
        inputs: [
            ['<input id="chk-pwd" type="password" style="margin-right: 10px;" placeholder="Enter your password">', 'keyup', function (instance, toast, input, e) {
            }],
            ['<button class="btn btn-primary" style="margin-right: 10px;">OK', 'click', function (instance, toast, input, e) {
                old = $('#chk-pwd').val();
                instance.hide({}, toast);
                getNew(old);
            }, true]
        ]
    });
}

function uploadFile() {
    showLoader();
    var formData = new FormData();
    var ifile = document.getElementById('eavatar-file').files[0];
    formData.append('eavatar-file', ifile);
    $.ajax({
        method: "POST",
        async: true,
        data: formData,
        url: 'model/im_avatar.php',
        contentType: false,
        processData: false
    }).done(function(html) {
        loadContent('view/profile.php');
    });
}

function saveChanges() {
    iziToast.info({
        title: 'User Profile',
        message: 'Are you sure to save changes ?',
        overlay: true,
        position: 'center',
        displayMode: 'once',
        drag: false,
        timeout: false,
        inputs: [
            ['<button class="btn btn-warning" style="margin-right: 10px;" id="save-changes">Update', 'click', function (instance, toast, input, e) {
                instance.hide({}, toast);
                $.ajax({
                    async: true,
                    method: "POST",
                    url: 'model/db_user_update.php',
                    data: {
                        'id': id,
                        'name': $('#ep-name').val(),
                        'address': $('#ep-address').val(),
                        'phone': $('#ep-phone').val(),
                        'user_level': $('#ep-user-level').val(),
                        'status': $('#ep-status').html()
                    }
                }).done(function(html) {
                    iziToast.success({
                        title: 'Edit User',
                        message: 'User details updated successfully'
                    });
                    loadContent('view/profile.php');
                });
            }, true]
        ]
    });
}

function enableEditing() {
    $('#ep-name').removeAttr('disabled');
    $('#ep-address').removeAttr('disabled');
    $('#ep-phone').removeAttr('disabled');
    $('#ep-name').focus();
    $('#ebtn-update').removeClass('btn-success');
    $('#ebtn-update').addClass('btn-danger');
    $('#ebtn-update').html('Save Changes');
    $('#ebtn-update').off('click', enableEditing);
    $('#ebtn-update').on('click', saveChanges);
}

$('#epage-profile').ready(function() {
    $('#ebtn-update').on('click', enableEditing);
    $('#eavatar-file').bind('change', uploadFile);
    $('#ebtn-change').on('click', changePassword);
});