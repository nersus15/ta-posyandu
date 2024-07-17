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
</style>

<div class="main">
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