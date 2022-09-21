var now = new Date();
var refreshInterval = 1000;
var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
var days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

function padZeroes(value) {
    if (value < 10) {
        return "0" + value.toString();
    } else {
        return value;
    }
}

function updatePage() {
    now = new Date();
    var offset = now.getTimezoneOffset();
    $('#etzhours').html((0-offset) + ' minutes');
    $('#esthour').html(padZeroes(now.getHours()));
    $('#estminute').html(padZeroes(now.getMinutes()));
    $('#estsecond').html(padZeroes(now.getSeconds()));
    $('#estdate').html(now.getDate());
    $('#estmonth').html(months[now.getMonth()]);
    $('#estyear').html(now.getFullYear());
    $('#estday').html(days[now.getDay()]);
}

function clearTimers() {
    try {
        clearInterval(int1);
    } catch(err) {
        console.log('Clean Up Error: ' + err.message);
    }
}

var int1;
$('#epage-dashboard').ready(function() {
    updatePage();
    int1 = setInterval(updatePage, refreshInterval);
    $(document).bind('page-change', clearTimers);

    $('#ehall').on('click', function() {
        loadContent('view/eahotspots.php?type=all');
    });
    $('#ebyex').on('click', function() {
        loadContent('view/eahotspots.php?type=expiring');
    });
    $('#eexp').on('click', function() {
        loadContent('view/eahotspots.php?type=expired');
    });

    $('#expiry-badge').html($('#expiry-block').html());
});