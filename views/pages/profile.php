<?php
$db = \Config\Database::connect();
$dataWil = $db->table('wilayah')->get()->getResultArray();
$wilayah = [
    'desa' => [],
    'kecamatan' => []
];
$session = session();
$respnse = $session->getFlashdata('response');
foreach ($dataWil as $w) {
    if ($w['level'] == 3)
        $wilayah['kecamatan'][$w['id']] = $w['nama'];
    elseif ($w['level'] == 4)
        $wilayah['desa'][$w['id']] = $w['nama'];
}
?>
<style>
    .custom-input {
        border: none;
        border-bottom: 1px solid grey;
    }

    .custom-input:active {
        border: none;
        border-bottom: 1px solid grey;
    }

    .custom-input:focus {
        border: none;
        border-bottom: 1px solid grey;
    }
</style>
<div class="mt-4">
    <h3>Profile</h3>
    <p class="text-danger"><?= $respnse ?></p>
    <form enctype="multipart/form-data" action="<?= base_url('profile/update') ?>" method="post">
        <div class="row mt-4" style="row-gap: 10px;">
            <div class="col-sm-12 col-md-4">
                <div class="profile-pic">
                    <label class="-label" for="file">
                        <span class="fas fa-camera"></span>
                        <span>Change Image</span>
                    </label>
                    <input accept="image/*" name="photo" id="file" type="file" />
                    <img style="" id="output" src="<?= assets_url('img/profile/' . $photo) ?>" alt="Poto profile">

                </div>
            </div>
            <div class="col-sm-12 col-md-8">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td>Username</td>
                                    <td>
                                        <input type="text" name="username" value="<?= $username ?>" readonly id="username" class="form-control custom-input">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Nama Lengkap</td>
                                    <td>
                                        <input type="text" name="nama_lengkap" value="<?= $nama_lengkap ?>" id="nama_lengkap" class="form-control custom-input">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>
                                        <input type="email" name="email" value="<?= $email ?>" id="email" class="form-control custom-input">
                                    </td>
                                </tr>
                                <tr>
                                    <td>No.Hp <span class="symbol-required"></span></td>
                                    <td>
                                        <input type="text" name="hp" value="<?= $hp ?>" id="hp" class="form-control custom-input">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Alamat</td>
                                    <td>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <select class="form-control" name="kecamatan" id="kecamatan">
                                                    <?php foreach ($wilayah['kecamatan'] as $id => $kec) : ?>
                                                        <option value="<?= $id ?>" <?= $id == substr($alamat, 0, 8) . '.0000' ? 'selected' : '' ?>><?= 'Kec. ' . $kec ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                            </div>
                                            <div class="col-sm-6">
                                                <select class="form-control" name="desa" id="desa">
                                                    <option value="">Pilih Desa</option>
                                                    <?php foreach ($wilayah['desa'] as $id => $desa) : ?>
                                                        <option value="<?= $id ?>" <?= $id == $alamat ? 'selected' : '' ?>><?= 'Desa' . $desa ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Wilayah Kerja</td>
                                    <td>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <select class="form-control" name="kecamatan_kerja" id="kecamatan_kerja">
                                                    <?php foreach ($wilayah['kecamatan'] as $id => $kec) : ?>
                                                        <option value="<?= $id ?>" <?= $id == substr($wilayah_kerja, 0, 8) . '.0000' ? 'selected' : '' ?>><?= 'Kec. ' . $kec ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                            </div>
                                            <div class="col-sm-6">
                                                <select class="form-control" name="desa_kerja" id="desa_kerja">
                                                    <option value="">Pilih Desa</option>
                                                    <?php foreach ($wilayah['desa'] as $id => $desa) : ?>
                                                        <option value="<?= $id ?>" <?= $id == $wilayah_kerja ? 'selected' : '' ?>><?= 'Desa' . $desa ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Faskes</td>
                                    <td>
                                        <input minlength="8" class="form-control custom-input" type="text" readonly value="<?= $faskes ?>" name="faskes" id="faskes">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Password <small>(isi jika ingin merubah password)</small></td>
                                    <td>
                                        <input minlength="8" class="form-control custom-input" type="password" name="password" id="password">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <div style="float: right;" class="">
                            <button type="submit" class="btn btn-info">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    $(document).ready(function() {
        $('#kecamatan,#desa, #kecamatan_kerja,#desa_kerja').select2();
        var loadFile = function(event) {
            var image = document.getElementById("output");
            image.src = URL.createObjectURL(event.target.files[0]);
        };
        $("#file").on('change', loadFile)
    })
</script>