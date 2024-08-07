$(document).ready(function () {
    var dtid = "<?= $dtid?>";
    var form = <?= json_encode($form) ?>;
    var toolbar = <?= json_encode($toolbar) ?>;
    var panel = $("#displayOptions-" + dtid);
    var role = toolbar.role;
    var daftarBulan = ['Semua', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
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
    panel.find(".tool-custom-detail").click(function (e) {
        e.preventDefault();
        var datatable = getInstance('dataTables', dtid);
        if (!datatable) return;

        var rowData = datatable.rows({ selected: true }).data();
        if (rowData.length <= 0) {
            alert("pilih salah satu data untuk melanjutkan");
            return;
        }
        else if (rowData.length > 1) {
            alert("Hanya bisa melihat satu data dalam satu waktu");
            return;
        }
        rowData = rowData[0];
        var induk = $('#detail-riwayat');
        induk.empty();

        // Load Data Pemeriksaan
        fetch(path + 'bumil/periksalist', {
            method: 'POST',
            body: JSON.stringify({
                'id': rowData.id,
            })
        }).then((res) => res.json()).then(function (res) {
            var data = res.data;
            induk.append('<div style="overflow-x: scroll" class="card mt-4">' +
                '<div class="card-header row">' +
                '<span class="text-danger" id="close-detail" style="position: relative;cursor: pointer;left: 95%;top: 15px; font-size: 20px"><i class="iconsmind-Close"></i></span>' +
                '<h1 class="card-title ml-4 mt-3 col-12">Detail Pemeriksaan ' + (rowData.nama ? rowData.nama : '') + '</h1>' +
                '<div class="row col-sm-6 ml-4">' +
                (role == 'admin' ? '' : '<button class="btn btn-primary btn-sm" type="button" id="add-pemeriksaan">Periksa Ibu</button>') +
                    '<button class="ml-4 btn btn-secondary btn-sm" type="button" id="export-single" data-id="'+ rowData.id +'">Cetak</button>' +
                '</div>' +
                '</div>' +
                '<div id="canvas_detail_pemeriksaan" class="card-body row">' +

                '</div>' +

                '</div>'
            );

            $("#add-pemeriksaan").click(function () {
                var cached = null;
                if(data.pemeriksaan && data.pemeriksaan.length > 0)
                    cached = data.pemeriksaan[0];
                if(cached){
                    delete cached['bb'];
                    delete cached['tb'];
                    delete cached['lila'];
                    delete cached['usia_kehamilan'];


                    cached['tgl_periksa'] = waktu(null, 'YYYY-MM-DD');
                    addPemeriksan(rowData, cached, false)
                }else
                    addPemeriksan(rowData)
            });
            $('#close-detail').click(function () {
                induk.empty();
            });

            $("#export-single").click(function(){
                var id = $(this).data('id');
                showLoading();
                $.post(path + 'report/bumil/' + id, function(res){
                    endLoading();
                    if (typeof(res) == 'string')
                        res = JSON.parse(res);

                    defaultCnfigToast.time = moment().format('YYYY-MM-DD HH:ss');
                    defaultCnfigToast.message = res.message;
                    setTimeout(function () {
                        $("#export-anak").modal('hide');
                    }, 1000);
                    makeToast(defaultCnfigToast);
                    window.open(path + 'uihelper/file/' + res.data);
                });
            });
            var bodyEl = induk.find('.card-body');


            if (res.type != 'succes' || !data) {
                bodyEl.append('<div class="col-12"><h4 style="text-align: center">Something Wrong</h4></div>');
                return;
            } else if (data.length == 0) {
                bodyEl.append('<div class="col-12"><h3 style="text-align: center">Belum ada data pemeriksaan</h3></div>');
                return;
            }

            renderTable(bodyEl, data, rowData.id);

        });
    });


    panel.find('.tool-custom-export').click(function(){
        var opsiTahun = [];
        var opsiKelompok = [
            {key: 'semua', text: 'Semua'},
            {key: '05', text: 'Umur 0-5 Bulan'},
            {key: '611', text: 'Umur 6-11 Bulan'},
            {key: '1223', text: 'Umur 12-23 Bulan'},
            {key: '2459', text: 'Umur 24-59 Bulan'},
        ];
        var tahunIni = new Date().getFullYear();
        tahunIni = parseInt(tahunIni)
        for (let index = tahunIni; index > (tahunIni - 10); index--) {
            opsiTahun.push({
                text: index,
                key: index
            })
        }
        var modalConfig = {
            modalId: 'export-bumil',
            wrapper: "body",
            opt: {
                clickToClose: false,
                type: 'form',
                ajax: true,
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
                    if (typeof(res) == 'string')
                        res = JSON.parse(res);

                    defaultCnfigToast.time = moment().format('YYYY-MM-DD HH:ss');
                    defaultCnfigToast.message = res.message;
                    setTimeout(function () {
                        $("#export-anak").modal('hide');
                    }, 1000);
                    makeToast(defaultCnfigToast);
                    window.open(path + 'uihelper/file/' + res.data);

                },
                submitError: function (res) {
                    endLoading();
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

                },
                open: true,
                destroy: true,
                modalPos: 'def',
                saatBuka: (innerOpt) => {
                    $('#form-export-bumil').form();
                },
                saatTutup: () => {
                },
                formOpt: {
                    enctype: 'multipart/form-data',
                    formId: 'form-export-bumil',
                    formAct: path + 'report/bumil',
                    formMethod: 'POST',
                },
                modalTitle: 'Export Data',
                modalBody: {
                    input: [
                        {
                            type: 'select', 
                            name: 'tahun',
                            label: 'Pilih Tahun',
                            options: opsiTahun,
                            default: null
                        },
                        {
                            type: 'select', 
                            name: 'bulan',
                            label: 'Pilih Bulan',
                            options: daftarBulan.map(function(v, i){return {key: i.toString(), text: v}}),
                            default: new Date().getMonth()
                        }

                    ],
                    buttons: [
                        { type: 'submit', text: 'Export', id: "export", class: "btn btn btn-primary" }
                    ]
                },
            }
        };
        generateModal(modalConfig.modalId, modalConfig.wrapper, modalConfig.opt);
    });

    function renderTable(bodyEl, data, idbumil) {
        bodyEl.empty();
        var rowData = data.bumil;
        data = data.pemeriksaan;

        var header = '<th class="middle center">Tanggal Periksa</th>' +
            '<th class="middle center">Tahun</th>' +
            '<th class="middle center">Bulan</th>' +
            '<th class="middle center">Nama Pemeriksa</th>' +
            '<th class="middle center">Usia Kehamilan</th>' +
            '<th class="middle center">Hamil Ke</th>' +
            '<th class="middle center">BB</th>' +
            '<th class="middle center">TB</th>' +
            '<th class="middle center">Lingkar Lengan Atas</th>' +
            '<th class="middle center">Actions</th>';

        if(role == 'bidan' || role == 'admin'){
            header = '<th class="middle center">Tanggal Periksa</th>' +
                '<th class="middle center">Tahun</th>' +
                '<th class="middle center">Bulan</th>' +
                '<th class="middle center">Nama Pemeriksa</th>' +
                '<th class="middle center">Obstetrik</th>'+
                '<th class="middle center">HPHT</th>'+
                '<th class="middle center">Taksiran <br> Persalinan</th>'+
                '<th class="middle center">Persalinan <br> Sebelumnya</th>'+
                '<th class="middle center">BB Sesudah Hamil</th>'+
                '<th class="middle center">BB Sebelum Hamil</th>'+
                '<th class="middle center">TB</th>'+
                '<th class="middle center">Buku KIA</th>'+
                '<th class="middle center">Actions</th>';
        }
        var content = '<table id="table-pemeriksaan-bumil" class="table table-bordered table-hover">' +
            '<thead>' +
            '<tr>' +
            header +
            '</tr>' +
            '</thead>' +
            '<tbody>' +
            convert(data, idbumil)
        '</tbody>' +
            '</table>';

        bodyEl.append(content);
        $(".edit-kunjungan-bumil").click(function () {
            var id = $(this).data('kunjungan');
            var idbumil = $(this).data('bumil');

            var cached = data.filter((row) => row.id == id);
            if(cached.length != 1) alert("Invalid");
            cached = cached[0];
            addPemeriksan(rowData, cached);
        });

        $('.hapus-kunjungan-bumil').click(function () {
            var _continue = confirm("Yakin ingin melanjutkan?");
            if (!_continue) return;

            var idbumil = $(this).data('bumil');
            var id = $(this).data('kunjungan')
            fetch(path + 'bumil/deletepemeriksaan', {
                method: 'POST',
                body: JSON.stringify({
                    '_http_method': 'delete',
                    'id': id,
                    'idbumil': idbumil
                })
            }).then(res => {
                if (res.status != 200) {
                    if (typeof (res) == 'string')
                        res = JSON.parse(res);

                    if (res.message)
                        defaultCnfigToast.message = res.message;
                    else
                        defaultCnfigToast.message = "Delete Gagal";

                    defaultCnfigToast.time = moment().format('YYYY-MM-DD HH:ss')
                    defaultCnfigToast.wrapper = 'body';
                    makeToast(defaultCnfigToast);
                    defaultCnfigToast.wrapper = 'form';
                } else
                    return res.json();

            }).then(res => {
                if (!res)
                    return;

                else {
                    if (typeof (res) == 'string')
                        res = JSON.parse(res);

                    if (res.message)
                        defaultCnfigToast.message = res.message;
                    else
                        defaultCnfigToast.message = "Delete Berhasil";

                    defaultCnfigToast.time = moment().format('YYYY-MM-DD HH:ss');
                    defaultCnfigToast.wrapper = 'body';
                    makeToast(defaultCnfigToast);
                    defaultCnfigToast.wrapper = 'form';

                    renderTable($("#canvas_detail_pemeriksaan"), res.data, idbumil);
                }
            }).catch(res => {
                if (!res)
                    return;

                else {
                    if (typeof (res) == 'string')
                        res = JSON.parse(res);

                    if (res.message)
                        defaultCnfigToast.message = res.message;
                    else if (res.responseJSON.message)
                        defaultCnfigToast.message = res.responseJSON.message;
                    else
                        defaultCnfigToast.message = "Delete Gagal";

                    defaultCnfigToast.time = moment().format('YYYY-MM-DD HH:ss')

                    defaultCnfigToast.wrapper = 'body';
                    makeToast(defaultCnfigToast);
                    defaultCnfigToast.wrapper = 'form';
                }
            });
        });
    }

    function addPemeriksan(bumil, cached = {}, isEdit = undefined) {
        var opsiTahun = {};
        var opsiBulan = {};
        var tahunIni = new Date().getFullYear();

        if(isEdit == undefined){
            isEdit = Object.keys(cached).length > 0;
        }
        tahunIni = parseInt(tahunIni)
        for (let index = tahunIni; index > (tahunIni - 10); index--) {
            opsiTahun[index] = {
                text: index
            }
        }
        for (let index = 0; index < 12; index++) {
            opsiBulan[index + 1] = {
                text: daftarBulan[index]
            }
        }
        
        var modalConfig = {
            modalId: 'pemeriksaan-bumil',
            wrapper: "body",
            opt: {
                clickToClose: false,
                type: 'form-custom',
                ajax: true,
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
                    if (typeof (res) == 'string')
                        res = JSON.parse(res);

                    defaultCnfigToast.time = moment().format('YYYY-MM-DD HH:ss');
                    defaultCnfigToast.wrapper = 'form';
                    defaultCnfigToast.message = res.message;
                    setTimeout(function () {
                        $("#pemeriksaan-bumil").modal('hide');
                    }, 1000);
                    makeToast(defaultCnfigToast);
                    setTimeout(function () {
                        renderTable($("#canvas_detail_pemeriksaan"), res.data, bumil.id)
                    }, 2000);

                },
                submitError: function (res) {
                    endLoading();
                    if (typeof (res) == 'string')
                        res = JSON.parse(res);

                    if (res.message)
                        defaultCnfigToast.message = res.message;
                    else if (res.responseJSON.message)
                        defaultCnfigToast.message = res.responseJSON.message;
                    else
                        defaultCnfigToast.message = "Sumbit Failed";

                    defaultCnfigToast.time = moment().format('YYYY-MM-DD HH:ss')
                    defaultCnfigToast.wrapper = 'form';
                    makeToast(defaultCnfigToast);

                },
                open: true,
                destroy: true,
                modalPos: 'right',
                size: 'modal-md',
                saatBuka: (innerOpt) => {
                    $('#periksa-bumil').form();
                },
                saatTutup: () => {
                },
                formOpt: {
                    enctype: 'multipart/form-data',
                    formId: 'periksa-bumil',
                    formAct: path + 'bumil/periksa',
                    formMethod: 'POST',
                },
                modalTitle: 'Form Pemeriksaan Ibu Hamil',
                modalBody: {
                    input: [],
                    buttons: []
                },
            }
        };
        var url = path + 'uihelper/form/?f=forms/periksa_bumil_'+role+'&sv=' + JSON.stringify({formid: 'periksa-bumil', isEdit: isEdit, ibu: bumil.id});

        if(Object.keys(cached).length > 0){
            url += '&ed=' + JSON.stringify(cached);
        }

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
                console.log(modalConfig);
                generateModal(modalConfig.modalId, modalConfig.wrapper, modalConfig.opt)
            }
        });
    }

    function convert(data, id) {
        var cl = '';
        var date = new Date();
        if(role == 'kader'){
            data.forEach(row => {
                icon = '';
                icon += '<i style="font-size: 12px;cursor:pointer" data-kunjungan="' + row.id + '" data-bumil="' + id + '" class="text-warning ml-2 edit-kunjungan-bumil fas fa-pencil-alt" aria-hidden="true"></i>';
                icon += '<i style="font-size: 12px;cursor:pointer" data-bumil="' + id + '" data-kunjungan="' + row.id + '" class="text-danger ml-2 hapus-kunjungan-bumil fas fa-trash-alt" aria-hidden="true"></i>';

                var usia = row['usia_kehamilan'];
                var bulan = Math.floor(usia / 30) + ' Bulan';
                var hari =  usia % 30 + ' Hari';


                cl += '<tr>' +
                    '<td>' + row.tgl_periksa + '</td>'+
                    '<td>' + row.tahun + '</td>'+
                    '<td>' + daftarBulan[row.bulan - 1] + '</td>' +
                    '<td>' + row.nama_pemeriksa + '</td>';

                cl+= '<td>' + (bulan + ', ' + hari) +'</td>' +
                    '<td>' + row.gravida +'</td>' +
                    '<td>' + (row.bb || '-') +'</td>' +
                    '<td>' + (row.tb || '-') +'</td>' +
                    '<td>' + (row.lila || '-') +'</td>';
                cl += '<td>' + icon + '</td>';
                cl += '</tr>'
            })
        }else{
            data.forEach(row => {
                icon = '';
                if(role == 'bidan'){
                    icon += '<i style="font-size: 20px;cursor:pointer" data-kunjungan="' + row.id + '" data-bumil="' + id + '" class="text-warning ml-2 edit-kunjungan-bumil fas fa-pencil-alt" aria-hidden="true"></i>';
                    icon += '<i style="font-size: 20px;cursor:pointer" data-bumil="' + id + '" data-kunjungan="' + row.id + '" class="text-danger ml-2 hapus-kunjungan-bumil fas fa-trash-alt" aria-hidden="true"></i>';
                }
                icon += '<a target="_blank" href="'+(path + 'bumil/kunjungan/' + id + row.id)+'"><i style="font-size: '+ (role == 'admin' ? '20px' : '20px') +';" class="text-info ml-2 detail-kunjungan-bumil simple-icon-info" aria-hidden="true"></i></a>';

                var obstetrik = 'Gravida: ' + row['gravida'] + '<br> Partus: ' + row['paritas'] + ' <br>Abortus: ' + row['abortus'] + ' <br>Hidup: ' + row['hidup'];

                cl += '<tr>' +
                    '<td>' + row.tgl_periksa + '</td>'+
                    '<td>' + row.tahun + '</td>'+
                    '<td>' + daftarBulan[row.bulan - 1] + '</td>' +
                    '<td>' + row.nama_pemeriksa + '</td>';


                cl+= '<td>' + obstetrik +'</td>' +
                    '<td>' + (row.hpht || '-') +'</td>' +
                    '<td>' + (row.hpl || '-') +'</td>' +
                    '<td>' + (row.persalinan_sebelumnya || '-') +'</td>' +
                    '<td>' + (row.bb ? row.bb + 'Kg' : '') +'</td>' +
                    '<td>' + (row.bb_sebelum ? row.bb_sebelum + 'Kg' : '') +'</td>' +
                    '<td>' + (row.tb ? row.tb + 'cm' : '') +'</td>' +
                    '<td>' + (row.buku_kia == 1 ? 'Memiliki' : 'Tidak Memiliki') +'</td>' ;
    
                cl += '<td>' + icon + '</td>';
                cl += '</tr>'
            })
        }
        
        return cl;
    }

});