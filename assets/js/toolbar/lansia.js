$(document).ready(function () {
    var dtid = "<?= $dtid?>";
    var panel = $("#displayOptions-" + dtid);
    var toolbar = <?= isset($toolbar) ? json_encode($toolbar) : '[]' ?>;
    var role = toolbar.role ? toolbar.role : '';
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
        var opsiTahun = [];
        var tahunIni = new Date().getFullYear();
        tahunIni = parseInt(tahunIni)
        for (let index = tahunIni; index > (tahunIni - 10); index--) {
            opsiTahun.push({
                text: index,
                key: index
            })
        }
        var modalConfig = {
            modalId: 'export-lansia',
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
                        $("#export-lansia").modal('hide');
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
                    $('#form-export-lansia').form();
                },
                saatTutup: () => {
                },
                formOpt: {
                    enctype: 'multipart/form-data',
                    formId: 'form-export-lansia',
                    formAct: path + 'report/lansia',
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
                    ],
                    buttons: [
                        { type: 'submit', text: 'Export', id: "export", class: "btn btn btn-primary" }
                    ]
                },
            }
        };
        generateModal(modalConfig.modalId, modalConfig.wrapper, modalConfig.opt);
    });

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
        fetch(path + 'lansia/periksalist', {
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
                '<div class="col-sm-6 ml-4">' +
                (['bidan', 'admin'].includes(role) ? '' : '<button class="btn btn-primary btn-sm" type="button" id="add-pemeriksaan">Periksa Lansia</button>') +
                '</div>' +
                '</div>' +
                '<div id="canvas_detail_pemeriksaan" class="card-body row">' +

                '</div>' +

                '</div>'
            );

            $("#add-pemeriksaan").click(function () {
                addPemeriksan(rowData)
            });
            $('#close-detail').click(function () {
                induk.empty();
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

    function renderTable(bodyEl, data, idlansia) {
        bodyEl.empty();
        var rowData = data.lansia;
        data = data.pemeriksaan;

        var content = '<table id="table-pemeriksaan-lansia" class="table table-bordered table-hover">' +
            '<thead>' +
            '<tr>' +
            '<th class="middle center" rowspan="2">Tahun</th>' +
            '<th class="middle center" colspan="12">Hasil Penimbangan BB (gr)</th>' +
            '</tr>' +
            '<tr>' +
            '<th class="center">Jan</th>' +
            '<th class="center">Feb</th>' +
            '<th class="center">Mar</th>' +
            '<th class="center">Apr</th>' +
            '<th class="center">Mei</th>' +
            '<th class="center">Jun</th>' +
            '<th class="center">Jul</th>' +
            '<th class="center">Agu</th>' +
            '<th class="center">Sep</th>' +
            '<th class="center">Okt</th>' +
            '<th class="center">Nov</th>' +
            '<th class="center">Des</th>' +
            '</tr>' +
            '</thead>' +
            '<tbody>' +
            convert(data, idlansia)
        '</tbody>' +
            '</table>';

        bodyEl.append(content);
        $(".edit-kunjungan-lansia").click(function () {
            var tahun = $(this).data('tahun');
            var bulan = $(this).data('bulan');
            addPemeriksan(rowData, data[tahun][bulan]);
        });

        $('.hapus-kunjungan-lansia').click(function () {

            var _continue = confirm("Yakin ingin melanjutkan?");
            if (!_continue) return;

            var idlansia = $(this).data('lansia');
            var id = $(this).data('kunjungan')
            fetch(path + 'lansia/deletepemeriksaan', {
                method: 'POST',
                body: JSON.stringify({
                    '_http_method': 'delete',
                    'id': id,
                    'idlansia': idlansia
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

                    renderTable($("#canvas_detail_pemeriksaan"), res.data, idlansia);
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

    function addPemeriksan(lansia, cached = {}) {
        var daftarBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        var opsiTahun = {};
        var opsiBulan = {};
        var tahunIni = new Date().getFullYear();
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
            modalId: 'pemeriksaan-lansia',
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
                    if (typeof (res) == 'string')
                        res = JSON.parse(res);

                    defaultCnfigToast.time = moment().format('YYYY-MM-DD HH:ss');
                    defaultCnfigToast.message = res.message;
                    setTimeout(function () {
                        $("#pemeriksaan-lansia").modal('hide');
                    }, 1000);
                    makeToast(defaultCnfigToast);
                    setTimeout(function () {
                        renderTable($("#canvas_detail_pemeriksaan"), res.data, lansia.id)
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
                    makeToast(defaultCnfigToast);

                },
                open: true,
                destroy: true,
                modalPos: 'def',
                saatBuka: (innerOpt) => {
                    $('#form-periksa-lansia').form();
                },
                saatTutup: () => {
                },
                formOpt: {
                    enctype: 'multipart/form-data',
                    formId: 'form-periksa-lansia',
                    formAct: path + 'lansia/periksa',
                    formMethod: 'POST',
                },
                modalTitle: 'Form Pemeriksaan Lansia',
                modalBody: {
                    input: [
                        {
                            type: 'hidden',
                            name: 'lansia',
                            value: lansia.id
                        },
                        {
                            type: 'hidden',
                            name: '_http_method',
                            value: Object.keys(cached).length > 0 ? 'update' : 'POST'
                        },
                        {
                            type: 'hidden',
                            name: 'id',
                            value: cached.id ? cached.id : ''
                        },
                        {
                            type: 'select', 
                            name: 'tahun',
                            label: 'Pilih Tahun',
                            options: opsiTahun,
                            default: cached.tgl_periksa ? cached.tgl_periksa.substr(0, 4) : null
                        },
                        {
                            type: 'select', 
                            name: 'bulan',
                            label: 'Pilih Bulan',
                            options: opsiBulan,
                            default: cached.tgl_periksa ? parseInt(cached.tgl_periksa.substr(5, 2)) : null
                        },
                        {
                            'type': 'text',
                            'label': 'Berat Badan (gr)',
                            'name': 'berat',
                            'id': 'berat',
                            'attr': 'data-rule-required="true" autocomplete="off" data-rule-digits="true"',
                            'value': cached.berat ? cached.berat : ''
                        },
                        {
                            'type': 'text',
                            'label': 'Nama Pemeriksa (opsional)',
                            'name': 'pemeriksa',
                            'id': 'pemeriksa',
                            'value': cached.nama_pemeriksa ? cached.nama_pemeriksa : ''
                        },

                    ],
                    buttons: [
                        { type: 'submit', text: 'Simpan', id: "login", class: "btn btn btn-primary" }
                    ]
                },
            }
        };
        generateModal(modalConfig.modalId, modalConfig.wrapper, modalConfig.opt);
    }

    function convert(data, id) {
        var cl = '';
        var date = new Date();

        Object.keys(data).forEach(tahun => {
            cl += '<tr>' +
                '<td>' + tahun + '</td>';
            for (var i = 1; i <= 12; i++) {
                var bulan = i;
                var berat = data[tahun][bulan] ? data[tahun][bulan]['berat'] : '-';
                var idKunjungan = data[tahun][bulan] ? data[tahun][bulan]['id'] : null;
                var pemeriksa = data[tahun][bulan] ? (data[tahun][bulan]['nama_pemeriksa'] ?? '') : '';
                var value = berat;
                var icon = '';
                if (value != '-') {
                    icon += '<i style="font-size: 12px;cursor:pointer" data-pemeriksa="' + pemeriksa + '" data-kunjungan="' + idKunjungan + '" data-bulan="' + i + '" data-value="' + (value == '-/-' ? null : value) + '" data-tahun="' + tahun + '" data-lansia="' + id + '" class="text-warning ml-2 edit-kunjungan-lansia fas fa-pencil-alt" aria-hidden="true"></i>';

                    icon += '<i style="font-size: 12px;cursor:pointer" data-lansia="' + id + '" data-kunjungan="' + idKunjungan + '" class="text-danger ml-2 hapus-kunjungan-lansia fas fa-trash-alt" aria-hidden="true"></i>';
                }
                if(role == 'admin') icon = '';
                cl += '<td>' + (date.getFullYear() == tahun && i > (date.getMonth() + 1) ? '' : (value + icon)) + '</>';
            }
            cl += '</tr>'
        })
        return cl;
    }
});