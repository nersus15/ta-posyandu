<table class="table tabeleditor table-nomargin table-condensed table-no-topborder table-bordered- table-striped- table-hover dataTable no-footer dtr-inline">
    <thead>
        <tr>
            <?php foreach ($header as $k => $v): ?>
                <td><?= $k ?></td>
            <?php endforeach ?>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($data)): ?>
            <tr>
                <td class="center" colspan="<?= count($header) ?>">Tidak ada data</td>
            </tr>
        <?php else: ?>
            <?php foreach ($data as $idx => $v): $v = (array) $v?>
                <tr>
                    <?php foreach ($header as $k => $v2): ?>
                        <td class="middle"><?= $v2 == '*increment' ? ++$idx : $v[$v2] ?></td>
                    <?php endforeach ?>
                </tr>
            <?php endforeach ?>
        <?php endif ?>
    </tbody>
</table>