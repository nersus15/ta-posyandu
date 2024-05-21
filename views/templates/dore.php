<?php
$navbarConf = array_merge(['adaSidebar' => isset($sidebar)], (isset($navbarConf) ? $navbarConf : []));
if (isset($navbar) && !is_array($navbar))
    include_view($navbar, $navbarConf);
if (isset($sidebar) && !is_array($sidebar))
    include_view($sidebar, $sidebarConf);
if (!isset($data_content))
    $data_content = null;

?>
<main>
    <div class="container-fluid" style="<?php echo isset($ada_bg) && $ada_bg ? "background: url('" . $bg_url . "') no-repeat" : null ?>">
        <div class="col-12">
            <h1><?php echo isset($pageName) ? $pageName : null ?></h1>
            <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
                <ol class="breadcrumb pt-0">
                    <li class="breadcrumb-item">
                        <p><?php echo isset($subPageName) ? $subPageName : null ?></p>
                    </li>
                </ol>
            </nav>
            <div class="separator mb-5"></div>

        </div>
        <?php
        if (isset($contentHtml) && !empty($contentHtml)) {
            if(!is_array($contentHtml)) $contentHtml = [$contentHtml];
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
        <br>
    </div>
</main>