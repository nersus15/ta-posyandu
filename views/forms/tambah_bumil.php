<form id="<?= $formid ?>" action="<?= base_url('bumil/save') ?>" method="POST">
    <input type="hidden" name="id" value="<?php $ambil_data('id') ?>">
    <input type="hidden" id="method" name="_http_method" value="POST">
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <?php if (is_login('bidan')) : ?>
                <div class="form-group">
                    <label for="no">Nomor Ibu <span class="symbol-required"></span></label>
                    <input value="<?= $ambil_data('no', null, true) ?>" type="text" data-rule-digits="true" data-rule-required="true" maxlength="10" minlength="9" name="nomor" id="no" class="form-control">
                </div>
            <?php endif ?>
            <div class="form-group">
                <label for="nama">Nama Ibu <span class="symbol-required"></span></label>
                <input value="<?= $ambil_data('nama', null, true) ?>" type="text" data-rule-required="true" name="nama" id="nama" class="form-control">
            </div>
            <div class="form-group">
                <label for="nama-suami">Nama Suami <span class="symbol-required"></span></label>
                <input value="<?= $ambil_data('suami', null, true) ?>" type="text" data-rule-required="true" name="nama_suami" id="nama-suami" class="form-control">
            </div>
            <div class="form-group">
                <label for="ttl">Ingat Tanggal lahir <span class="symbol-required"></span></label>
                <div class="row ">
                    <div class="form-check ml-2">
                        <input id="ingat" value="1" class="form-check-input" type="radio" <?= $ambil_data('ttl_estimasi', 0, true) == '0' ? 'checked' : '' ?> name="ingat_ttl">
                        <label for="ingat" class="form-check-label">Ingat</label>
                    </div>
                    <div class="form-check ml-2">
                        <input id="tidak_ingat" value="0" class="form-check-input" type="radio" <?= $ambil_data('ttl_estimasi', 0, true) == '1' ? 'checked' : '' ?> name="ingat_ttl">
                        <label for="tidak_ingat" class="form-check-label">Tidak ingat</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="ttl">Tanggal lahir <span class="symbol-required"></span></label>
                <input autocomplete="off" data-date-format="yyyy-mm-dd" type="text" value="<?= $ambil_data('ttl', null, true) ?>" name="tanggal_lahir" id="ttl" class="form-control datepicker" data-rule-required="true">
            </div>
            <div style="display: none;" class="form-group">
                <label for="umur">Umur <span class="symbol-required"></span></label>
                <input type="text" value="<?= $ambil_data('umur', null, true) ?>" data-rule-required="true" data-rule-digits="true" data-rule-min="17" name="umur" id="umur" class="form-control">
            </div>
            <div class="form-group">
                <label for="alamat">Alamat Domisili <span class="symbol-required"></span></label>
                <textarea name="domisili" id="domisili" rows="5" class="form-control"><?php $ambil_data('domisili') ?></textarea>
            </div>
            <div class="form-group">
                <label for="alamat">Alamat <span class="symbol-required"></span></label>
                <textarea name="alamat" id="alamat" rows="5" class="form-control"><?php $ambil_data('alamat') ?></textarea>
            </div>
        </div>

        <div class="col-sm-12 col-md-6">
            <div class="form-group">
                <label for="pendidikan">Pendidikan</label>
                <select name="pendidikan" id="pendidikan" class="form-control">
                    <option value="-">Tidak sekolah</option>
                    <option value="TK">PAUD</option>
                    <option value="SD">SD</option>
                    <option value="SMP">SMP</option>
                    <option value="SMA">SMA</option>
                    <option value="D1">D1</option>
                    <option value="D3">D3</option>
                    <option value="D4">D4</option>
                    <option value="S1">S1</option>
                    <option value="S2">S2</option>
                    <option value="S3">S3</option>
                </select>
            </div>
            <div class="form-group">
                <label for="pekerjaan">Pekerjaan</label>
                <input value="<?= $ambil_data('pekerjaan', null, true) ?>" type="text" name="pekerjaan" id="pekerjaan" class="form-control">
            </div>
            <div class="form-group">
                <label for="agama">Agama</label>
                <select name="agama" id="agama" class="form-control">
                    <option value="islam">Islam</option>
                    <option value="hindu">Hindu</option>
                    <option value="buda">Buda</option>
                    <option value="kristen protestan">Kristen Protestan</option>
                    <option value="kristen katolik">Kristen Katolik</option>
                    <option value="konghucu">Konghucu</option>
                </select>
            </div>
            <div class="form-group">
                <label for="darah">Golongan darah</label>
                <select name="golongan_darah" id="darah" class="form-control">
                    <option value="-" <?= $ambil_data('darah', null, true) == '-' ? 'selected' : '' ?>>Tidak tahu</option>
                    <option value="A" <?= $ambil_data('darah', null, true) == 'A' ? 'selected' : '' ?>>A</option>
                    <option value="B" <?= $ambil_data('darah', null, true) == 'B' ? 'selected' : '' ?>>B</option>
                    <option value="O" <?= $ambil_data('darah', null, true) == 'O' ? 'selected' : '' ?>>O</option>
                    <option value="AB" <?= $ambil_data('darah', null, true) == 'AB' ? 'selected' : '' ?>>AB</option>
                </select>
            </div>
            <div class="form-group">
                <label for="kartu">Kartu kesehatan</label>
                <select name="kartu_kesehatan" id="kartu" class="form-control">
                    <option value="" <?= empty($ambil_data('kartu', null, true))  ? 'selected' : '' ?>>Tidak ada</option>
                    <option value="jamkesmas" <?= $ambil_data('kartu', null, true) == 'jamkesmas' ? 'selected' : '' ?>>Jamkesmas</option>
                    <option value="jamsostek" <?= $ambil_data('kartu', null, true) == 'jamsostek' ? 'selected' : '' ?>>Jamsostek</option>
                    <option value="jamkesda" <?= $ambil_data('kartu', null, true) == 'jamkesda' ? 'selected' : '' ?>>Jamkesda Askes</option>
                </select>
            </div>
            <div class="form-group">
                <label for="hp">Nomor Hp</label>
                <input type="text" name="hp" id="hp" class="form-control" maxlength="13" value="<?= $ambil_data('hp', null, true) ?>">
            </div>
        </div>
    </div>

    <div class="col-sm-12 mt-4 mb-4">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>