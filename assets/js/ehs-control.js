var AXR = [];
var idle_timer= null;
var keep_alive = null;
var previous_page = null;
var current_page = null;
var pofiles = null;
var routerInfo = null;

function cancelRequests() {
    window.idle_count = 0;
    var i=0;
    while (i<AXR.length) {
        try {
            if (AXR[i].status != 200) {
                AXR[i].abort();
            }
        } catch (err) {
            //
        }
        i++;
    }
    AXR = new Array();
}

window.onbeforeunload = function() {
    $(document).trigger('page-change');
}

function logOut() {
    $(document).trigger('page-change');
    clearInterval(keep_alive);
    clearInterval(idle_timer);
    window.location = 'controller/logout.php';
}

function adminP() {
    $(document).trigger('page-change');
    clearInterval(keep_alive);
    clearInterval(idle_timer);
    window.location = 'admin.php';
}

function keepSessionAlive() {
    setStatus('Sending keep alive request');
    $.ajax({
        async: true,
        method: "POST",
        url: 'controller/router_list_profiles.php'
    }).done(function(html) {
        try {
            eprofiles = JSON.parse(html)['data'];
            window.profiles = eprofiles;
        } catch {
            //
        }
    });
}

// AJAX Routes
var ajax_routes = {
    'edashboard': 'view/dashboard.php',
    'eadd-single': 'view/add_user.php',
    'eadd-multiple': 'view/add_multiple_users.php',
    'elist-active': 'view/list_users.php?type=active',
    'elist-all': 'view/list_users.php?type=all',
    'elist-uninit': 'view/list_users.php?type=uninitiated',
    'elist-expired': 'view/list_users.php?type=expired',
    'euser-profiles': 'view/user_profiles.php',
	'esystem-users': 'view/system_users.php',
    'elogs-all': 'view/logs.php?type=all',
    'elogs-system': 'view/logs.php?type=system',
    'elogs-hotspot': 'view/logs.php?type=hotspot',
    'elogs-dhcp': 'view/logs.php?type=dhcp',
    'erouter-settings': 'view/router_settings.php',
    'eapp-settings': 'view/application_settings.php',
    'esms-email-settings': 'view/sms_email_settings.php',
    'eprofile': 'view/profile.php',
    'eprint-settings': 'view/print_settings.php',

    //Admin page routings
    'eadashboard': 'view/eadashboard.php',
    'eahotspots-all': 'view/eahotspots.php?type=all',
    'eahotspots-expired': 'view/eahotspots.php?type=expired',
    'eahotspots-by-expiry': 'view/eahotspots.php?type=expiring',
    'easystem-users': 'view/system_users.php',
    'eahotspots-add': 'view/eahotspot_add_new.php'
};

function setStatus(text) {
    $('#estatus').html(text);
}

function showLoader() {
    $('#edraw-area').hide();
    $('#eloader').show();
}

function hideLoader() {
    $('#eloader').hide();
    $('#edraw-area').fadeIn();
}

// Document load
function loadContent(filepath, data=null) {
    previous_page = current_page;
    current_page = filepath;
    $(document).trigger('page-change');
    setStatus('Loading page ...');
    showLoader();
    var req = $.ajax({
        async: true,
        method: "POST",
        url: filepath,
        data: data,
    }).done(function(html) {
        $('#edraw-area').html(html);
        setStatus('Page loaded');
        hideLoader();
        window.onresize();
    });
    window.AXR.push(req);
}

function pageBack() {
    if (previous_page) {
        loadContent(previous_page);
    }
}

function verifyValidity() {
    try {
        if (validity > 10) {
            $('#eh-validity').addClass('bg-success');
        } else if (validity > 2) {
            $('#eh-validity').addClass('bg-warning text-dark');
            iziToast.warning({
                title: 'Subscription',
                message: validity + ' day(s) left to renew your subscription.',
                position: 'center',
                overlay: true,
                timeout: 20000
            });
        } else if (validity <= 0) {
            $('#eh-validity').addClass('bg-danger');
            iziToast.error({
                title: 'Subscription',
                message: 'Your validity has expired. Please contact admin to renew.',
                position: 'center',
                overlay: true,
                timeout: 20000
            });
        }
        if (status == 'Inactive') {
            closeNav();
            $('#etogglenav').hide();
            cancelRequests();
            iziToast.error({
                title: 'Subscription',
                message: 'Host inactive. Please contact administrator.',
                drag: false,
                close: false,
                position: 'center',
                timeout: false,
            });
        } else {
            $('#edashboard').trigger('click');
        }
    } catch (err) {
        $('#eadashboard').trigger('click');
    }
}

function elog(message, optional) {
    $.ajax({
        async: true,
        method: "POST",
        data: {'message': message, 'optional': optional},
        url: 'controller/_log.php'
    });
}

$(document).ready(function() {
    keep_alive = setInterval(keepSessionAlive, 1200000);
    for (var id in ajax_routes) {
        $('#'+id).on('click', function() {
            $('.esidenav-item').removeClass('eactive');
            $(this).addClass('eactive');
            loadContent(ajax_routes[this.id]);
        });
    }

    $('#elogout').on('click', function() {
        iziToast.warning({
            timeout: 10000,
            overlay: true,
            displayMode: 'once',
            id: 'inputs',
            zindex: 999,
            title: 'Logout',
            message: 'Are you sure to log out of Easy Hotspot ?',
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
                    setTimeout(logOut, 1000);
                    $('#edraw-area').fadeOut();
                    $('body').fadeOut('slow');
                    instance.hide({}, toast);
                }, true]
            ]
        });
    });
    $('#eadminp').on('click', function() {
        iziToast.warning({
            timeout: 10000,
            overlay: true,
            displayMode: 'once',
            id: 'inputs',
            zindex: 999,
            title: 'Logout',
            message: 'Are you sure to return ?',
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
                    setTimeout(adminP, 1000);
                    $('#edraw-area').fadeOut();
                    $('body').fadeOut('slow');
                    instance.hide({}, toast);
                }, true]
            ]
        });
    });
    verifyValidity();

    $(document).bind('page-change', cancelRequests);
    try {
        if (demo == 1) {
            iziToast.info({
                title: 'Demo',
                message: 'You are running in Demo Mode. No changes will be saved.',
                position: 'center',
                timeout: 20000,
                overlay: true
            });
        } 
    } catch (err) {
        //
    }
});
