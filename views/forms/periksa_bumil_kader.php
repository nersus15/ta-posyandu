<form id="<?= $formid ?>" action="<?= base_url('bumil/periksa') ?>" method="POST">
    <input type="hidden" name="ibu" value="<?= $ibu ?>">
    <input type="hidden" name="id" value="<?php $ambil_data('id') ?>">
    <input type="hidden" id="method" name="_http_method" value="<?= $isEdit ? 'update' : 'POST' ?>">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label for="">Tanggal Periksa <span class="symbol-required"></span></label>
                <input type="text" data-rule-required="true" name="tgl_periksa" value="<?= $ambil_data('tgl_periksa', null, true) ?>" id="kunjungan" class="form-control datepicker" data-date-format="yyyy-mm-dd" autocomplete="off">
            </div>
            <div class="form-group">
                <label for="">Nama Pemeriksa</label>
                <input type="text" name="nama_pemeriksa" value="<?= $ambil_data('nama_pemeriksa', null, true) ?>" id="pemeriksa" class="form-control">
            </div>
            <div class="form-group">
                <label for="">Usia Kehamilan (hari)</label>
                <input type="number" name="usia_kehamilan" value="<?= $ambil_data('usia_hamil', null, true) ?>" id="usia_hamil" class="form-control">
            </div>
            <div class="form-group">
                <label for="">Hamil Ke</label>
                <input type="number" name="gravida" value="<?= $ambil_data('gravida', null, true) ?>" id="gravida" class="form-control">
            </div>
            <div class="form-group">
                <label for="">Berat badan (Kg)</label>
                <input type="number" name="bb" value="<?= $ambil_data('bb', null, true) ?>" id="bb" class="form-control">
            </div>
            <div class="form-group">
                <label for="">Tinggi Badan (cm)</label>
                <input type="number" name="tb" value="<?= $ambil_data('tb', null, true) ?>" id="tb" class="form-control">
            </div>
            <div class="form-group">
                <label for="">Lingkar Lengan Atas (cm)</label>
                <input type="number" name="lila" value="<?= $ambil_data('lila', null, true) ?>" id="lila" class="form-control">
            </div>
            <div class="form-group">
                <label for="">Tinggi Fundus (cm)</label>
                <input type="number" name="fundus" value="<?= $ambil_data('fundus', null, true) ?>" id="fundus" class="form-control">
            </div>
            <div class="form-group">
                <label for="">HB</label>
                <input type="number" name="hb" value="<?= $ambil_data('hb', null, true) ?>" id="hb" class="form-control">
            </div>
        </div>
    </div>
    <div class="col-sm-12 mt-4 mb-4">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>