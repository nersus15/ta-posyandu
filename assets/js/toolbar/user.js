$(document).ready(function () {
    var dtid = "<?= $dtid?>";
    var form = <?= json_encode($form) ?>;
    var toolbar = <?= json_encode($toolbar) ?>;
    var urlexport = toolbar.urlexport;
    var panel = $("#displayOptions-" + dtid);
    var jenis = toolbar.jenis;
    var role = toolbar.role;
    var defaultCnfigToast = {
        title: 'Submit Feedback',
        message: 'Submit Successfull',
        id: 'defaut-config',
        cara_tempel: 'after',
        autohide: true,
        show: true,
        hancurkan: true,
        wrapper: 'form',
        delay: 5000
    }

    panel.find('.tool-custom-export').click(function(){
        showLoading();
        $.post(path + urlexport, {role: jenis})
        .done(function(res){
            if (typeof(res) == 'string')
                res = JSON.parse(res);

            defaultCnfigToast.time = moment().format('YYYY-MM-DD HH:ss');
            defaultCnfigToast.message = res.message;
            setTimeout(function () {
                $("#export-anak").modal('hide');
                window.open(path + 'uihelper/file/' + res.data);

            }, 1000);
            makeToast(defaultCnfigToast);
        })
        .fail(function(res){
            if (typeof (res) == 'string')
                res = JSON.parse(res);

            if (res.message)
                defaultCnfigToast.message = res.message;
            else if (res.responseJSON.message)
                defaultCnfigToast.message = res.responseJSON.message;
            else
                defaultCnfigToast.message = "Sumbit Failed";

            defaultCnfigToast.time = moment().format('YYYY-MM-DD HH:ss')
            makeToast(defaultCnfigToast);
        })
        .always(endLoading);
    });
});