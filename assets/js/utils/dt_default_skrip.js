$(document).ready(function(){
    var form = <?= json_encode($form) ?>;
    var dtid = "<?= $dtid?>";
    var modal = <?= json_encode(isset($modal) ? $modal : []) ?>;
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
    
    if(!form.buttons){
        form.buttons = [
            
        ];
    }
    var attributTabel = $("#"+dtid).data();
    var panel = $("#displayOptions-" + dtid);
    var addButton = panel.find('.tool-add');
    var editButton = panel.find('.tool-edit');
    var deleteButton = panel.find('.tool-delete');
    var exportButton = panel.find('.tool-export');
    var links = panel.find('.tool-link');
    var modalid = "modal-" + dtid;
    var skripid = '';

    $(document).scroll(function(){
        var toolbarButton = panel.find(".panel-toolbar-" + dtid);
        if(toolbarButton.length == 0) return;

        var style = "position: fixed;right: 0;z-index: 99;top: 15%;background: white;border: 1px solid whitesmoke;border-radius: 25px;padding: 10px 10px";
        if($(document).scrollTop() >= 300){
            if(!toolbarButton.hasClass('sticky-toolbar'))
                toolbarButton.addClass('sticky-toolbar');
        }
        else if($(document).scrollTop() < 300)
            toolbarButton.removeClass('sticky-toolbar');
    });

    var modalConfig = {
        modalId: modalid,
        wrapper: "body",
        opt: {
            clickToClose: false,
            type: form.path ? 'form-custom' : 'form',
            ajax: true,
            size: modal.size,
            rules: [
                {
                    name: 'noSpace',
                    method: function (value, element) { return value.indexOf(" ") < 0; },
                    message: "No space please",
                    field: 'url'
                }
            ],
            sebelumSubmit: function () {
                showLoading();
            },
            submitSuccess: function (res) {
                endLoading();
                if(typeof(res) == 'string')
                    res = JSON.parse(res);

                var toastCnfg = {...defaultCnfigToast, time: time = moment().format('YYYY-MM-DD HH:ss'), message: res.message, wrapper: 'body'}
                setTimeout(function(){
                    $("#" + modalid).modal('hide');
                }, 1000);

                console.log("Toast Opt ====>", toastCnfg);
                makeToast(toastCnfg);

                var dt = getInstance('dataTables', dtid);
                dt.ajax.reload();

            },
            submitError: function(res){
                endLoading();
                if(typeof(res) == 'string')
                    res = JSON.parse(res);

                if (res.message)
                    defaultCnfigToast.message = res.message;
                else if(res.responseJSON.message)
                    defaultCnfigToast.message = res.responseJSON.message;
                else
                    defaultCnfigToast.message = "Sumbit Failed";

                defaultCnfigToast.wrapper = 'form';
                defaultCnfigToast.time = moment().format('YYYY-MM-DD HH:ss')
                makeToast(defaultCnfigToast);
                defaultCnfigToast.wrapper = 'body';
            },
            open: true,
            destroy: true,
            modalPos: attributTabel.formPosisi?attributTabel.formPosisi:'rigth',
            saatBuka: (innerOpt) => {
                var datatable = getInstance('dataTables', dtid);
                if(!datatable) return;
                formSkrip = form.skrip ? 's=' + form.skrip : '';
                var url = path + 'uihelper/skrip?' + formSkrip;
                skripid = moment().format('YYYYMMDDHHss');

                if(form.skripVar){
                    form.skripVar.formid = form.formid
                    form.skripVar['skripid'] = skripid;
                }else{
                    form.skripVar = {
                        'skripid': skripid,
                        'formid': form.formid
                    }
                }
            
                if(innerOpt.mode == 'edit'){
                    form.skripVar.mode = 'edit';
                    var rowData = datatable.rows({selected:true}).data();
                    var editedData =rowData[0];
                    if(editedData)
                        url += "&ed=" + JSON.stringify(editedData);

                }else{
                    form.skripVar.mode = 'baru';
                }

                url += "&sv=" + JSON.stringify(form.skripVar);
                fetch(url, {method: 'GET', })
                .then(res => {
                    if (res.status != 200)
                        return;
                    else
                        return res.json()
                }).then(res => {
                    if (!res)
                        return;
                    else {
                        $("#" + modalid).after(res.skrip);
                    }
                });
                $('#' + form.formid).form();
            },
            saatTutup: () => {
                console.log(skripid);
                $("#" + skripid).remove();
            },
            formOpt: {
                enctype: 'multipart/form-data',
                formId: form.formid,
                formAct: path + form.posturl,
                formMethod: 'POST',
            },
            modalTitle: form.nama,
            modalBody: {
                input: form.formGenerate ? form.formGenerate : [],
                buttons: form.buttons
            },
        }
    };

    if(addButton.length > 0){
        addButton.click(function(){            
           modalConfig.opt.mode = 'baru';
           if(form.path){
                var url = path + 'uihelper/form/?f=' + form.path  + '&sv=' + JSON.stringify({formid: form.formid})
                fetch(url, {
                    method: 'GET',
                }).then(res => {
                    if (res.status != 200)
                        return;
                    else
                        return res.json()
                }).then(res => {
                    if (!res)
                        return;
                    else {
                        modalConfig.opt.modalBody.customBody = res.html;
                        generateModal(modalConfig.modalId, modalConfig.wrapper, modalConfig.opt)
                    }
                });
            }else{
                generateModal(modalConfig.modalId, modalConfig.wrapper, modalConfig.opt);
            }
        });
    }

    if(editButton.length > 0){
        editButton.click(function(e){
            e.preventDefault();
            var datatable = getInstance('dataTables', dtid);
            if(!datatable) return;

            modalConfig.opt.mode = 'edit';
            form.skripVar.formid = modalConfig.opt.formOpt.formId;

            var rowData = datatable.rows({selected:true}).data();
            if(rowData.length <= 0){
                alert("pilih salah satu data untuk melanjutkan");
                return;
            }
            else if(rowData.length > 1){
                alert("Hanya bisa mengedit satu data dalam satu waktu"); 
                return;
            }
            var editedData =rowData[0];
            var url = path + 'uihelper/form/?f=' + form.path + "&ed=" + JSON.stringify(editedData) + '&sv=' + JSON.stringify({formid: form.formid});

            if(form.path){
                fetch(url, {
                    method: 'GET',
                }).then(res => {
                    if (res.status != 200)
                        return;
                    else
                        return res.json()
                }).then(res => {
                    if (!res)
                        return;
                    else {
                        modalConfig.opt.formOpt.formAct = form.updateurl != undefined ? path + form.updateurl : path + form.posturl;
                        modalConfig.opt.modalBody.customBody = res.html
                        generateModal(modalConfig.modalId, modalConfig.wrapper, modalConfig.opt)
                    }
                });
            }else{
                generateModal(modalConfig.modalId, modalConfig.wrapper, modalConfig.opt);
            }
        });
    }

    if(deleteButton.length > 0){
        deleteButton.click(function(e){
            e.preventDefault();
            var datatable = getInstance('dataTables', dtid);
            var rowData = datatable.rows({selected:true}).data();

            if(rowData.length <= 0){
                alert("pilih salah satu data untuk melanjutkan");
                return;
            }
                
            if(!form.deleteurl)
                form.deleteurl = form.posturl;
            var ids = [];
            var names = [];
            for (let i = 0; i < rowData.length; i++) {
               ids.push(rowData[i].id);
               names.push(rowData[i].nama);
            }
            var sure = confirm("Yakin Ingin Menghapus data " + ids.join(', ') + "(" + names.join(', ') + ")")
            if(!sure) return;

            fetch(path + form.deleteurl, {
                method: 'POST',
                body: JSON.stringify({
                    '_http_method': 'delete',
                    'ids': ids,
                })
            }).then(res => {
                if (res.status != 200){
                    if(typeof(res) == 'string')
                        res = JSON.parse(res);

                    if (res.message)
                        defaultCnfigToast.message = res.message;
                    else
                        defaultCnfigToast.message = "Delete Gagal";

                    defaultCnfigToast.time = moment().format('YYYY-MM-DD HH:ss')
                    defaultCnfigToast.wrapper = 'body';
                    makeToast(defaultCnfigToast);
                    defaultCnfigToast.wrapper = 'form';
                    var dt = getInstance('dataTables', dtid);
                    dt.ajax.reload();
                }
                    
                else
                    return res.json()
            }).then(res => {
                if (!res)
                    return;
                    
                else {
                    if(typeof(res) == 'string')
                        res = JSON.parse(res);

                    if (res.message)
                        defaultCnfigToast.message = res.message;
                    else
                        defaultCnfigToast.message = "Delete Berhasil";

                    defaultCnfigToast.time = moment().format('YYYY-MM-DD HH:ss')
                    defaultCnfigToast.wrapper = 'body';
                    makeToast(defaultCnfigToast);
                    defaultCnfigToast.wrapper = 'form';
                    var dt = getInstance('dataTables', dtid);
                    dt.ajax.reload();
                }
            }).catch(res => {
                if (!res)
                        return;
                        
                    else {
                        if(typeof(res) == 'string')
                            res = JSON.parse(res);
        
                        if (res.message)
                            defaultCnfigToast.message = res.message;
                        else if(res.responseJSON.message)
                            defaultCnfigToast.message = res.responseJSON.message;
                        else
                            defaultCnfigToast.message = "Delete Gagal";

                        defaultCnfigToast.time = moment().format('YYYY-MM-DD HH:ss')
                        defaultCnfigToast.wrapper = 'body';
                        makeToast(defaultCnfigToast);
                        defaultCnfigToast.wrapper = 'form';

                        var dt = getInstance('dataTables', dtid);
                        dt.ajax.reload();
                    }
            });            

        });
    }

    if(links.length > 0){
        links.click(function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            console.log(url);
            window.open(url, '_blank');
        })
    }

    if(exportButton.length > 0){
        exportButton.click(function(e){
            e.preventDefault();
            var el = document.getElementById(dtid);
            let cloned = el.cloneNode(true);
            document.body.appendChild(cloned);
            cloned.classList.add('printable');
            cloned.children[0].children[0].children[0].remove();

            for (let index = 0; index < cloned.children[1].children.length; index++) {
                cloned.children[1].children[index].children[0].remove();            
                
            }

            print();
            document.body.removeChild(cloned);
        })
    }
});