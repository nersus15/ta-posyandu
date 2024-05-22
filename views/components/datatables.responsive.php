<?php
    $attributTabel = '';
    if(isset($options) && !empty($options) && is_array($options)){
        foreach($options as $k => $v){
            $attributTabel .= 'data-' . $k . " = '" . $v . "'";
        }
    }
    if(!isset($skrip_data)) $skrip_data = [];
    if(!isset($skrip) || empty($skrip) || !file_exists(ROOT . "/assets/js/" . $skrip. ".js"))
        echo "<script> alert('Skrip datatable " . $dtid. " tidak ditemukan');</script>";
    else
        echo "<script>" . load_script($skrip, $skrip_data, true) . "</script>";

    if(!isset($perpage) && isset($pages) && !empty($pages)) $perpage = 10;

    if(!isset($form)) 
        $form = array('formid'=>'form-'. $dtid, 'posturl' => '', 'path' => '', 'skrip' => '', 'formGenerate' => '');
    else
        $form = array_merge(array('formid'=>'form-'. $dtid, 'posturl' => '', 'path' => '', 'skrip' => '', 'formGenerate' => '', 'nama' => '', 'skripVar' => (object)[]), $form);
?>
<style>
    tr.selected{
        background-color: white !important;
    }
    .sticky-toolbar{
        position: fixed;
        right: 0;z-index: 99;
        top: 15%;
        background: white;
        border: 1px solid whitesmoke;
        border-radius: 25px;
        padding: 10px 10px;
    }
</style>
<div class="row mb-4 mt-3">
    <div class="col-12 mb-4">
        <?php 
            if(isset($data_panel) && !empty($data_panel)){
                extract($data_panel);
                include_view('components/toolbar.panel', array(
                    'tabel' => $nama,
                    'perpage' => $perpage,
                    'pages' => $pages,
                    'controls' => isset($controls) ? $controls : null,
                    'custom_search' => isset($custom_search) ? $custom_search : null,
                    'nofilter' => isset($hilangkan_filter) && $hilangkan_filter,
                    'notablelength' => isset($hilangkan_display_length) && $hilangkan_display_length,
                    'toolbar_button' => $toolbar
                ));
            }
        ?>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-body card-no-border">
                <h1 class="card-title ml-4"><?php echo $dtTitle ?></h1>
                <?php if(isset($dtAlert)): ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <strong><?php echo $alert ?>: </strong> <span id="saldo-sebelum"><?php echo $dtAlert?></span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php endif ?>
                <div class="table-responsive container-fluid dt-bootstrap4">
                    <table class="dataTable table tabeleditor table-nomargin table-condensed table-no-topborder table-bordered- table-striped- table-hover dataTable no-footer dtr-inline" id="<?php echo $dtid ?>" data-export-title="<?php echo isset($exportTitle) ? $exportTitle : null ?>" <?= $attributTabel ?>>
                        <thead>
                            <tr>
                                <?php foreach($head as $h):?>
                                <th><?php echo $h ?></th>
                                <?php endforeach ?>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script id="toolbar-default-skrip">
    <?php 
        if(!isset($modal)){
            $modal = [];
        }
        $defData = array(
            'form' => $form,
            'dtid' => $dtid,
        );
        $data = array_merge($defData, $modal);
        load_script('utils/dt_default_skrip.js', $data)
    ?>
</script>
<script id="toolbar-user-skrip">
<?php
    if(isset($toolbarSkrip) && !empty($toolbarSkrip)){
        load_script($toolbarSkrip, $form + array('dtid' => $dtid));
    }
?>
</script>