uihelper = function () {
    var _generatedModal = {};
    this.configTabel = {};
    var _ajaxSubmit = {};
    this.storeModal = function (params) {
        _generatedModal[params.key.toString().replaceAll('-', '_')] = {
            modalid: params.modalid,
            formid: params.formid ? params.formid : '',
            modal: params.modal
        };
    }
    this.storeAjaxSubmit = function (params) {
        _ajaxSubmit[params.key] = params.callback;
    }
    this.getAjaxSumbit = function (key) {
        return _ajaxSubmit[key];
    }
    this.getModal = function (modalid = null) {
        if (modalid) {
            var key = modalid.split('-');
            var key = key.join('_');
            var modal = this.generatedModal[key];
            return { modal: modal.modal, callback: _ajaxSubmit };
        } else
            return _generatedModal;
    }
    var instance = {
        validator: {},
        dropzone: {},
        dataTables: {}
    };


    this.getInstance = function (ins, key) {
        return instance[ins][key];
    }
    this.getAllInstance = function (ins, key) {
        return instance;
    }
    this.removeInstance = function(ins, key){
        delete instance[ins][key];
    }
    this.setInstance = function (ins, key, val) {
        instance[ins][key] = val;
    }
    this.tambahkanBody = function (type, opt) {
        var bodyEl = '';
        var inputEl = "";
        var buttonsEl = "";

        var cardEl = opt.modalBody.card;
        var input = opt.modalBody.input;
        var buttons = opt.modalBody.buttons;


        if (!opt.modalBody.extra)
            opt.modalBody.extra = '';
        if (type == 'form') {
            var form = opt.formOpt;
            if (!form.formId)
                form.formId = 'noId'
            if (!form.enctype)
                form.enctype = '';
            if (!form.formMethod)
                form.formMethod = "POST";
            if (!form.formAttr)
                form.formAttr = '';
            if (!form.formClass)
                form.formClass = '';

            input.forEach(element => {
                if (element.type == 'select')
                    inputEl += this.generateSelect(element);
                else if (element.type == 'custom')
                    inputEl += element.text;
                else
                    inputEl += this.generateInput(element);
            });

            if (buttons) {
                buttons.forEach(el => {
                    var id = !el.id ? "" : el.id;
                    var data = el.data ? el.data : "";
                    buttonsEl += '<button style="margin: 0 5px; ' + el.style + '"' + data + ' type = "' + el.type + '" id = "' + id + '" class = "' + el.class + '">' + el.text + '</button>';
                });
            }
            bodyEl +=
                '<form enctype = "' + form.enctype + '" ' + form.formAttr + ' class="' + form.formClass + '" id ="' + form.formId + '" method = "' + form.formMethod + '" action = "' + form.formAct + '">' +
                '<div id="alert_danger" style="display: none" class="alert alert-danger" role="alert"> </div>' +
                '<div id="alert_success" style="display: none" class="alert alert-success" role="alert"> </div>' +
                inputEl +
                '<div class="separator mb-5"></div>' +
                buttonsEl +
                '</form>' +
                opt.modalBody.extra;

            return bodyEl;
        }

        else if (type == 'card group') {
            var card = "";
            if (opt.modalBody.cardDisplay == 'grid')
                card += '<div class="row row-cols-1 row-cols-md-2">';

            cardEl.forEach(element => {
                card += '<div class="col mb-4">';
                card += this.generateCard(element);
                card += '</div>';
            });

            if (opt.modalBody.cardDisplay == 'grid')
                card += '</div>';

            bodyEl += card + '</div>';

        } else if (type == 'inputNF') {
            input.forEach(element => {
                if (element.type == 'select')
                    inputEl += this.generateSelect(element);
                else
                    inputEl += this.generateInput(element);
            });
            bodyEl += inputEl + opt.modalBody.extra;


        } else if (type == 'custom' || 'form-custom')
            bodyEl = opt.modalBody.customBody;

        return bodyEl;
    }

    this.generateSelect = function (el) {
        var options = null;
        if(el.options){
            options = el.options;
        }
        var id = !el.id ? el.name : el.id;
        var def = el.default ? el.default : '';
        var params = ['label', 'fgClass', 'attr', 'labelClass', 'class'];
        var nullOpt = el.nullOpt ? '<option value = "" selected>' + el.nullOpt + '</option>' : '';
        var selectOpt = nullOpt;

        params.forEach(item => {
            if (!el[item])
                el[item] = ''
        });


        if (options) {
            if(Array.isArray(options)){
                for (let i = 0; i < options.length; i++) {
                    const option = options[i];   
                    const key = option.key;
                      
                    let dataitem = '';
                    if (option.data) {
                        Object.keys(option.data).forEach(i => {
                            dataitem += 'data-' + i + ' ="' + option.data[i] + '"';
                        })
                    }
                    if (def && key == def)
                        selectOpt += '<option ' + dataitem + ' value = "' + key + '" selected> ' + option.text + '</option>';
                    else
                        selectOpt += '<option ' + dataitem + ' value = "' + key + '"> ' + option.text + '</option>';
                }
            }else{
                for (const key in options) {
                    if (Object.hasOwnProperty.call(options, key)) {
                        const option = options[key];     
                        let dataitem = '';
                        if (option.data) {
                            Object.keys(option.data).forEach(i => {
                                dataitem += 'data-' + i + ' ="' + option.data[i] + '"';
                            })
                        }
                        if (def && key == def)
                            selectOpt += '<option ' + dataitem + ' value = "' + key + '" selected> ' + option.text + '</option>';
                        else
                            selectOpt += '<option ' + dataitem + ' value = "' + key + '"> ' + option.text + '</option>';
                    }
                }
            }
            
        }

        var select =
            '<div class = "form-group ' + el.fgClass + '">' +
            '<label class = "control-label ' + el.labelClass + '" for = "' + id + '">' + el.label + '</label>' +
            '<select name = "' + el.name + '" id = "' + id + '"  ' + el.attr + ' class = "form-control ' + el.class + '" >' +
            selectOpt +
            '</select>' +
            '</div>';

        return select
    }
    this.generateCard = function (el) {
        var card = "";
        var cardHead = "";
        var Topimage = '';
        var Bottomimage = '';
        var Leftimage = '';
        var Rightimage = '';
        var foots = '';
        var links = "";
        var buttons = "";
        var params = ['title', 'footerClass', 'text', 'styles', 'tipe', 'value', 'imagewrapper', 'subtitle', 'class'];
        var badges = '';
        params.forEach(item => {
            if (!el[item])
                el[item] = ''
        });
        if (el.width == 'standart' || !el.width)
            cardHead += '<div id="' + el.id + '" class="card ' + el.class + '" style="width: 18rem;' + el.styles + '">';
        else
            cardHead += '<div id="' + el.id + '" class="card ' + el.class + '" style="width:' + el.width + ';' + el.styles + '">';


        if (el.footer) {
            foots += '<div class="card-footer' + el.footerClass + '">';
            el.footer.forEach(foot => {
                var options = ['pembungkus', 'button', 'btnType', 'tujuan', 'class', 'id', 'text', 'link', 'tag', 'extra']
                options.forEach(item => {
                    if (!foot[item])
                        foot[item] = '';
                });

                if (foot.type == 'button') {
                    if (foot.button)
                        foots += foot.button;

                    else if (foot.text && foot.btnType != 'link')
                        foots += '<button id="' + foot.id + '" type="' + foot.btnType + '"' + foot.extra + ' class= "btn ' + foot.class + '">' + foot.text + '</button>';
                    else if (foot.text && foot.btnType == 'link')
                        foots += '<a id="' + foot.id + '" href="' + foot.tujuan + '"' + foot.extra + ' class= "btn ' + foot.class + '">' + foot.text + '</a>';
                }
                else if (foot.type == 'link') {
                    if (foot.link)
                        foots += foot.button;
                    else if (foot.text && !foot.link)
                        foots += '<a id="' + foot.id + '" href="' + foot.tujuan + '"' + foot.extra + ' class= "' + foot.class + '">' + foot.text + '</a>';
                }
                else if (foot.type == 'text') {
                    if (foot.text && !foot.tag)
                        foots += text;
                    else if (foot.tag) {
                        foots += '<' + foot.tag + 'id="' + foot.id + '"' + foot.extra + ' class ="' + foot.class + '" >' + foot.text + '</' + foot.tag + '>';
                    }
                }
            })
            foots += '</div>';

        }


        if (el.badge) {
            badges += '<div class="position-relative">';
            el.badge.forEach(b => {
                if (!b.id)
                    b.id = '';
                if (!b.class)
                    b.class = '';
                if (!b.extra)
                    b.extra = '';

                badges += '<span' + b.id + 'class="badge badge-pill position-absolute badge-top-left' + b.class + '"' + b.extra + '>' + b.text + '</span>';
            });
            badges += `</div>`;
        }

        if (el.images) {
            el.images.forEach(image => {
                if (!image.styles)
                    image.styles = '';
                if (!image.class)
                    image.class = '';
                if (!image.type)
                    image.type = '';

                if (image.position == 'top' && image.type == 'carousel') {
                    Topimage +=
                        '<div class="slick-item">' +
                        badges
                    '<img class="card-img-top' + image.class + '" style="' + image.styles + '" src="' + image.src + '" alt= "' + image.alt + '" >' +
                        '</div>';
                }

                if (image.position == 'top' && image.type != 'carousel')
                    Topimage += '<img class="card-img-top' + image.class + '" style="' + image.styles + '" src="' + image.src + '" alt= "' + image.alt + '" >';

                if (image.position == 'left')
                    Leftimage += '<img class="card-img-top' + image.class + '" style="' + image.styles + '" src="' + image.src + '" alt= "' + image.alt + '" >';

                if (image.position == 'bottom')
                    Bottomimage += '<img class="card-img-top' + image.class + '" style="' + image.styles + '" src="' + image.src + '" alt= "' + image.alt + '" >';

                if (image.position == 'right')
                    Rightimage += '<img class="card-img-top' + image.class + '" style="' + image.styles + '" src="' + image.src + '" alt= "' + image.alt + '" >';
            });
        }

        if (el.buttons && el.buttons.length > 0) {
            el.buttons.forEach(button => {
                if (!button.class)
                    button.class = '';
                if (!button.type)
                    button.type = 'button';
                if (!button.id)
                    button.id = '';
                if (!button.extra)
                    button.extra = '';

                else if (button.type == 'link')
                    buttons += '<a id="' + button.id + '"' + button.extra + ' href="' + button.link + '" class="btn ' + button.class + '">' + button.text + '</a>';
                else
                    buttons += '<button id="' + button.id + '"' + button.extra + ' type="' + button.type + '" class="btn ' + button.class + '">' + button.text + '</button>';
            })
        }
        if (el.links && el.links.length > 0) {
            el.links.forEach(link => {
                if (!link.class)
                    link.class = '';
                if (!link.id)
                    link.id = '';
                if (!link.extra)
                    link.extra = '';

                links += '<a id="' + link.id + '"' + link.extra + ' href="' + link.link + '" class="btn ' + link.class + '">' + link.text + '</a>';
            })
        }
        if (el.type == 'image') {
            card +=
                cardHead +
                badges +
                Topimage +
                '</div>';
        } else {
            if (!Leftimage && !Rightimage) {
                if (el.type == 'carousel') {
                    card +=
                        cardHead +
                        '<div class="carousel ' + el.imagewrapper + '">' +
                        Topimage +
                        '</div>' +
                        '<div class="card-body">'
                        < 'h5 class="card-title' + el.titleClass + '">' + el.title + '</h5>' +
                        '<h6 class="card-subtitle mb-2 ' + el.subtitleClass + '">' + el.subtitle + '</h6>' +
                        '<p class="card-text ' + el.textClass + '">' + el.text + '</p>' +
                        '<div style="margin: -6rem 0 0 0;" class="slick-navs-dots slider-nav text-center">' +
                        links +
                        '</div>' +
                        buttons +
                        Bottomimage +
                        '</div>' +
                        foots +
                        '</div>';
                } else {
                    card +=
                        cardHead +
                        badges +
                        Topimage +
                        '<div class="card-body">' +
                        '<h5 class="card-title ' + el.titleClass + '">' + el.title + '</h5>' +
                        '<h6 class="card-subtitle mb-2 ' + el.subtitleClass + '">' + el.subtitle + '</h6>' +
                        '<p class="card-text ' + el.textClass + '">' + el.text + '</p>' +
                        links +
                        buttons +
                        Bottomimage +
                        '</div>'
                    foots +
                        '</div>';
                }
            } else {
                card +=
                    cardHead +
                    '<div class="card-body">' +
                    '<div style="display: flex">' +
                    Leftimage +
                    '<div style="margin-left: 2%">' +
                    '<h5 class="card-title">' + el.title + '</h5>' +
                    '<h6 class="card-subtitle mb-2">' + el.subtitle + '</h6>' +
                    '<p class="card-text">' + el.text + '</p>' +
                    '</div>' +
                    Rightimage +
                    '</div>' +
                    links +
                    buttons +
                    '</div>' +
                    foots +
                    '</div>';
            }

        }
        return card;
    }

    this.generateInput = function (el) {
        var id = !el.id ? el.name : el.id;
        var placeholder = el.placeholder ? el.placeholder : "";
        var khusus = ['hidden', 'file', 'select', 'radio'];
        var params = ['label', 'fgClass', 'attr', 'value', 'labelClass', 'class'];

        params.forEach(item => {
            if (!el[item])
                el[item] = ''
        });

        if (el.type == 'file') {
            if($.dore !== undefined){
                return '<div class="input-group col-sm-7 ' + el.fgClass + '">' +
                '<span class="input-group-btn">' +
                '<span class="btn btn-default btn-file">' +
                'Browse… <input type="' + el.type + '" name="' + el.name + '" id="' + id + '">' +
                '</span>' +
                '</span>' +
                '<input type="text" value="' + el.value + '" class="form-control ' + el.class + '" readonly>' +
                '</div>';
            }else{
                return '<div class="input-group col-sm-7 ' + el.fgClass + '">' +
                '<span class="input-group-btn">' +
                '<span class="btn btn-default btn-file">' +
                'Browse… <input type="' + el.type + '" name="' + el.name + '" id="' + id + '">' +
                '</span>' +
                '</span>' +
                '</div>';
            }
            
        }
        
        if (el.type == 'hidden')
            return '<input type="hidden" value="' + el.value + '" id="' + id + '" name = "' + el.name + '" />';
        if (el.type == 'textarea')
            return '<div class = "form-group"><label class= "control-label ' + el.labelClass + '" for = "' + id + '">' + el.label + '</label> <textarea name = "' + el.name + '" id = "' + id + '" class = "form-control ' + el.class + '" ' + el.attr + ' placeholder = "' + placeholder + '">' + el.value + '</textarea></div>';

        if (!khusus.includes(el.type))
            return '<div class = "form-group">  <label class= "control-label' + el.labelClass + '" for = "' + id + '">' + el.label + '</label> <input name = "' + el.name + '" type = "' + el.type + '" id = "' + id + '" value = "' + el.value + '" class = "form-control ' + el.class + '"' + el.attr + ' placeholder = "' + placeholder + '"> </div>';
    }
    this.notifikasi = function (pesan, opsi) {
        this.generateModal('notif', 'body', {
            type: 'custom',
            open: true,
            destroy: true,
            saatBuka: opsi.saatBuka == undefined ? function () { } : opsi.saatBuka,
            saatTutup: opsi.saatBuka == undefined ? function () { } : opsi.saatTutup,
            modalBody: {
                customBody: '<h4>' + pesan + '</h4>'
            }
        });
    }
    this.generateModal = function (modalId, wrapper, opt) {
        var body = "";
        var foot = "";
        var stored = null;
        if(opt.clickToClose == undefined)
            opt.clickToClose = true;
            
        var kembalian = null;
        if (!opt.type)
            opt.type = "nonForm";
        if (!modalId) {
            alert("Id Modal harus di isi!");
            return;
        }
        if (!opt) {
            alert("Opt harus di isi!");
            return;
        }

        if(!opt.size) opt.size = '';
        var modalStyles = '';
        if(!['modal-lg', 'modal-sm', 'modal-md', 'modal-xl'].includes(opt.size)){
            var modalStyles = 'style="width:'+ opt.size +'"';
            opt.size = '';
        }

        if (!opt.modalTitle)
            opt.modalTitle = "";

        if (!opt.modalSubtitle)
            opt.modalSubtitle = "";

        if (opt.modalBody)
            body += this.tambahkanBody(opt.type, opt);

        if (opt.modalFooter) {
            foot = '<div class="modal-footer">';
            opt.modalFooter.forEach(el => {
                var id = !el.id ? "" : el.id;
                var data = el.data ? el.data : "";
                foot += '<button ' + data + ' type = "' + el.type + '" id ="' + id + '" class ="' + el.class + '">' + el.text + '</button>';
            });
            foot += '</div>';
        }

        if (!opt.modalPos)
            opt.modalPos = 'def';

        var modalTemplate = opt.modalPos == 'def' ?
            '<div style="overflow-y: scroll" class="modal fade" id="' + modalId + '" tabindex="-1" role="dialog">' +
            '<div class="modal-dialog ' + opt.size + ' dialog-scrollable" role="document" '+  modalStyles +'>' +
            '<div class="modal-content">' +
            '<div class="modal-header d-block">' +
            '<div class = "d-flex">' +
            '<h5 class="modal-title">' + opt.modalTitle + '</h5>' +
            '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
            '<span aria-hidden="true">&times;</span>' +
            '</button>' +
            '</div>' +
            '<h6 id="modal-subtitle" class = "modal-title text-muted">' + opt.modalSubtitle + '</h6>' +
            '</div>' +
            '<div class="modal-body">' + body + '</div>' + foot +
            '</div>' +
            '</div>' +
            '</div>'
            :
            opt.modalPos == 'left' ?
                '<div style="overflow-y:scroll" class="modal fade modal-lef" id="' + modalId + '" tabindex="-1" role="dialog">' +
                '<div class="modal-dialog ' + opt.size + ' dialog-scrollable" role="document">' +
                '<div class="modal-content">' +
                '<div class="modal-header d-block">' +
                '<div class = "d-flex">' +
                '<h5 class="modal-title">' + opt.modalTitle + '</h5>' +
                '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
                '<span aria-hidden="true">&times;</span>' +
                '</button>' +
                '</div>' +
                '<h6 id="modal-subtitle" class = "modal-title text-muted">' + opt.modalSubtitle + '</h6>' +
                '</div>' +
                '<div class="modal-body">' + body + '</div>' + foot +
                '</div>' +
                '</div>' +
                '</div>'
                :
                '<div style="overflow-y: scroll" class="modal fade modal-right" id="' + modalId + '" tabindex="-1" role="dialog">' +
                '<div class="modal-dialog ' + opt.size + ' dialog-scrollable" role="document">' +
                '<div class="modal-content">' +
                '<div class="modal-header d-block">' +
                '<div class = "d-flex">' +
                '<h5 class="modal-title">' + opt.modalTitle + '</h5>' +
                '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
                '<span aria-hidden="true">&times;</span>' +
                '</button>' +
                '</div>' +
                '<h6 id="modal-subtitle" class = "modal-title text-muted">' + opt.modalSubtitle + '</h6>' +
                '</div>' +
                '<div class="modal-body">' + body + '</div>' + foot +
                '</div>' +
                '</div>' +
                '</div>'
            ;

        if (opt.open)
            opt.tulis = true;
        if (!wrapper)
            opt.tulis = false;
        if (!opt.ajax)
            opt.ajax = false;

        if (opt.tulis)
            $(wrapper).append(modalTemplate);
        if (opt.open && !opt.clickToClose)
            $("#" + modalId).modal({ backdrop: 'static', keyboard: false }, 'show');
        else if (opt.open && opt.clickToClose)
            $("#" + modalId).modal('show');
        if (opt.destroy) {
            $("#" + modalId).on('hidden.bs.modal', (e) => {
                e.preventDefault();
                $("#" + e.target.id).remove();
                var oldInstance = this.getInstance('validator', modalId.replaceAll('-', '_'))
                if (oldInstance) {
                    this.removeInstance('validator', modalId.replaceAll('-', '_'));
                }

            });
        }
        if (opt.type == 'form' || opt.type == 'form-custom') {
            stored = { key: modalId, modal: modalTemplate, modalid: modalId, formid: opt.formOpt.formId },
                kembalian = { 'modalId': modalId, 'formId': opt.formOpt.formId, 'modal': modalTemplate }
            if (opt.ajax) {
                var ajaxSubmit = (formId = null) => {
                    var formid = !formId ? opt.formOpt.formId : formId;
                    var succes = opt.submitSuccess ? opt.submitSuccess : () => { };
                    var error = opt.submitError ? opt.submitError : () => { };
                    var sebelumSubmit = opt.sebelumSubmit ? opt.sebelumSubmit : () => { };
                    var rules = {};
                    var options = {
                        success: succes,
                        error: error,
                        beforeSubmit: sebelumSubmit
                    };

                    if(opt.headers != null)
                        options.headers = opt.headers;

                    if (opt.rules) {
                        opt.rules.forEach(rule => {
                            jQuery.validator.addMethod(rule.name, rule.method, rule.message);
                            rules[rule.field] = {};
                            rules[rule.field][rule.name] = true;

                        })
                    }
                    window.formOpt = options
                    var instance_validator =  $("#" + formid).validate({
                        errorPlacement: function(label, element) {
                            label.addClass('text-danger');
                            label.insertAfter(element);
                        },
                        rules: rules,
                        submitHandler: function (form) {
                            $('#' + formid + ' #alert_danger, #alert_success').html('').hide();
                            $(form).ajaxSubmit(options);
                        }
                    });
                    
                    this.setInstance('validator', modalId.replaceAll('-', '_'), instance_validator);
                }
                this.storeAjaxSubmit({ key: opt.formOpt.formId, callback: ajaxSubmit });
                ajaxSubmit();
            }
        } else {
            stored = { key: modalId, modal: modalTemplate, modalid: modalId };
            kembalian = { 'modalId': modalId, 'modal': modalTemplate }
        }

        if (opt.eventListener) {
            $("#" + modalId).on('shown.bs.modal', function () {
                opt.eventListener.forEach(ev => {
                    $(ev.element).on(ev.type, ev.callback);
                })
            });
        }


        $("#" + modalId).on('hidden.bs.modal', opt.saatTutup);
        $("#" + modalId).on('shown.bs.modal', () => {
            opt.saatBuka(opt);
        });

        if (opt.modalclick) {
            $("#" + modalId).on('shown.bs.modal', function () {
                setTimeout(function () {
                    this.addModalOpen();
                }, 20)
            });
            $("#" + modalId).on('hide.bs.modal', function () {
                $('.modal').off('click', this.addModalOpen)
            });
        }



        this.storeModal(stored);

        if (opt.kembali)
            return kembalian;

    }
    this.addModalOpen = function (langsung = false) {
        $('.modal').click(function (e) {
            setTimeout(function () {
                if (!$('body').hasClass('modal-open'))
                    $('body').addClass('modal-open');
            }, 10);
        });

        if (langsung)
            $('.modal').trigger('click');
    };
    this.initDatatable = function (el, opt) {
        var options = {
            searching: opt.search == undefined ? false : opt.search,
            lengthchange: opt.change == undefined ? false : opt.change,
            lengthMenu: opt.changeMenu == undefined ? false : [10, 15, 20, 30, 50, 100],
            destroy: true,
            info: opt.info == undefined ? false : opt.info,
            ordering: opt.order == undefined ? false : opt.order,
            dom: opt.dom == undefined ? '<"row view-filter"<"col-sm-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"ip>>>' : opt.dom,
            buttons: opt.buttons == undefined ? [] : opt.buttons,
            select: opt.select == undefined ? false : opt.select,
            responsive: opt.responsive == undefined ? false : opt.responsive,
            pageLength: 10,
            language: {
                paginate: {
                    previous: "<i class='simple-icon-arrow-left'></i>",
                    next: "<i class='simple-icon-arrow-right'></i>"
                }
            },
            createdRow: function (row, data, index) {
                if (opt.rowCallback) {
                    opt.rowCallback.forEach(rowc => {
                        console.log(row);
                        if (rowc.filter)
                            $(row).on(rowc.evt, rowc.filter, { data: data }, rowc.func);
                        else
                            $(row).on(rowc.evt, { data: data }, rowc.func);
                    })
                }
            },
            drawCallback: function () {
                if (opt.hapusLength)
                    $(el + "_length").remove();
                $($(".dataTables_wrapper .pagination li:first-of-type"))
                    .find("a")
                    .addClass("prev");
                $($(".dataTables_wrapper .pagination li:last-of-type"))
                    .find("a")
                    .addClass("next");

                $(".dataTables_wrapper .pagination").addClass("pagination-sm");
            }
        };

        if(opt.columnDefs != undefined)
            options.columnDefs = opt.columnDefs;
            
        console.log("OPT CDN", options);
        var table = $(el).DataTable(options);

        if (opt.addCallback) {
            opt.callback.forEach(cb => {
                if (cb.filterEL)
                    $(cb.el).on(cb.evt, cb.filterEL, { tabel: table }, cb.func);
                else
                    $(cb.el).on(cb.evt, { tabel: table }, cb.func);
            });
        }
        this.setInstance('dataTables', el.replaceAll("#", ''), table);
        return table;
    }
    this.endLoading = function () {
        $('body').removeClass('show-spinner');
        $('body').removeClass('modal-open')
        $('.c-overlay').hide();
        $('button[type="submit"').prop('disabled', false);

    }
    $.fn.initDropzone = function (opt) {
        Dropzone.autoDiscover = false;
        var id = this.attr('id');
        if ($().dropzone && !$('#' + id).hasClass("disabled")) {
            Dropzone.options[id] = {
                url: opt.url,
                thumbnailWidth: opt.thumbSize,
                previewTemplate: '<div class="dz-preview dz-file-preview mb-3"><div class="d-flex flex-row "> <div class="p-0 w-30 position-relative"> <div class="dz-error-mark"><span><i class="simple-icon-exclamation"></i>  </span></div>      <div class="dz-success-mark"><span><i class="simple-icon-check-circle"></i></span></div>      <img data-dz-thumbnail class="img-thumbnail border-0" /> </div> <div class="pl-3 pt-2 pr-2 pb-1 w-70 dz-details position-relative"> <div> <span data-dz-name /> </div> <div class="text-primary text-extra-small" data-dz-size /> </div> <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>        <div class="dz-error-message"><span data-dz-errormessage></span></div>            </div><a href="#" class="remove" data-dz-remove> <i class="simple-icon-trash"></i> </a></div>',
                init: function () {
                    if (opt.eventListener != undefined) {
                        opt.eventListener.forEach(ev => {
                            this.on(ev.event, ev.func);
                        })
                    }
                }
            }
            var dropzone = $('#' + id).dropzone();
            setInstance('dropzone', id.replaceAll('-', '_'), dropzone);
        }
    }
    this.showLoading = function () {
        $('body').addClass('modal-open');
        $('.c-overlay').show();
        $('button[type="submit"').prop('disabled', true);

        // if (opt.auto && opt.delay) {
        //     setTimeout(function () {
        //         this.endLoading(opt.endConf);
        //     }, opt.delay);
        // }
    }
    this.makeToast = function (opt) {
        var non_req = ['tempel', 'textColor', 'bg', 'delay', 'wrapper', 'toastTime', 'show', 'return', 'hancurkan', 'title', 'message', 'cara_tempel'];

        non_req.forEach(nq => {
            if (!opt[nq])
                opt[nq] = '';
        });

        if (!opt.autohide)
            opt.autohide = false

        if (!opt.delay && opt.autohide)
            opt.delay = parseInt(3000);

        if (!opt.id) {
            alert('toastId tidak  boleh kosong');
            return;
        }
        var toast = '<div aria-live="assertive" aria-atomic="true" role="alert" id="' + opt.id + '" data-delay ="' + opt.delay + '" class="toast ' + opt.bg + ' ' + opt.textColor + '" style="position: fixed;top: 20%;right: 0;" data-autohide="' + opt.autohide + '" >' +
            '<div class="toast-header">' +
            '<strong class="mr-auto">' + opt.title + '</strong>' +
            '<small class="ml-5">' + opt.time + '</small>' +
            '<button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">' +
            '<span aria-hidden="true">×</span>' +
            '</button>' +
            '</div>' +
            '<div class="toast-body">' +
            opt.message +
            '</div>' +
            '</div>';

        if ((opt.tempel && opt.wrapper) || opt.show) {
            if (!opt.cara_tempel)
                $(opt.wrapper).append(toast);
            if (opt.cara_tempel == 'after')
                $(opt.wrapper).after(toast);
            if (opt.cara_tempel == 'prepend')
                $(opt.wrapper).prepend(toast);
            if (opt.cara_tempel == 'before')
                $(opt.wrapper).before(toast);
        }

        if (opt.show)
            $('#' + opt.id).toast('show');

        if (opt.return)
            return toast;

        $("#" + opt.id).on('hidden.bs.toast', function () {
            if (opt.hancurkan)
                $('#' + opt.id).remove();
        });
    }
    this.makeNotify = function (opt = {}) {
        const params = [
            'dismiss', 'timpa', 'atasbawah', 'kirikanan', 'append', 'saatmembuka',
            'saatterbuka', 'saatmenutup', 'saattertutup', 'progressBar'
        ];

        params.forEach(item => {
            if (!opt)
                opt[item] = '';
            else if (!opt[item])
                opt[item] = '';
        });
        $.notify(
            {
                title: !opt.title ? "Bootstrap Notify" : opt.title,
                message: !opt.message ? "Here is a notification!" : opt.message,
                target: "_blank"
            },
            {
                element: opt.append ? opt.append : 'body',
                position: null,
                type: opt.type ? opt.type : 'success',
                allow_dismiss: opt.dismiss ? opt.dismiss : true,
                newest_on_top: opt.timpa ? opt.timpa : true,
                showProgressbar: opt.progressBar ? opt.progressBar : false,
                placement: {
                    from: opt.atasbawah,
                    align: opt.kirikanan
                },
                offset: 20,
                spacing: 10,
                z_index: 1031,
                delay: opt.delay ? opt.delay : 5000,
                timer: 2000,
                url_target: "_blank",
                mouse_over: null,
                animate: {
                    enter: "animated fadeInDown",
                    exit: "animated fadeOutUp"
                },
                onShow: opt.saatmembuka ? opt.saatmembuka : function () { },
                onShown: opt.saatterbuka ? opt.saatterbuka : function () { },
                onClose: opt.saatmenutup ? opt.saatmenutup : function () { },
                onClosed: opt.saattertutup ? opt.saattertutup : function () { },
                icon_type: "class",
                template:
                    '<div data-notify="container" class="col-11 col-sm-3 alert  alert-{0} " role="alert">' +
                    '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                    '<span data-notify="icon"></span> ' +
                    '<span data-notify="title">{1}</span> ' +
                    '<span data-notify="message">{2}</span>' +
                    '<div class="progress" data-notify="progressbar">' +
                    '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
                    "</div>" +
                    '<a href="{3}" target="{4}" data-notify="url"></a>' +
                    "</div>"
            }
        );
    }
    $.fn.initFormAjax = function (opt = {}) {
        var formid = this.attr('id');
        var succes = opt.submitSuccess ? opt.submitSuccess : () => { };
        var error = opt.submitError ? opt.submitError : () => { };
        var sebelumSubmit = opt.sebelumSubmit ? opt.sebelumSubmit : () => { };
        var beforeSerialize = opt.sebelumSerialize ? opt.sebelumSerialize : () => { };
        var rules = opt.rules ? opt.rules : {};
        var options = {
            error: error,
            success: succes,
            beforeSubmit: sebelumSubmit,
            beforeSerialize: beforeSerialize,
        };

        if (opt.rules) {
            rules.forEach(rule => {
                jQuery.validator.addMethod(rule.name, rule.method, rule.message);
                rules[rule.field] = {};
                rules[rule.field][rule.name] = true;

            })
        }
        var form =  $("#" + formid).validate({
            rules: rules,
            errorPlacement: function(label, element) {
                label.addClass('text-danger');
                label.insertAfter(element);
            },
            submitHandler: function (form) {
                $('#' + formid + ' #alert_danger, #alert_success').html('').hide();
                $(form).ajaxSubmit(options);
            }
        });

        setInstance('validator', formid, form);

    }

    this.generateForm = function (form, input, buttons = null, cards = null) {
        var inputEl = '';
        var buttonsEl = '';
        var formEl = '';
        if (!form.formId)
            form.formId = 'noId'
        if (!form.enctype)
            form.enctype = '';
        if (!form.formMethod)
            form.formMethod = "POST";
        if (!form.formAttr)
            form.formAttr = '';
        if (!form.formClass)
            form.formClass = '';
        input.forEach(element => {
            if (element.type == 'select')
                inputEl += this.generateSelect(element);
            else if (element.type == 'custom')
                inputEl += element.text;
            else
                inputEl += this.generateInput(element);
        });

        if (buttons) {
            buttons.forEach(el => {
                var id = !el.id ? "" : el.id;
                var data = el.data ? el.data : "";
                buttonsEl += '<button style=" margin: 0 10px;"' + data + ' type = "' + el.type + '" id = "' + id + '" class = "' + el.class + '">' + el.text + '</button>';
            });
        }
        formEl +=
            '<form enctype = "' + form.enctype + '" ' + form.formAttr + ' class="' + form.formClass + '" id ="' + form.formId + '" method = "' + form.formMethod + '" action = "' + form.formAct + '">' +
            '<div id="alert_danger" style="display: none" class="alert alert-danger" role="alert"> </div>' +
            '<div id="alert_success" style="display: none" class="alert alert-success" role="alert"> </div>' +
            inputEl +
            buttonsEl +
            '</form>';

        $(form.wrapper).append(formEl);

        if (form.ajax) {
            var ajaxSubmit = function (formId = null) {
                console.log(formId);
                var formid = !formId ? form.formId : formId;
                var succes = form.submitSuccess ? form.submitSuccess : () => { };
                var error = opt.submitError ? opt.submitError : () => { };
                var sebelumSubmit = form.sebelumSubmit ? form.sebelumSubmit : () => { };
                var rules = {};
                var options = {
                    success: succes,
                    error: error,
                    beforeSubmit: sebelumSubmit
                };
                if (form.rules) {
                    form.rules.forEach(rule => {
                        jQuery.validator.addMethod(rule.name, rule.method, rule.message);
                        rules[rule.field] = {};
                        rules[rule.field][rule.name] = true;

                    })
                }
                var instance_validator = $("#" + formid).validate({
                    rules: rules,
                    errorPlacement: function(label, element) {
                        label.addClass('text-danger');
                        label.insertAfter(element);
                    },
                    submitHandler: function (form) {
                        $('#' + formid + ' #alert_danger, #alert_success').html('').hide();
                        $(form).ajaxSubmit(options);
                    }
                });
                this.setInstance('validator', formid.replaceAll('-', '_'), instance_validator);
            }
            this.storeAjaxSubmit({ key: form.formId, callback: ajaxSubmit });
            ajaxSubmit();
        }
    }

    /* View in fullscreen */
    this.openFullscreen = function () {
        var elem = document.documentElement;
        if (elem.requestFullscreen) {
            elem.requestFullscreen();
        } else if (elem.webkitRequestFullscreen) { /* Safari */
            elem.webkitRequestFullscreen();
        } else if (elem.msRequestFullscreen) { /* IE11 */
            elem.msRequestFullscreen();
        }
    }

    /* Close fullscreen */
    this.closeFullscreen = function () {
        var elem = document.documentElement;
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.webkitExitFullscreen) { /* Safari */
            document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) { /* IE11 */
            document.msExitFullscreen();
        }
    }
    $.fn.initDatatable = async function (opt = {}) {
        var attribut = this.data();
        var id = this.attr('id');
        var file_skrip_dtconfig = attribut.skrip;
        var skrip_dtconfig = null;
        var selected_rows = [];
        var selectRow = attribut.select == undefined ? true : attribut.select;
        if(!configTabel[id]){
            alert("Config Tabel " + id + " tidak ditemukan");
        }
    
        var columnDefs = [];
        var autoDeselect = attribut.deselectOnRefresh == undefined ? true : attribut.deselectOnRefresh;
        if(attribut.checkbox){
            columnDefs = [
                {
                    'targets': 0,
                    'checkboxes': {
                       'selectRow': selectRow
                    },
                    'createdCell':  function (td, cellData, rowData, row, col){
                        this.api().cell(td).checkboxes.deselect();
                        if(autoDeselect)
                            this.api().cell(td).checkboxes.deselect();

                        else if(!autoDeselect){
                            var selected = [];
                            var key = '_dt_s_' + id;
                            var s = window.localStorage.getItem(key);
                            if(s)
                                selected = s.split(',');
                            if(selected.length > 0)
                                this.api().cell(td).checkboxes.deselect();

                            if(selected.length > 0 && selected.includes(rowData.id)){
                                this.api().cell(td).checkboxes.select();
                            }
                        }
                    }
                 }
            ];
        }

        

        var options = {
            dom: attribut.dom || 'lfrtip',
            bSearch: attribut.search == undefined ? true : attribut.search,
            bLengthChange: attribut.change == undefined ? true : attribut.change,
            responsive: attribut.responsive == undefined ? true : attribut.responsive,
            select: selectRow,
            order: attribut.order == undefined ? [[1, 'asc']] : [[attribut.order, 'asc']],
            columnDefs: columnDefs,
            deferRender: false,
            info: attribut.showInfo == undefined ? true : attribut.showInfo,
            initComplete: function(){
                if(attribut.ajax != false)
                    createProto(this);

                var key = '_dt_s_' + id;
                window.localStorage.removeItem(key);
                
                panel.find('.satu').hide();
                panel.find('.multi').hide();
                $('#' + id + ' thead .dt-checkboxes-cell.dt-checkboxes-select-all').change(function(){
                    var selectedRows = dt_instance.rows({ selected: true }).data();
                    showButton(selectedRows.length);
                });

                $('#' + id +' tbody tr').click(function(){
                    if(selectRow){
                        setTimeout(function(){
                            var selectedRows = dt_instance.rows({ selected: true }).data();
                            showButton(selectedRows.length);
                        }, 100)
                    }
                });

                if(options.info){
                    $("#"+ id +"_info").addClass('ml-4');
                }

            },
            createdRow: function(row, data, dataIndex ){
                $(row).find('input.dt-checkboxes').addClass(dataIndex.toString());
            },
        };

        function showButton(selected = 0){
            if(selected != 1){
                panel.find('.satu').hide();
            }else{
                panel.find('.satu').show();
            }

            if(selected == 0){
                panel.find('.multi').hide();
            }else{
                panel.find('.multi').show();
            }

            // Update Information
            if(options.select != false && selected > 0){
                $("#" + id + "_info .select-info .select-item").text(selected + ' row(s) selected')
            }
        }

        if(attribut.btns){
            options.buttons = JSON.parse(attribut.btns);
        }

        if(attribut.ajax != false){
            options.processing = true,
            options.ajax = path + attribut.source;
            options.serverSide= true;
            options.columns = configTabel[id];
        }else{
            await renderDatatablesOffline(path + attribut.source, id, configTabel[id])
        }

        var dt_instance = $("#" + id).DataTable(options);
        var panel = $("#displayOptions-" + id);
        if(panel.length > 0){
            var searchBar = panel.find('.table-search input');
            var lengthMenu = panel.find('.length-menu a');
            searchBar.keyup(function(){
                var val = $(this).val();
                dt_instance.search(val).draw();
            });

            lengthMenu.click(function(e){
                e.preventDefault();
                var length = $(this).text();
                dt_instance.page.len(length).draw();
            });
        }

        if(attribut.autoRefresh != undefined && attribut.autoRefresh !=  false){
            var interval = 2000;
            if(attribut.autoRefresh != true){
                interval = parseInt(attribut.autoRefresh);
                if(!interval) interval = 2000;
            }
            setInterval(function(){
                // store selected id to localstorage
                var selected = dt_instance.rows({selected: true}).data().toArray();
                var tmp = [];
                selected.forEach(d => {
                    tmp.push(d.id);
                });
                var key = '_dt_s_' + id;
                window.localStorage.setItem(key, tmp);
                if(attribut.ajax != false){
                    console.log("RELOAD DATATABLE WITH AJAX");
                    dt_instance.ajax.reload(null, true);
                }
                else{
                    console.log("RELOAD DATATABLE WITH OFFLINE RENDERER");
                    renderDatatablesOffline(path + attribut.source, id, configTabel[id]);
                }
            }, interval);
        }

        console.log("DT - " + id + ' OPTIONS ==> ', options);
        
        dt_instance.rows().data().__proto__.edit = function(newData){
            var data = this[0];
            this[0].jenis = 'aaffa';
            this.setan = 'agaga';
        }
        function createProto($dt){
            dt_instance.__proto__.api = $dt.api;
        }
        setInstance('dataTables', id, dt_instance);
        
    }
    $(document).ready(function(){
        if(!$().dataTable && ! $().DataTable) return;
        if($('.dataTable').length > 0)
            $('.dataTable').initDatatable();
    });


    async function renderDatatablesOffline(path, dtid, configTabel){
        await fetch(path).then(res => res.json()).then(res => {
            var data  = res.data;
            var tabel = $("#" + dtid);
            var rows = '';
            if(!configTabel)
                throw("Konfig datatable #" + dtid + " invalid");

            tabel.find('tbody').empty();
            data.forEach(row => {
                rows += '<tr>';
                configTabel.forEach(column => {
                    if(column.data == undefined || column.data == null || column.data == ''){
                        rows += '<td></td>';
                    }else if(column.data && column.data != null && column.data != '' && !column.mRender){
                        rows += '<td>' + row[column.data] + '</td>';
                    }else if(column.mRender && typeof(column.mRender) =='function'){
                        rows += '<td>' + column.mRender(null, null, row) + '</td>';
                    }
                    
                });
                rows += '</tr>';
            });
            tabel.find('tbody').html(rows);
        }).catch(err => {
            console.log("Error Proccessing Datatable #" + dtid, err);
        })
    }
    async function load_skrip(path_skrip){
        await fetch(path + 'ws/uihelper/scriptloader?path=' + path_skrip, { method: 'GET' }).then(res => res.json()).then(res => {
            if (!res.data) {
                endLoading();
                return;
            }

            $('body').append(res.data)
            endLoading();
        }).catch(err => { endLoading() });
    }

    this.copyToClipboard = function(text) {
        var sampleTextarea = document.createElement("textarea");
        document.body.appendChild(sampleTextarea);
        sampleTextarea.value = text; //save main text in it
        sampleTextarea.select(); //select textarea contenrs
        document.execCommand("copy");
        document.body.removeChild(sampleTextarea);
        makeToast({
            title: 'Copy To Clipboard',
            message: 'Berhasil copy text ke clipboard',
            id: 'defaut-config',
            cara_tempel: 'after',
            autohide: true,
            show: true,
            time: moment().format('H:m:s'),
            hancurkan: true,
            wrapper: 'body',
            delay: 3000,
            bg: 'bg-primary'
        });
    }

    $.fn.form = function (element, config) {
        /** Calendar */
        if ($().fullCalendar) {
            var testEvent = new Date(new Date().setHours(new Date().getHours()));
            var day = testEvent.getDate();
            var month = testEvent.getMonth() + 1;
            $(".calendar").fullCalendar({
                themeSystem: "bootstrap4",
                height: "auto",
                buttonText: {
                    today: "Today",
                    month: "Month",
                    week: "Week",
                    day: "Day",
                    list: "List"
                },
                bootstrapFontAwesome: {
                    prev: " simple-icon-arrow-left",
                    next: " simple-icon-arrow-right",
                    prevYear: "simple-icon-control-start",
                    nextYear: "simple-icon-control-end"
                },
                events: [
                    {
                        title: "Account",
                        start: "2018-05-18"
                    },
                    {
                        title: "Delivery",
                        start: "2018-09-22",
                        end: "2018-09-24"
                    },
                    {
                        title: "Conference",
                        start: "2018-06-07",
                        end: "2018-06-09"
                    },
                    {
                        title: "Delivery",
                        start: "2018-11-03",
                        end: "2018-11-06"
                    },
                    {
                        title: "Meeting",
                        start: "2018-10-07",
                        end: "2018-10-09"
                    },
                    {
                        title: "Taxes",
                        start: "2018-08-07",
                        end: "2018-08-09"
                    }
                ]
            });
        }
    
        /* 03.16. Tooltip */
        if ($().tooltip) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    
        /* 03.17. Popover */
        if ($().popover) {
            $('[data-toggle="popover"]').popover({ trigger: "focus" });
        }
    
        /* 03.18. Select 2 */
        if ($().select2) {
            $(".select2-single, .select2-multiple").select2({
                theme: "bootstrap",
                placeholder: "",
                maximumSelectionSize: 6,
                containerCssClass: ":all:"
            });
        }
    
        /* 03.19. Datepicker */
        if ($().datepicker) {
            $("input.datepicker").datepicker({
                autoclose: true,
                templates: {
                    leftArrow: '<i class="simple-icon-arrow-left"></i>',
                    rightArrow: '<i class="simple-icon-arrow-right"></i>'
                }
            });
    
            $(".input-daterange").datepicker({
                autoclose: true,
                templates: {
                    leftArrow: '<i class="simple-icon-arrow-left"></i>',
                    rightArrow: '<i class="simple-icon-arrow-right"></i>'
                }
            });
    
            $(".input-group.date").datepicker({
                autoclose: true,
                templates: {
                    leftArrow: '<i class="simple-icon-arrow-left"></i>',
                    rightArrow: '<i class="simple-icon-arrow-right"></i>'
                }
            });
    
            $(".date-inline").datepicker({
                autoclose: true,
                templates: {
                    leftArrow: '<i class="simple-icon-arrow-left"></i>',
                    rightArrow: '<i class="simple-icon-arrow-right"></i>'
                }
            });
        }
    
        /* 03.20. Dropzone */
        if ($().dropzone && !$(".dropzone").hasClass("disabled")) {
        }
    
        /* 03.21. Cropperjs */
        var Cropper = window.Cropper;
        if (typeof Cropper !== "undefined") {
            function each(arr, callback) {
                var length = arr.length;
                var i;
    
                for (i = 0; i < length; i++) {
                    callback.call(arr, arr[i], i, arr);
                }
    
                return arr;
            }
            var previews = document.querySelectorAll(".cropper-preview");
            var options = {
                aspectRatio: 4 / 3,
                preview: ".img-preview",
                ready: function () {
                    var clone = this.cloneNode();
    
                    clone.className = "";
                    clone.style.cssText =
                        "display: block;" +
                        "width: 100%;" +
                        "min-width: 0;" +
                        "min-height: 0;" +
                        "max-width: none;" +
                        "max-height: none;";
                    each(previews, function (elem) {
                        elem.appendChild(clone.cloneNode());
                    });
                },
                crop: function (e) {
                    var data = e.detail;
                    var cropper = this.cropper;
                    var imageData = cropper.getImageData();
                    var previewAspectRatio = data.width / data.height;
    
                    each(previews, function (elem) {
                        var previewImage = elem.getElementsByTagName("img").item(0);
                        var previewWidth = elem.offsetWidth;
                        var previewHeight = previewWidth / previewAspectRatio;
                        var imageScaledRatio = data.width / previewWidth;
                        elem.style.height = previewHeight + "px";
                        if (previewImage) {
                            previewImage.style.width =
                                imageData.naturalWidth / imageScaledRatio + "px";
                            previewImage.style.height =
                                imageData.naturalHeight / imageScaledRatio + "px";
                            previewImage.style.marginLeft = -data.x / imageScaledRatio + "px";
                            previewImage.style.marginTop = -data.y / imageScaledRatio + "px";
                        }
                    });
                },
                zoom: function (e) { }
            };
    
            if ($("#inputImage").length > 0) {
                var inputImage = $("#inputImage")[0];
                var image = $("#cropperImage")[0];
    
                var cropper;
                inputImage.onchange = function () {
                    var files = this.files;
                    var file;
    
                    if (files && files.length) {
                        file = files[0];
                        $("#cropperContainer").css("display", "block");
    
                        if (/^image\/\w+/.test(file.type)) {
                            uploadedImageType = file.type;
                            uploadedImageName = file.name;
    
                            image.src = uploadedImageURL = URL.createObjectURL(file);
                            if (cropper) {
                                cropper.destroy();
                            }
                            cropper = new Cropper(image, options);
                            inputImage.value = null;
                        } else {
                            window.alert("Please choose an image file.");
                        }
                    }
                };
            }
        }
    
        /* 03.22. Range Slider */
        if (typeof noUiSlider !== "undefined") {
            if ($("#dashboardPriceRange").length > 0) {
                noUiSlider.create($("#dashboardPriceRange")[0], {
                    start: [800, 2100],
                    connect: true,
                    tooltips: true,
                    range: {
                        min: 200,
                        max: 2800
                    },
                    step: 10,
                    format: {
                        to: function (value) {
                            return "$" + $.fn.addCommas(Math.floor(value));
                        },
                        from: function (value) {
                            return value;
                        }
                    }
                });
            }
    
            if ($("#doubleSlider").length > 0) {
                noUiSlider.create($("#doubleSlider")[0], {
                    start: [800, 1200],
                    connect: true,
                    tooltips: true,
                    range: {
                        min: 500,
                        max: 1500
                    },
                    step: 10,
                    format: {
                        to: function (value) {
                            return "$" + $.fn.addCommas(Math.round(value));
                        },
                        from: function (value) {
                            return value;
                        }
                    }
                });
            }
    
            if ($("#singleSlider").length > 0) {
                noUiSlider.create($("#singleSlider")[0], {
                    start: 0,
                    connect: true,
                    tooltips: true,
                    range: {
                        min: 0,
                        max: 150
                    },
                    step: 1,
                    format: {
                        to: function (value) {
                            return $.fn.addCommas(Math.round(value));
                        },
                        from: function (value) {
                            return value;
                        }
                    }
                });
            }
        }
        /* 03.27. Tags Input */
        if ($().tagsinput) {
            $(".tags").tagsinput({
                cancelConfirmKeysOnEmpty: true,
                confirmKeys: [13]
            });
    
            $("body").on("keypress", ".bootstrap-tagsinput input", function (e) {
                if (e.which == 13) {
                    e.preventDefault();
                    e.stopPropagation();
                }
            });
        }
    
    }
    return this;
}();
