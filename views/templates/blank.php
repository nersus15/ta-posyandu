<?php
// $data_head = isset($data_head) ? $data_head : null;
$data_head = array(
    'resource' => $resource,
    'extra_js' => isset($extra_js) ? $extra_js : null,
    'extra_css' => isset($extra_css) ? $extra_css : null,
    'includeTiny' => isset($includeTiny) ? $includeTiny : null,
    'loadingAnim' => isset($loading_animation) ? $loading_animation : null,
    'bodyClass' => isset($bodyClass) ? $bodyClass : 'show-spinner',
    'hideSpinner' => isset($hideSpinner) ? $hideSpinner : false,
);
$header = isset($header) ? $header : 'head/main';
$footer = isset($footer) ? $footer : 'footer/main';
include_view($header, $data_head);
if (isset($navbar) && !is_array($navbar))
    include_view($navbar, $navbarConf);
if (isset($sidebar) && !is_array($sidebar))
    include_view($sidebar, $sidebarConf);
if (!isset($data_content))
    $data_content = null;


if (isset($contentHtml) && !empty($contentHtml)) {
    if(!is_array($contentHtml)) $contentHtml = [$contentHtml];
    foreach ($contentHtml as $k => $c) {
        echo $c;
    }
    echo "<br>";
}

if (isset($content) && !empty($content)) {
    if (!is_array($content)) $content = [$content];
    foreach ($content as $k => $c) {
        include_view($c, $data_content);
    }
}
$dataFoot = array(
    'resource' => $resource, 
    'adaThemeSelector' => isset($adaThemeSelector) ? $adaThemeSelector : null,
    'extra_js' => isset($extra_js) ? $extra_js : null, 
    'extra_css' => isset($extra_css) ? $extra_css : null
);
include_view($footer, array('resource' => $resource, 'extra_js' => isset($extra_js) ? $extra_js : null, 'extra_css' => isset($extra_css) ? $extra_css : null));