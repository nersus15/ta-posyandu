<form id="<?= $formid ?>" action="<?= base_url('bumil/periksa') ?>" method="POST">
    <input type="hidden" name="ibu" value="<?= $ibu ?>">
    <input type="hidden" name="id" value="<?php $ambil_data('id') ?>">
    <input type="hidden" id="method" name="_http_method" value="<?= $isEdit ? 'update' : 'POST' ?>">

    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <label for="">Nama Pemeriksa <span class="symbol-required"></span></label>
                <input type="text" name="nama_pemeriksa" required value="<?= $ambil_data('nama_pemeriksa', null, true) ?>" id="pemeriksa" class="form-control">
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label for="">Posyandu</label>
                <input type="text" name="posyandu" value="<?= $ambil_data('posyandu', null, true) ?>" id="posyandu" class="form-control">
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label for="">Nama Dukun</label>
                <input type="text" name="dukun" value="<?= $ambil_data('dukun', null, true) ?>" id="dukun" class="form-control">
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <h4 class="col-12">Riwayat Obstetrik</h4>
            <div class="form-group">
                <label for="">Gravida</label>
                <input type="text" data-rule-digits="true" name="gravida" value="<?= $ambil_data('gravida', null, true) ?>" id="gravida" class="form-control">
            </div>
            <div class="form-group">
                <label for="">Partus</label>
                <input type="text" data-rule-digits="true" name="paritas" value="<?= $ambil_data('paritas', null, true) ?>" id="paritas" class="form-control">
            </div>
            <div class="form-group">
                <label for="">Abortus</label>
                <input type="text" data-rule-digits="true" name="abortus" value="<?= $ambil_data('abortus', null, true) ?>" id="abortus" class="form-control">
            </div>
            <div class="form-group">
                <label for="">Anak Hidup</label>
                <input type="text" data-rule-digits="true" name="hidup" value="<?= $ambil_data('hidup', null, true) ?>" id="hidup" class="form-control">
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <h4 class="col-12">Pemeriksaan Bidan</h4>
            <div class="form-group">
                <label for="kunjungan">Tanggal Periksa</label>
                <input type="text" name="tgl_periksa" value="<?= $ambil_data('tgl_periksa', null, true) ?>" id="kunjungan" class="form-control datepicker" data-date-format="yyyy-mm-dd" autocomplete="off">
            </div>
            <div class="form-group">
                <label for="hpht">Tanggal HPHT</label>
                <input type="text" name="hpht" value="<?= $ambil_data('hpht', null, true) ?>" id="hpht" class="form-control datepicker" data-date-format="yyyy-mm-dd" autocomplete="off">
            </div>
            <div class="form-group">
                <label for="hpl">Taksiran Persalinan</label>
                <input type="text" name="hpl" value="<?= $ambil_data('hpl', null, true) ?>" id="hpl" class="form-control datepicker" data-date-format="yyyy-mm-dd" autocomplete="off">
            </div>
            <div class="form-group">
                <label for="sebelum">Persalinan Sebelumnya</label>
                <input type="text" name="persalinan_sebelumnya" value="<?= $ambil_data('persalinan_sebelumnya', null, true) ?>" id="sebelum" class="form-control datepicker" data-date-format="yyyy-mm-dd" autocomplete="off">
            </div>
            <div class="form-group">
                <label for="bb">BB Sebelum Hamil (dalam Kg)</label>
                <input type="text" data-rule-digits="true" value="<?= $ambil_data('bb', null, true) ?>" name="bb" id="bb" class="form-control" min="10">
            </div>
            <div class="form-group">
                <label for="tb">Tinggi Badan (dalam cm)</label>
                <input type="text" data-rule-digits="true" value="<?= $ambil_data('tb', null, true) ?>" name="tb" id="tb" class="form-control" min="10">
            </div>
            <div class="form-group">
                <label for="bb">Buku KIA</label>
                <select name="buku_kia" id="buku-kia" class="form-control">
                    <option value="1" <?= $ambil_data('buku_kia', 0, true) == 1 ? 'selected' : '' ?>>Memiliki</option>
                    <option value="0" <?= $ambil_data('buku_kia', 0, true) == 0 ? 'selected' : '' ?>>Tidak Memiliki</option>
                </select>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label for="komplikasi">Riwayat komplikasi kebidanan</label>
                <input type="text" name="riwayat_komplikasi" value="<?= $ambil_data('riwayat_komplikasi', null, true) ?>" id="komplikasi" class="form-control">
            </div>
            <div class="form-group">
                <label for="penyakit">Penyakit kronis dan alergi</label>
                <input type="text" name="penyakit" value="<?= $ambil_data('penyakit', null, true) ?>" id="penyakit" class="form-control">
            </div>
            <h4>Rencan Persalinan</h4>
            <div class="form-group">
                <label for="tgl_persalinan">Tanggal</label>
                <input type="text" name="persalinan_tgl" value="<?= $ambil_data('persalinan_tgl', null, true) ?>" id="tgl_persalinan" class="form-control datepicker" data-date-format="yyyy-mm-dd" autocomplete="off">
            </div>
            <div class="form-group">
                <label for="penolong">Rencana penolong</label>
                <select name="persalinan_penolong" id="penolong" class="form-control">
                    <option value="1" <?= $ambil_data('persalinan_penolong', 7, true) == '1' ? 'selected' : '' ?>>Keluarga</option>
                    <option value="2" <?= $ambil_data('persalinan_penolong', 7, true) == '2' ? 'selected' : '' ?>>Dukun</option>
                    <option value="3" <?= $ambil_data('persalinan_penolong', 7, true) == '3' ? 'selected' : '' ?>>Bidan</option>
                    <option value="4" <?= $ambil_data('persalinan_penolong', 7, true) == '4' ? 'selected' : '' ?>>dr. Umum</option>
                    <option value="5" <?= $ambil_data('persalinan_penolong', 7, true) == '5' ? 'selected' : '' ?>>dr. Spesialis</option>
                    <option value="6" <?= $ambil_data('persalinan_penolong', 7, true) == '6' ? 'selected' : '' ?>>Lain lain</option>
                    <option value="7" <?= $ambil_data('persalinan_penolong', 7, true) == '7' ? 'selected' : '' ?>>Tidak ada</option>
                </select>
            </div>
            <div class="form-group">
                <label for="tempat">Tempat</label>
                <select name="persalinan_tempat" id="persalinan_tempat" class="form-control">
                    <option value="1" <?= $ambil_data('persalinan_tempat', 1, true) == '1' ? 'selected' : '' ?>>Rumah</option>
                    <option value="2" <?= $ambil_data('persalinan_tempat', 1, true) == '2' ? 'selected' : '' ?>>Poskesdes/Polindes</option>
                    <option value="3" <?= $ambil_data('persalinan_tempat', 1, true) == '3' ? 'selected' : '' ?>>Pustu</option>
                    <option value="4" <?= $ambil_data('persalinan_tempat', 1, true) == '4' ? 'selected' : '' ?>>Puskesmas</option>
                    <option value="5" <?= $ambil_data('persalinan_tempat', 1, true) == '5' ? 'selected' : '' ?>>RB</option>
                    <option value="6" <?= $ambil_data('persalinan_tempat', 1, true) == '6' ? 'selected' : '' ?>>RSIA</option>
                    <option value="7" <?= $ambil_data('persalinan_tempat', 1, true) == '7' ? 'selected' : '' ?>>RS</option>
                    <option value="8" <?= $ambil_data('persalinan_tempat', 1, true) == '8' ? 'selected' : '' ?>>RS Odha</option>
                </select>
            </div>
            <div class="form-group">
                <label for="pendamping">Pendamping</label>
                <select name="persalinan_pendamping" id="persalinan_pendamping" class="form-control">
                    <option value="1" <?= $ambil_data('persalinan_pendamping', 6, true) == '1' ? 'selected' : '' ?>>Suami</option>
                    <option value="2" <?= $ambil_data('persalinan_pendamping', 6, true) == '2' ? 'selected' : '' ?>>Keluarga</option>
                    <option value="3" <?= $ambil_data('persalinan_pendamping', 6, true) == '3' ? 'selected' : '' ?>>Teman</option>
                    <option value="4" <?= $ambil_data('persalinan_pendamping', 6, true) == '4' ? 'selected' : '' ?>>Tetangga</option>
                    <option value="5" <?= $ambil_data('persalinan_pendamping', 6, true) == '5' ? 'selected' : '' ?>>Lain lain (Dukun)</option>
                    <option value="6" <?= $ambil_data('persalinan_pendamping', 6, true) == '6' ? 'selected' : '' ?>>Tidak ada</option>
                </select>
            </div>
            <div class="form-group">
                <label for="transport">Transportasi</label>
                <select name="persalinan_transportasi" id="persalinan_transportasi" class="form-control">
                    <option value="1" <?= $ambil_data('persalinan_transportasi', 1, true) == '1' ? 'selected' : '' ?>>Sepeda motor</option>
                    <option value="2" <?= $ambil_data('persalinan_transportasi', 1, true) == '2' ? 'selected' : '' ?>>Mobil</option>
                    <option value="3" <?= $ambil_data('persalinan_transportasi', 1, true) == '3' ? 'selected' : '' ?>>Lain lain (Cidomo, becak, benhur, dll)</option>
                </select>
            </div>
            <div class="form-group">
                <label for="donor">Pendonor</label>
                <select name="persalinan_pendonor" id="persalinan_pendonor" class="form-control">
                    <option value="1" <?= $ambil_data('persalinan_pendonor', 5, true) == '1' ? 'selected' : '' ?>>Suami</option>
                    <option value="2" <?= $ambil_data('persalinan_pendonor', 5, true) == '2' ? 'selected' : '' ?>>Keluarga</option>
                    <option value="3" <?= $ambil_data('persalinan_pendonor', 5, true) == '3' ? 'selected' : '' ?>>Teman</option>
                    <option value="4" <?= $ambil_data('persalinan_pendonor', 5, true) == '4' ? 'selected' : '' ?>>Lain lain(Kader, Masyarakat, Polri, Satpam)</option>
                    <option value="5" <?= $ambil_data('persalinan_pendonor', 5, true) == '5' ? 'selected' : '' ?>>Tidak ada</option>
                </select>
            </div>
            <div class="form-group">
                <label for="">Kunjugan Rumah</label>
                <input type="text" name="persalinan_kunjungan_rumah" value="<?= $ambil_data('persalinan_kunjungan_rumah', null, true) ?>" id="kunjungan-rumah" class="form-control">
            </div>
            <div class="form-group">
                <label for="">Kondisi Rumah</label>
                <input type="text" name="persalinan_kondisi_rumah" value="<?= $ambil_data('persalinan_kondisi_rumah', null, true) ?>" id="kondisi-rumah" class="form-control">
            </div>
            <div class="form-group">
                <label for="">Persediaan kain handuk, pakaian bayi bersih dan kering</label>
                <input type="text" name="persalinan_persedian" value="<?= $ambil_data('persalinan_persedian', null, true) ?>" id="persediaan" class="form-control">
            </div>
        </div>
    </div>

    <div class="col-sm-12 mt-4 mb-4">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>