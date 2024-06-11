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

    th {
        font-size: 14px !important;
    }

    td {
        font-size: 12px !important;
    }
    .middle{
    vertical-align: middle;
  }

  .center{
    text-align: center;
  }
</style>

<?php
$headers = array_keys($maps);
?>
<div class="main">
    <h1 class="card-title ml-4"><?= $title ?></h1>
    <div class="table-responsive container-fluid dt-bootstrap4">
        <table class="dataTable table tabeleditor table-nomargin table-condensed table-no-topborder table-bordered- table-striped- table-hover dataTable no-footer dtr-inline dt-checkboxes-select">
            <thead>
                <?php if (isset($headerHtml)) : ?>
                    <?= $headerHtml ?>
                <?php else : ?>
                    <tr role="row">
                        <th>Tanggal Dicatat</th>
                        <?php foreach ($headers as $h) : ?>
                            <td><?= $h ?></td>
                        <?php endforeach ?>
                    </tr>
                <?php endif ?>
            </thead>
            <tbody>
                <?php if (!empty($data)) : ?>
                    <?php foreach ($data as $key => $row) : ?>
                        <tr>
                            <td>
                                <p><?= $row['createdAt'] ?></p>
                            </td>
                            <?php foreach ($maps as $k => $map) : ?>
                                <td><?= is_callable($map) ? $map($row) : (empty($row[$map]) ? '-' : $row[$map]) ?></td>
                            <?php endforeach ?>
                        </tr>
                    <?php endforeach ?>
                <?php else : ?>
                    <tr>
                        <td style="text-align: center;" colspan="<?= count($headers) - 1 ?>">Tidak ada data</td>
                    </tr>
                <?php endif ?>
            </tbody>

        </table>
    </div>

    <?php foreach ($data as $row) : ?>
        <h2>Data Kunjungan <b><?= $row['nama'] ?></b></h2>
        <hr>
        <div class="table-responsive container-fluid dt-bootstrap4">
            <table class="dataTable table tabeleditor table-nomargin table-condensed table-no-topborder table-bordered- table-striped- table-hover dataTable no-footer dtr-inline dt-checkboxes-select">
                <thead>
                    <?php if (isset($headerHtmlKunjungan)) : ?>
                        <?= $headerHtmlKunjungan ?>
                    <?php else : ?>
                        <tr role="row">
                            <?php foreach (array_keys($maps_kunjungan) as $h) : ?>
                                <td><?= $h ?></td>
                            <?php endforeach ?>
                        </tr>
                    <?php endif ?>
                </thead>
                <tbody>
                    <?php if (!empty($row['kunjungan'])) : ?>
                        <?php foreach ($row['kunjungan'] as $key => $row2) : ?>
                            <tr>
                                <?php foreach ($maps_kunjungan as $k => $map) : ?>
                                    <td><?= is_callable($map) ? $map($row, $row2) : (empty($row2[$map]) ? '-' : $row2[$map]) ?></td>
                                <?php endforeach ?>
                            </tr>
                        <?php endforeach ?>

                    <?php else : ?>
                        <tr>
                            <td style="text-align: center;" colspan="<?= count(array_keys($maps_kunjungan)) - 1 ?>">Tidak ada data</td>
                        </tr>
                    <?php endif ?>
                </tbody>

            </table>
        </div>
    <?php endforeach ?>
</div>