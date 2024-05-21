$(document).ready(function(){
    var form_data = <?= $form_data ?>;
    var edited_data = <?= $form_cache ?>;
    var formid = form_data.formid;
    var components = {
        form: $("#" + formid),
        method: $("#method"),
        id: $('#id'),
        username: $('#username'),
        nama_lengkap: $('#nama_lengkap'),
        no_hp: $('#no_hp'),
        email: $('#email'),
        alamat: $('#alamat'),
        password: $('#password'),
        kelamin: $('#kelamin-p'),
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

    }


    function _persiapan_nilai(data){
        if(form_data.mode == 'edit' && edited_data){
            components.method.val('update');
            components.id.val(edited_data.id);
            components.username.val(edited_data.username).attr('readonly', true);
            components.nama_lengkap.val(edited_data.nama);
            components.email.val(edited_data.email);
            components.no_hp.val(edited_data.hp);
            components.alamat.val(edited_data.alamat);
            components.kelamin.prop('checked', true);

            components.password.prev('label').text("Password (Isi untuk mengupdate password)");
            components.password.val('').data('rule-required', false).prop('data-rule-required', false);
        }else if(form_data.mode == 'baru'){
            components.method.val('POST');
            components.password.prev('label').text("Password");
        }
    }

});