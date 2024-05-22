<form id="<?= $formid ?>" action="<?= base_url('bayi/save') ?>" method="POST">
    <input type="hidden" name="id" value="<?php $ambil_data('id') ?>">
    <input type="hidden" id="method" name="_http_method" value="POST">
    <div class="col-sm-12">
        <div class="form-group">
            <label for="nama">Nama</label>
            <input value="<?php $ambil_data('nama') ?>" type="text" name="nama" id="nama" class="form-control">
        </div>
        <div class="form-group">
            <label for="nik">Kelamin</label>
            <select name="kelamin" id="kelamin" class="form-control">
                <option value="L" <?= $ambil_data('kelamin', null, true) == 'L' ? 'selected' : null ?>>Laki laki</option>
                <option value="P" <?= $ambil_data('kelamin', null, true) == 'P' ? 'selected' : null ?>>Perempuan</option>
            </select>
        </div>
        <div class="form-group">
            <label for="">Berat Badan Saat Lahir (dalam gram)</label>
            <input type="text" data-rule-digits="true" value="<?php $ambil_data('bbl') ?>" name="bbl" id="bbl" class="form-control">
        </div>
        <div class="form-group">
            <label for="ttl">Ingat Tanggal lahir <span class="symbol-required"></span></label>
            <div class="row ">
                <div class="form-check ml-2">
                    <input id="ingat" value="1" class="form-check-input" type="radio" <?= $ambil_data('ingat_ttl', 1, true) == '1' ? 'checked' : '' ?> name="ingat_ttl">
                    <label for="ingat" class="form-check-label">Ingat</label>
                </div>
                <div class="form-check ml-2">
                    <input id="tidak_ingat" value="0" class="form-check-input" type="radio" <?= $ambil_data('ingat_ttl', 1, true) == '0' ? 'checked' : '' ?> name="ingat_ttl">
                    <label for="tidak_ingat" class="form-check-label">Tidak ingat</label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="ttl">Tanggal lahir <span class="symbol-required"></span></label>
            <input autocomplete="off" data-date-format="yyyy-mm-dd" type="text" value="<?php $ambil_data('ttl') ?>" data-rule-required="true" name="tanggal_lahir" id="ttl" class="form-control datepicker">
        </div>
        <div style="display: none;" class="form-group">
            <label for="umur">Umur (dalam hari) <span class="symbol-required"></span></label>
            <input type="text" value="<?php $ambil_data('umur') ?>" data-rule-required="true" name="umur" id="umur" class="form-control">
        </div>
        <div class="form-group">
            <label for="">Nama Ibu <span class="symbol-required"></span></label>
            <input type="text" id="ibu" required maxlength="46" name="ibu" value="<?php $ambil_data('ibu') ?>" class="form-control ibu">
        </div>
        <div class="form-group">
            <label for="">Nama Ayah</label>
            <input type="text" id="ayah" maxlength="46" name="ayah" value="<?php $ambil_data('ayah') ?>" class="form-control ayah">
        </div>
        <div class="form-group">
            <label for="">AKB</label>
            <input type="number" value="<?php $ambil_data('akb') ?>" name="akb" id="akb" class="form-control">
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