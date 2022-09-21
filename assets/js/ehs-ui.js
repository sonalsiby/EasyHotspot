// Styling 
var navbarWidth = 190;
var collapseThreshold = 1050;
var hideThreshold = 450;

var dropdowns = Array();
var dropped = false;

window.onresize = function() {
    if ($(window).width() < collapseThreshold) {
        closeNav();
    } else {
        openNav();
    }
    if ($(window).width() < hideThreshold) {
        $('#elogo-title').hide();
        $('#erouter-label').hide();
        $('#erouter-name').hide();
    } else {
        $('#elogo-title').show();
        $('#erouter-label').show();
        $('#erouter-name').show();
    }

    var notepath_height = $('#enotepath').height();
    var body_margin = notepath_height + $('#eheader').height();
    $('#esidenav').css('margin-top', body_margin);
    $('#enotepath').css('margin-top', $('#eheader').height());
    $('#ebody-canvas').css('margin-top', body_margin);
    //$('#edraw-area').css('margin-top', body_margin);
}

function openNav() {
    $('#esidenav').width(navbarWidth);
    if ($(window).width() > collapseThreshold) {
        $('#ebody-canvas').css('marginLeft', navbarWidth+'px');
    }
}

function closeNav() {
    $('#esidenav').width(0);
    $('#ebody-canvas').css('marginLeft', '0px');
}

function closeDrops(e) {
    if (dropped==true) {
        var target = null;
        var ul = null;
        for (var i=0; i<dropdowns.length; i++) {
            
            if (e.target.parentNode.id == dropdowns[i]) {
                target = dropdowns[i];
            }
        }
        if (target != null) {
            for (var i=0; i<dropdowns.length; i++) {
                ul = document.getElementById(dropdowns[i]).parentNode.getElementsByClassName('edropdown-sub')[0];
                if (ul==null) {
                    ul = document.getElementById(dropdowns[i]).parentNode.getElementsByClassName('esidenav-sub')[0];
                }
                if (($(ul).height() != 0) && (dropdowns[i] != target)) {
                    document.getElementById(dropdowns[i]).onclick();
                }
            }
        } else {
            for (var i=0; i<dropdowns.length; i++) {
                ul = document.getElementById(dropdowns[i]).parentNode.getElementsByClassName('edropdown-sub')[0];
                if (ul==null) {
                    ul = document.getElementById(dropdowns[i]).parentNode.getElementsByClassName('esidenav-sub')[0];
                }
                if ($(ul).height() != 0) {
                    document.getElementById(dropdowns[i]).onclick();
                }
            }
        }
    }
}

$(document).ready(function() {

    // Enable Navbar toggle button
    document.getElementById('etogglenav').onclick = function() {
        var cur_width = $('.esidenav').width();
        if (cur_width == 0) {
            openNav();
        } else {
            closeNav();
        }
    };

    //Add dropdown capability to dropdowns
    var dd_parents = document.getElementsByClassName('edropdown-container');
    var dd_parent, dd_child, row_height, children, multiplier, menu_height;
    var i,j;
    for(i=0; i<dd_parents.length; i++) {
        dd_parent = dd_parents[i].getElementsByClassName('edropdown')[0];
        dd_parent.onclick = function () {
            dd_parent = $('#'+this.id);
            dd_child = this.parentNode.getElementsByClassName('edropdown-sub')[0];
            row_height = $(dd_parent).height();
            children = dd_child.getElementsByTagName('li');
            multiplier = children.length;
            menu_height = multiplier * row_height;

            if ($(dd_child).height() == 0) {
                dd_child.style.height = menu_height.toString() + 'px';
                $(dd_parent).addClass('eactive');
                dropped = true;
            } else {
                dd_child.style.height = "0px";
                $(dd_parent).removeClass('eactive');
                dropped = false;
            }

            for (j=0; j<children.length; j++) {
                $(children[j]).on('click', function() {
                    this.parentNode.style.height = "0px";
                    $(this).removeClass('eactive');
                    $(this.parentNode.parentNode.getElementsByClassName('edropdown')[0]).removeClass('eactive');
                });
            }
        }
        dropdowns.push(dd_parent.id);
    }

    

    //Add dropdown capability to sidenav dropdowns
    var edd_parents = document.getElementsByClassName('eside-dropdown-container');
    var edd_parent, edd_child, erow_height, echildren, emultiplier, emenu_height;
    for(i=0; i<edd_parents.length; i++) {
        edd_parent = edd_parents[i].getElementsByClassName('esidenav-dropdown')[0];
        edd_parent.onclick = function () {
            edd_parent = $('#'+this.id);
            edd_child = this.parentNode.getElementsByClassName('esidenav-sub')[0];
            erow_height = $(edd_parent).height();
            echildren = edd_child.getElementsByTagName('li');
            emultiplier = echildren.length;
            emenu_height = emultiplier * erow_height;

            if ($(edd_child).height() == 0) {
                edd_child.style.height = emenu_height.toString() + 'px';
                $(edd_parent).addClass('eactive');
                dropped = true;
            } else {
                edd_child.style.height = "0px";
                $(edd_parent).removeClass('eactive');
                dropped = false;
            }
            for (j=0; j<echildren.length; j++) {
                $(echildren[j]).on('click', function() {
                    this.parentNode.style.height = "0px";
                    // $(this).removeClass('eactive');
                    $(this.parentNode.parentNode.getElementsByClassName('esidenav-dropdown')[0]).addClass('eactive');
                });
            }
        }
        dropdowns.push(edd_parent.id);
    }
    window.onresize();
    window.addEventListener('click', closeDrops);
});

// Needs lot of restructuring
// 