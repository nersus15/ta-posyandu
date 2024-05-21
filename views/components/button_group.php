<?php
if (isset($class))
    $class .= strpos($class, 'btn-') === FALSE ? ' btn-lg btn-primary' : '';
?>
<div class="btn-group toolbar-<?php echo $tabel;?>" data-nama="<?php echo $tabel;?>">
    <?php if (isset($tipe) && $tipe == 'dropdown'):?>
    <button type="button" class="btn dropdown-toggle dropdown-toggle-split mr-1 pl-4 pr-4 <?php if (isset($class)) echo $class;?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <?php if (isset($icon)):?>
            <i class="<?php echo $icon;?>"></i>&nbsp;
        <?php endif;?>
        <span class="d-none d-md-inline-block"><?php if (isset($title)) echo $title;?></span>
    </button>

    <?php else: ?>
    <div class="btn btn-primary btn-lg pl-4 pr-0 check-button <?php if (isset($class)) echo $class;?>">
        <label class="custom-control custom-checkbox mb-0 d-inline-block">
            <input type="checkbox" class="ceksemua custom-control-input" id="checkAll">
            <span class="custom-control-label"></span>
        </label>
    </div>
    <button type="button" class="btn btn-lg btn-primary dropdown-toggle dropdown-toggle-split mr-1 pl-2 pr-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <?php endif;?>
    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(-86px, 42px, 0px); top: 0px; left: 0px; will-change: transform;">
        <?php foreach ($buttons as $b):
            if (isset($b['divider']) && $b['divider'])
                continue;
            $b['atribut']['data-nama'] = $tabel;
        ?>
        <a class="dropdown-item <?php if (isset($b['class'])) echo $b['class'];?>" href="<?php echo isset($b['link']) ? $b['link'] : '#';?>" <?php if (isset($b['atribut'])) echo attribut_ke_str($b['atribut']);?>><?php echo $b['title'];?></a>
        <?php endforeach;?>
        <?php if (count($buttons) == 0): ?>
        <div class="item-kosong" style="text-align: center;font-style: italic;display: none;">Tidak ada tombol</div>
        <?php endif;?>
    </div>
</div>