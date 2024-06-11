<!-- Load CSS File -->
<style>
    <?php load_style('vendor/bootstrap/css/bootstrap.css') ?>
</style>
<style>
    <?php // load_style('themes/dore/css/dore.light.green.css') 
    ?>
</style>
<style>
    * {
        background-color: white;
    }
    th{
        font-size: 14px !important;
    }
    td{
        font-size: 12px !important;
    }
</style>
<div class="main">
    <h1 class="card-title ml-4">Data Bayi <?= $usia ?></h1>
    <div class="table-responsive container-fluid dt-bootstrap4">
        <table class="dataTable table tabeleditor table-nomargin table-condensed table-no-topborder table-bordered- table-striped- table-hover dataTable no-footer dtr-inline dt-checkboxes-select">
            <thead>
                <tr role="row">
                    <th>Tanggal Catat</th>
                    <th>Nama</th>
                    <th>Umur</th>
                    <th> Jenis Kelamin</th>
                    <th>AKB</th>
                    <th>BBL</th>
                    <th>Ibu</th>
                    <th>Ayah</th>
                    <th>Tanggal Lahir</th>
                    <th>Alamat</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($data as $key => $row) : ?>
                    <tr>
                        <td><p><?= $row['createdAt'] ?></p></td>
                        <td><p><?= empty($row['nama']) ? 'Belum ada nama' : $row['nama']  ?></p></td>
                        <td><p><?= empty($row['umur']) ? '' : ($row['umur'] . ' Hari') ?></p></td>
                        <td><p><?= empty($row['kelamin']) ? '' : ($row['kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan') ?></p></td>
                        <td><p><?= empty($row['akb']) ? '' : $row['akb'] ?></p></td>
                        <td><p><?= empty($row['bbl']) ? '' : $row['bbl'] ?></p></td>
                        <td><p><?= empty($row['ibu']) ? '' : $row['ibu'] ?></p></td>
                        <td><p><?= empty($row['ayah']) ? '' : $row['ayah'] ?></p></td>
                        <td><p><?= empty($row['tanggal_lahir']) ? '' : $row['tanggal_lahir'] . ($row['ttl_estimasi'] == 1 ? ' <span class ="badge badge-pill badge-sm bg-info">Estimasi</span>' : '') ?></p></td>
                        <td><p><?= empty($row['alamat']) ? '' : $row['alamat'] ?></p></td>
                    </tr>
                    <tr>
                        <th>Tanggal Periksa</th>
                        
                    </tr>
                <?php endforeach ?>
            </tbody>

        </table>
    </div>
</div>