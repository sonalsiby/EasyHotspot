var chart;
var failed_req = 0;
var now = new Date();
var refreshInterval = 1000;
var routerRefreshInterval = 6000;
var referenceTime = now.getTime();
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
    $('#esthour').html(padZeroes(now.getHours()));
    $('#estminute').html(padZeroes(now.getMinutes()));
    $('#estsecond').html(padZeroes(now.getSeconds()));
    $('#estdate').html(now.getDate());
    $('#estmonth').html(months[now.getMonth()]);
    $('#estyear').html(now.getFullYear());
    $('#estday').html(days[now.getDay()]);
}

// Convert bits/bytes into human readable units
function computeStorageUnit(value, bits=false) {
    var unit = '';
    var returnArray = []
    if (!bits){
        if (value < 1073741824) {
            unit = 'MB';
            value /= 1048576;
        } else {
            unit = 'GB';
            value /= (1024^3);
        }
    } else {
        if (value < 1000000) {
            unit = 'Kb';
            value /= 1000;
        } else {
            unit = 'Mb';
            value /= 1000000;
        }
    }
    returnArray['value'] = Math.round(value);
    returnArray['unit'] = unit;
    return returnArray;
}

var fail_timer;
function conn_failure() {
    clearTimers();
    iziToast.error({
        title: 'Error',
        message: 'Router not responding. Retrying in 20 seconds.',
        overlay: true,
        drag: false,
        displayMode: 'once',
        position: 'center',
        timeout: 20000,
        class: 'err'
    });
    fail_timer = setTimeout(setTimers, 20000);
}

function updateForm(html) {
    setStatus('Router response. Updating perfomance values.');

    // $('#erouter-name').html(html['system_identity']);
    $('#eactive-users').html(html['active_users']);
    $('#etotal-users').html(html['total_users']);
    $('#euninit-users').html(html['uninitiated_users']);
    $('#eexpired-users').html(html['expired_users']);

    $('#erouter-uptime').html(html['uptime']);
    $('#ecpu-load').html(html['cpu_load']);
    $('#ecpu-perf-meter').css('width', html['cpu_load']+'%');
    
    var total_memory = computeStorageUnit(Number(html['total_memory']));
    var free_memory = computeStorageUnit(Number(html['free_memory']));
    var used_memory = total_memory['value'] - free_memory['value']
    $('#eused-memory').html(used_memory);

    var used_memory = ((used_memory)/total_memory['value'])*100;
    $('#etotal-memory').html(total_memory['value']);
    $('.ememunit').html(free_memory['unit']);
    $('#emem-perf-meter').css('width', used_memory+'%');

    $('#erouter-board').html(html['board']);
    $('#erouter-model').html(html['board']);
    $('#erouter-os').html(html['version']);
    $('#eproc-name').html(html['cpu']);
    $('#eproc-cores').html(html['cores']);
    $('#eproc-freq').html(html['freq']);
    $('#einterface').html(html['interface']);

    var hdd_free = computeStorageUnit(html['free_space']);
    var hdd_total = computeStorageUnit(html['total_space']);
    var hdd_used = hdd_total['value'] - hdd_free['value'];
    $('#eused-space').html(hdd_used);
    $('#etotal-space').html(hdd_total['value']);
    $('.espace-unit').html(hdd_free['unit']);

    var rx_ = computeStorageUnit(html['rx'], true);
    var tx_ = computeStorageUnit(html['tx'], true);
    
    var nowe = new Date();
    var label = padZeroes(nowe.getHours()) + ':' + padZeroes(nowe.getMinutes());
    chart.data.labels.push(label);
    chart.data.datasets[0].data.push(html['rx']/8/1024);
    chart.data.datasets[1].data.push(html['tx']/8/1024);

    if (chart.data.datasets[0].data.length > 15) {
        chart.data.datasets[0].data.splice(0, 1);
        chart.data.datasets[1].data.splice(0, 1);
        chart.data.labels.splice(0, 1);
    }
    chart.update();

    $('#erx-speed').html(rx_['value']);
    $('#etx-speed').html(tx_['value']);
    $('#erx-unit').html(rx_['unit']);
    $('#etx-unit').html(tx_['unit']);

    if (rx_['unit'] == 'Kb') {
        rxGauge.setProperty('valMax', 1000);
    } else {
        rxGauge.setProperty('valMax', 100);
    }
    if (tx_['unit'] == 'Kb') {
        txGauge.setProperty('valMax', 1000);
    } else {
        txGauge.setProperty('valMax', 100);
    }
    rxGauge.setValue(rx_['value']);
    txGauge.setValue(tx_['value']);
    
    failed_req = 0;
    hideLoader();
}

