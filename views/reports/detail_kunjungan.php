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

    .middle {
        vertical-align: middle;
    }

    .center {
        text-align: center;
    }

  .separator{
    height: 3px;
    background-color: black;
    width: 90%;
    text-align: center;
    display: block;
    justify-self: flex-end;
    margin: 50px 5%;

  }
</style>

<div class="main">
    <table style=" width: 90%; margin: 50px 5% 0 5%">
        <tr>
            <td style="width: 10%;">
                <img style="width:100%; height: auto;" src="assets/img/logo/pemkab.jpg" alt="Logo Pemkab">
            </td>
            <td style="text-align: center;">
                <div class="" style="text-align: center;">
                    <h3>POSYANDU MELATI JANGKRUNG</h3>
                    <h4>Laporan Data Posyandu</h4>
                </div>
            </td>
            <td style="width: 10%; text-align: right;">
                <img style="width: 100%; height: auto;" src="assets/img/logo/posyandu.jpg" alt="Logo Posyandu">
            </td>
        </tr>
    </table>
    <span class="separator"></span>
    <h3 class="mb-4 center"><?= $pageName ?></h3>
    <?php
    if (isset($contentHtml) && !empty($contentHtml)) {
        if (!is_array($contentHtml)) $contentHtml = [$contentHtml];
        foreach ($contentHtml as $k => $c) {
            echo $c;
        }
    }
    ?>
    <br>

    <?php if (isset($content) && !empty($content)) {
        if (!is_array($content)) $content = [$content];
        foreach ($content as $k => $c) {
            include_view($c, $data_content);
        }
    }
    ?>
</div>