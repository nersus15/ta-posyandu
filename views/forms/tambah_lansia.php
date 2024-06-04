<form action="<?=base_url('lansia/save') ?>" id="<?= $formid ?>" method="POST">
    <input type="hidden" name="id" value="<?php $ambil_data('id') ?>">
    <input type="hidden" id="method" name="_http_method" value="POST">
    <div class="col-sm-12">
        <div class="form-group">
            <label for="nama">Nama <span class="symbol-required"></span></label>
            <input value="<?= $ambil_data('nama', null, true) ?>" type="text" required name="nama" id="nama" class="form-control">
        </div>
        <div class="form-group">
            <label for="nik">NIK</label>
            <input value="<?= $ambil_data('nik', null, true) ?>" minlength="16" maxlength="16" type="text" name="nik" id="nik" class="form-control">
        </div>
        <div class="form-group">
            <label for="ttl">Ingat Tanggal lahir <span class="symbol-required"></span></label>
            <div class="row ">
                <div class="form-check ml-2">
                    <input id="ingat" value="1" class="form-check-input" type="radio" <?= $ambil_data('estimasi_ttl', 0, true) == '0' ? 'checked' : '' ?> name="ingat_ttl">
                    <label for="ingat" class="form-check-label">Ingat</label>
                </div>
                <div class="form-check ml-2">
                    <input id="tidak_ingat" value="0" class="form-check-input" type="radio" <?= $ambil_data('estimasi_ttl', 0, true) == '1' ? 'checked' : '' ?> name="ingat_ttl">
                    <label for="tidak_ingat" class="form-check-label">Tidak ingat</label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="ttl">Tanggal lahir <span class="symbol-required"></span></label>
            <input type="text" autocomplete="off" data-date-format="yyyy-mm-dd" value="<?= $ambil_data('ttl', null, true) ?>" data-rule-required="true" name="tanggal_lahir" id="ttl" class="form-control datepicker" >
        </div>
        <div style="display: none;" class="form-group">
            <label for="umur">Umur (dalam tahun)<span class="symbol-required"></span></label>
            <input type="text" value="<?= $ambil_data('umur', null, true) ?>" data-rule-required="true" data-rule-digits="true" name="umur" id="umur" class="form-control">
        </div>
        <div class="form-group">
            <label for="alamat">Alamat <span class="symbol-required"></span></label>
            <textarea name="alamat" id="alamat" rows="5" class="form-control"><?php $ambil_data('alamat') ?></textarea>
        </div>
    </div>

    <div class="col-sm-12 mt-4 mb-4">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>