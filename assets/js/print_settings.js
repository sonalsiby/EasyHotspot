var editor = null;

function saveTemplate() {
    showLoader();
    var template = editor.getValue();
    var req = $.ajax({
        async: true,
        method: "POST",
        data: {
            'save': 'X',
            data: template
        },
        url: 'model/voucher/set_voucher.php'
    }).done(function(html) {
        if (html == 0) {
            iziToast.success({
                title: 'Voucher Printing',
                message: 'Template saved successfully'
            });
            readTemplate();
            hideLoader();
        }
    });
    window.AXR.push(req);
}

function readTemplate() {
    var req = $.ajax({
        async: true,
        method: "POST",
        url: 'model/voucher/fetch_voucher.php'
    }).done(function(html) {
        editor.setValue(html);
        refreshTemplate();
    });
    window.AXR.push(req);
}

function refreshTemplate() {
    showLoader();
    var template = editor.getValue();
    var req = $.ajax({
        async: true,
        method: "POST",
        data: {
            'filename': 'temp.php',
            'data': template
        },
        url: 'model/voucher/set_voucher.php'
    }).done(function(html) {
        $.ajax({
            async: true,
            method: "POST",
            data: {
                'preview': true
            },
            url: 'model/voucher/voucher_config.php'
        }).done(function(html) {
            $('#epreview-area').html(html);
            hideLoader();
        })
    });
    window.AXR.push(req);
}

$('#epage-print-settings').ready(function() {
    editor = ace.edit("editor");
    editor.setTheme("ace/theme/monokai");
    editor.session.setMode("ace/mode/php");
    editor.setOption("maxLines", 30);

    readTemplate();
    $('#refreshPreview').on('click', function() {
        refreshTemplate();
    });

    $('#saveTemplate').on('click', function() {
        saveTemplate();
    });
});
