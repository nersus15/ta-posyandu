<?php
    //$hidden_text = isset($hidden_text) ? $hidden_text : 'Tampilkan Opsi';
    $controls = isset($controls) ? $controls : array();
    $custom_search = isset($custom_search) ? $custom_search : NULL;
    //$show = !isset($nofilter) || !$nofilter || !isset($notablelength) || !$notablelength;
    $ppage1 = isset($pages) ? $pages : array(25, 50, 100);
    $buttons = array();
    foreach ($ppage1 as $p) {
        $buttons[] = array(
            'href' => '#',
            'title' => $p,
            'class' => $p == $perpage ? 'active' : '',
        );
    }

    $toolbar_items = array();
    if(!isset($toolbar_button)) $toolbar_button = array();
    else $toolbar_button[] = $toolbar_button;

    toolbar_items($toolbar_button, $toolbar_items);
?>
<!--<a class="btn pt-0 pl-0 d-inline-block d-md-none" data-toggle="collapse" href="#displayOptions" role="button" aria-expanded="true" aria-controls="displayOptions">
    <?php /*echo $hidden_text;*/?>
    <i class="simple-icon-arrow-down align-middle"></i>
</a>-->
<div class="setting-tabel-<?php echo $tabel;?>" id="displayOptions-<?php echo $tabel ?>">
    <div class="d-block d-md-inline-block">
        <?php foreach ($controls as $control): ?>
        <?php echo $control;?>
        <?php endforeach;?>
        <?php if (!isset($nofilter) || !$nofilter): ?>
	        <?php if ($custom_search):?>
	        <?php echo $custom_search;?>
	        <?php else:?>
	        <div class="table-search search-sm d-inline-block float-md-left mr-1 mb-1 align-top">
	            <input class="form-control" placeholder="Cari">
	        </div>
	        <?php endif;?>
    	<?php endif;?>
    </div>
    <?php if (!isset($notablelength) || !$notablelength): ?>
    <div class="float-md-right">
        <span class="text-muted text-small tabel-info" style="margin-right: 0.5rem;"></span>
        <button class="btn btn-outline-dark btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?php echo $perpage;?>
        </button>
        <div class="dropdown-menu length-menu dropdown-menu-right" x-placement="top-end" style="position: absolute; transform: translate3d(1010px, -125px, 0px); top: 0px; left: 0px; will-change: transform;">
            <?php foreach ($buttons as $b):
            if (isset($b['divider']) && $b['divider'])
                continue;
            $b['atribut']['data-nama'] = $tabel;
            ?>
            <a class="dropdown-item display-length <?php if (isset($b['class'])) echo $b['class'];?>" href="<?php echo isset($b['link']) ? $b['link'] : '#';?>" <?php if (isset($b['atribut'])) echo attribut_ke_str($b['atribut']);?>><?php echo $b['title'];?></a>
        <?php endforeach;?>
        </div>
    </div>
	<?php endif;?>
    <div class="panel-top-toolbar panel-toolbar-<?= $tabel ?> float-sm-right text-zero">
        <?php 
           if (isset($toolbar_button[0]) && count($toolbar_button[0]) > 0) {
            $toolbar_items = array();
            $tbmulti = array();
            toolbar_items($toolbar_button, $toolbar_items);
            //log_message('debug', 'TOOLBAR ITEMS: ' . print_r($toolbar_items, TRUE));
            foreach ($toolbar_items as $tb) {
                if (strpos($tb['class'], 'tool-refresh') !== FALSE)
                    continue;
    
                if (!isset($tb['atribut']))
                    $tb['atribut'] = array('data-nama' => $tabel);
                else
                    $tb['atribut']['data-nama'] = $tabel;
                if (strpos($tb['class'], 'tetap') !== FALSE || $tb['tipe'] == 'dropdown') {
                    if ($tb['tipe'] == 'dropdown') {
                        //$tb['class'] .= strpos($tb['class'], 'btn-') === FALSE ? ' btn-lg btn-primary' : '';
                        for ($i = 0; $i < count($tb['menu']); $i++) {
                            if (isset($tb['menu'][$i]['atribut']['class'])) {
                                $tb['menu'][$i]['class'] = $tb['menu'][$i]['atribut']['class'];
                                unset($tb['menu'][$i]['atribut']['class']);
                            }
                        }
                        include_view('components/button_group', $tb + array(
                            'tabel' => $tabel,
                            'class' => isset($toolbar_class) ? $toolbar_class : '',
                            'buttons' => $tb['menu']
                        ));
                    }else {
                        $tcls = strpos($tb['class'], 'btn-') === FALSE ? 'btn-lg btn-primary' : '';
                        echo ('<button type="button" class="btn ' .$tcls. ' mr-1 pl-4 pr-4 ' . $tb['class'] . '" ' . attribut_ke_str($tb['atribut']) . '>' . (isset($tb['icon']) ? '<i class="' . $tb['icon'] . '"></i>&nbsp;' : '') . '<span class="d-none d-md-inline-block">' . $tb['title']. '</span></button>');
                    }
                }else {
                    $tbmulti[] = $tb;
                }
            }
            if (count($tbmulti) > 0) {
                include_view('components/button_group', array(
                    'tabel' => $tabel,
                    'class' => isset($toolbar_class) ? $toolbar_class : '',
                    'buttons' => $tbmulti
                ));
            }
        }
        ?>
    </div>
   
</div>