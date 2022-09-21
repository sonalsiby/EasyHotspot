var profile_data = null;

function deleteProfile(pname) {
    showLoader();
    var req = $.ajax({
        async: true,
        method: "POST",
        data: {'profile_name': pname},
        url: 'controller/router_delete_profile.php'
    }).done(function(html) {
        iziToast.success({title: 'Profile Deletion', message:'Profile deleted successfuly.'});
        loadContent('view/user_profiles.php');
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
                timeout: 20000,
                overlay: true,
                displayMode: 'once',
                id: 'inputs',
                zindex: 999,
                title: 'Delete Profile',
                message: 'Are you sure to delete the profile ?',
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
                        deleteProfile(dl_id);
                        instance.hide({}, toast);
                    }, true]
                ]
            });
        });
    }
    btns = $("[id^=cv]");
    for (i=0; i<btns.length; i++) {
        $(btns[i]).on('click', function() {
            var ed_id = this.id.slice(3);
            var data =  {'id': ed_id};
            loadContent('view/add_user_profile.php?type=edit', data);
        });
    }
}

function retrieveProfiles() {
    var req = $.ajax({
        async: true,
        method: "POST",
        url: 'controller/router_list_profiles.php'
    }).done(function(html) {
        // console.log(html);
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
        loadContent('view/user_profiles.php',{'profiles':html});
    }).fail(function(html) {
        alert('Failed to load profiles');
    }); 
    window.AXR.push(req);
}

$('#epage-user-profiles').ready(function() {
    if (!is_loaded) {
        showLoader();
        retrieveProfiles();
    } else {
        bindButtons();
        hideLoader();
        $('#eadd-profile').on('click', function() {
            loadContent('view/add_user_profile.php?type=add');
        });
    }
});