var first_run = true;
function updateRouterInfo() {
    if (first_run) {
        first_run = false;
        showLoader();
    } else {
        window.routerInfo = null;
    }
    if (failed_req > 2) {
        showLoader();
        conn_failure();
    }
    if ($.active > 1) {
        return;
    }
    setStatus('Connecting to router. Active threads - ' + (Number($.active) + 1));
    if (window.routerInfo == null) {
        var req = $.ajax({
            async: true,
            method: "POST",
            url: 'controller/router_info.php',
            timeout: 15000,
        }).done(function(html) {
            try {            
                html = JSON.parse(html);
                window.routerInfo = html;
                updateForm(html);
            } catch(err) {
                failed_req += 1;
                console.log(err.message);
            }
            
        });
        window.AXR.push(req);
    } else {
        html = window.routerInfo;
        updateForm(html);
    }
}

function createKnob() {
    var knob = pureknob.createKnob(70, 70);
    // Set properties.
    knob.setProperty('angleStart', -0.75 * Math.PI);
    knob.setProperty('angleEnd', 0.75 * Math.PI);
    knob.setProperty('colorFG', '#ffffff');
    knob.setProperty('colorBG', '#000000');
    knob.setProperty('trackWidth', 0.4);
    knob.setProperty('valMin', 0);
    knob.setProperty('valMax', 100);
    knob.setProperty('readOnly', true);
    return knob;
}

var rxGauge;
var txGauge;
var rxMax = 100;
var txMax = 100;
function createGauges() {
    // Set initial value.
    rxGauge = createKnob();
    rxGauge.setValue(50);
    var node = rxGauge.node();
    var elem = document.getElementById('erx-gauge');
    elem.appendChild(node);
    $(node).css('height', '50%');
    $(node).css('width', '50%');

    txGauge = createKnob();
    txGauge.setValue(50);
    node = txGauge.node();
    elem = document.getElementById('etx-gauge');
    elem.appendChild(node);
    $(node).css('height', '50%');
    $(node).css('width', '50%');
}

var int1;
var int2;
function clearTimers() {
    try {
        var toast = document.querySelector('.err');
        iziToast.hide({}, toast);
    } catch (err) {
        // console.log('Clean Up Error: ' + err.message);
    }
    try {
        clearInterval(int1);
        clearInterval(int2);
    } catch(err) {
        console.log('Clean Up Error: ' + err.message);
    }
    try {
        clearTimeout(fail_timer);
    } catch (err) {
        console.log('Clean Up Error: ' + err.message);
    }
    $(document).unbind('page-change', clearTimers);
}

function setTimers() {
    int1 = setInterval(updatePage, refreshInterval);
    int2 = setInterval(updateRouterInfo, routerRefreshInterval);
}

$('#epage-dashboard').ready(function() {
    createGauges();
    updatePage();
    updateRouterInfo();
    setTimers();
    $(document).bind('page-change', clearTimers);

    var ctx = document.getElementById('myChart').getContext('2d');
    chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Rx KiB/s',
                backgroundColor: 'red',
				borderColor: 'red',
				fill: false,
            },
            {
                label: 'Tx KiB/s',
                backgroundColor: 'blue',
				borderColor: 'blue',
                fill: false,
            }]
        },
        options: {
            title: {
                text: 'Data Throughput'
            },
            scales: {
                xAxes: [{
                    
                }],
                yAxes: [{
                    
                }]
            }
        }
    });
});