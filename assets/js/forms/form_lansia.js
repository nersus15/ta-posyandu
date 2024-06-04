$(document).ready(function(){
    var form_data = <?= $form_data ?>;
    var edited_data = <?= $form_cache ?>;
    var formid = form_data.formid;
    var components = {
        form: $("#" + formid),
        method: $("#method"),
        id: $('#id'),
    };

    _persiapan_data().then(data => {
        _add_event_listener(data);
        _persiapan_nilai(data);
    
    });
   



    async function _persiapan_data(){
        var data = {};
       
        return data;
    }


    function _add_event_listener(data){
        $("input[name='ingat_ttl']").change(function() {
            var ingat = $(this).attr('id');
            if (ingat == 'ingat') {
                $("#ttl").parent().show();
                $("#umur").parent().hide();
                $("#umur").data('rule-required', false);
            } else if (ingat == 'tidak_ingat') {
                $("#ttl").parent().hide();
                $("#umur").parent().show();
                $("#ttl").data('rule-required', false);
    
            }
        });
    }


    function _persiapan_nilai(data){
        if(form_data.mode == 'edit' && edited_data){
            components.method.val('update');
            components.id.val(edited_data.id);

        }else if(form_data.mode == 'baru'){
            components.method.val('POST');
        }

        $("input[name='ingat_ttl']:checked").trigger('change');
        
    }

});