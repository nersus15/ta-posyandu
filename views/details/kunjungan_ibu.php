<?php
function dariMap($nilai, $map = [], $def = null)
{
    if (empty($nilai)) return $def;

    return !isset($map, $nilai) ? $def : $map[$nilai];
}
$mapPenolong = [
    "1" => "Keluarga",
    "2" => "Dukun",
    "3" => "Bidan",
    "4" => "dr. Umum",
    "5" => "dr. Spesialis",
    "6" => "Lain lain",
    "7" => "Tidak ada",
];
$mapTempat = [
    "1" => "Rumah",
    "2" => "Poskesdes/Polindes",
    "3" => "Pustu",
    "4" => "Puskesmas",
    "5" => "RB",
    "6" => "RSIA",
    "7" => "RS",
    "8" => "RS Odha",
];
$mapPendamping = [
    "1" => "Suami",
    "2" => "Keluarga",
    "3" => "Teman",
    "4" => "Tetangga",
    "5" => "Lain lain (Dukun)",
    "6" => "Tidak ada",
];
$mapTransport = [
    "1" => "Sepeda motor",
    "2" => "Mobil",
    "3" => "Lain lain (Cidomo, becak, benhur, dll)",
];
$mapDonor = [
    "1" => "Suami",
    "2" => "Keluarga",
    "3" => "Teman",
    "4" => "Lain lain(Kader, Masyarakat, Polri, Satpam)",
    "5" => "Tidak ada",
];
?>

<!-- <div class="container-fluid"> -->
<!-- SELECT2 EXAMPLE -->
<div class="card card-default">
    <!-- /.card-header -->
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <ul style="list-style: none;">
                    <!-- <li>Puskesmas: <b><?php // $kunjungan['petugas']['faskes'] 
                                            ?></b></li> -->
                    <li>No. Ibu: <b><?= $nomor ?></b></li>
                    <li>Nama Lengkap: <b><?= $nama ?></b></li>
                    <li>Nama Suami: <b><?= $nama_suami ?></b></li>
                </ul>
            </div>
            <div class="col-sm-12 col-md-6">
                <ul style="list-style: none;">
                    <li>Tanggal Lahir: <b><?= $tanggal_lahir ?></b></li>
                    <li>Alamat Domisili: <b><?= $domisili ?></b></li>
                    <li>Kabupaten: <b><?= 'Lombok Timur' ?></b></li>
                    <li>Prov: <b><?= 'Nusa Tenggara Barat' ?></b></li>

                </ul>
            </div>
            <div class="col-sm-12 col-md-6">
                <ul style="list-style: none;">
                    <li>Umur: <b><?= (!empty($umur) ? $umur . ' Tahun' : '') ?></b></li>
                    <li>Agama: <b><?= $agama ?></b></li>
                    <li>Pendidikan: <b><?= $pendidikan ?></b></li>
                    <li>Pekerjaan: <b><?= $pekerjaan ?></b></li>
                    <li>Tgl Register: <b><?= substr($createdAt, 0, 10) ?></b></li>
                </ul>
            </div>

            <div class="col-sm-12 col-md-6">
                <ul style="list-style: none;">
                    <li><span <?= $kartu_kesehatan == 'jamkesmas' ? 'style="border: 1px solid black; border-radius: 100%; padding: 10px"' : '' ?>>Jamkesmas</span>/<span <?= $kartu_kesehatan == 'jamsostek' ? 'style="border: 1px solid black; border-radius: 100%; padding: 10px"' : '' ?>>Jamsostek</span>/<span <?= $kartu_kesehatan == 'jamkesda' ? 'style="border: 1px solid black; border-radius: 100%; padding: 10px"' : '' ?>>Jamkesda Askes</span></li>
                    <li class="mt-3">Golongan Darah: <b><?= $golongan_darah ?></b></li>
                    <li>Telp/Hp: <b><?= $hp ?></b></li>
                </ul>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-sm-12 col-md-4">
                <h4><b>RIWAYAT OBSTETRIK</b></h4>
                <ul style="list-style: none;">
                    <li>Gravida: <b><?= $kunjungan['gravida'] ?></b></li>
                    <li>Partus: <b><?= $kunjungan['paritas'] ?></b></li>
                    <li>Abortus: <b><?= $kunjungan['abortus'] ?></b></li>
                    <li>Hidup: <b><?= $kunjungan['hidup'] ?></b></li>
                </ul>
            </div>
            <div class="col-sm-12 col-md-4">
                <h3><b>PEMERIKSAAN BIDAN</b></h3>
                <ul style="list-style: none;">
                    <li>Tanggal Periksa: <b><?= $kunjungan['tgl_periksa'] ?></b></li>
                    <li>Tanggal HPHT: <b><?= $kunjungan['hpht'] ?></b></li>
                    <li>Taksiran Persalinan: <b><?= $kunjungan['hpl'] ?></b></li>
                    <li>Persalinan Sebelumnya: <b><?= empty($kunjungan['persalinan_sebemulnya']) || $kunjungan['persalinan_sebemulnya'] == '0000-00-00' ? '' : $kunjungan['persalinan_sebemulnya'] ?></b></li>
                </ul>
            </div>
            <div class="col-sm-12 col-md-4">
                <h3></h3>
                <ul style="list-style: none;">
                    <li>BB Sebelum Hamil: <b><?= empty($kunjungan['bb']) ? '' : $kunjungan['bb'] . ' Kg' ?></b></li>
                    <li>Tinggi Badan: <b><?= empty($kunjungan['tb']) ? '' : $kunjungan['tb'] ?></b></li>
                    <li>Buku KIA: <b><?= $kunjungan['buku_kia'] == 1 ? 'Memiliki' : 'Tidak Memiliki' ?></b></li>

                </ul>
            </div>

            <div class="col-sm-12">
                <ul style="list-style: none;">
                    <li>Riwayat Komplikasi Kebidanan: <p><?= $kunjungan['riwayat_komplikasi'] ?></p>
                    </li>
                    <li>Penyakit Kronis dan Alergi <p><?= $kunjungan['penyakit'] ?></p>
                    </li>
                </ul>
            </div>

            <div class="col-sm-12">
                <h3>Rencana Persalinan</h3>
                <ul style="list-style: none;">
                    <li>Tanggal: <b><?= $kunjungan['persalinan_tgl'] ?></b></li>
                    <li>Rencana Penolong: <b><?= dariMap($kunjungan['persalinan_penolong'], $mapPenolong) ?></b></li>
                    <li>Tempat: <b><?= dariMap($kunjungan['persalinan_tempat'], $mapTempat) ?></b></li>
                    <li>Pendamping: <b><?= dariMap($kunjungan['persalinan_pendamping'], $mapPendamping) ?></b></li>
                    <li>Transportasi: <b><?= dariMap($kunjungan['persalinan_transportasi'], $mapTransport) ?></b></li>
                    <li>Pendonor: <b><?= dariMap($kunjungan['persalinan_pendonor'], $mapDonor) ?></b></li>
                    <li>Kunjungan Rumah: <b><?= $kunjungan['persalinan_kunjungan_rumah'] ?></b></li>
                    <li>Kondisi Rumah: <b><?= $kunjungan['persalinan_kondisi_rumah'] ?></b></li>
                    <li>Persediaan kain handuk, pakaian bayi bersih dan kering: <p><?= $kunjungan['persalinan_persedian'] ?></p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">

    </div>
</div>
<!-- /.card -->
<!-- </div> -->