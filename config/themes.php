<?php
// Resource group "main"
$config['themes'] = array(
    'main' => array(
        'js' => array(
            array('pos' => 'head', 'src' => 'vendor/jquery/jquery.min.js'),
            array('pos' => 'head', 'src' => 'vendor/bootstrap/js/popper.min.js'),
            array('pos' => 'head', 'src' => 'vendor/bootstrap/js/bootstrap.min.js'),
            array('pos' => 'head', 'src' => 'vendor/bootstrap/js/bootstrap.bundle.min.js'),
            array('pos' => 'head', 'src' => 'vendor/bootstrap-notify/bootstrap-notify.min.js'),
            array('pos' => 'head', 'src' => 'vendor/moment/moment.min.js'),
            array('pos' => 'head', 'src' => 'vendor/kamscore/js/Kamscore.min.js'),
            array('pos' => 'head', 'src' => 'vendor/kamscore/js/uihelper.js'),
            array('pos' => 'head', 'src' => 'js/utils/navbar.action.js')
        ),
        'css' => array(
            array('pos' => 'head', 'src' => 'vendor/bootstrap/css/bootstrap.min.css'),
            array('pos' => 'head', 'src' => 'vendor/kamscore/css/main.css'),
            array('pos' => 'head', 'src' => 'vendor/fontawesome/css/all.min.css'),
            array('pos' => 'head', 'src' => 'vendor/icon/iconsmind/style.css' ),
            array('pos' => 'head', 'src' => 'vendor/icon/simple-line-icons/css/simple-line-icons.css')
        )
    ), 

    'softui' => array(
        'js' => array(
            array('pos' => 'head', 'src' => 'vendor/fontawesome/js/fontawesome.js'),
            array('pos' => 'head', 'src' => 'themes/softUi/js/plugins/perfect-scrollbar.min.js'),
            array('pos' => 'head', 'src' => 'themes/softUi/js/plugins/smooth-scrollbar.min.js'),
            array('pos' => 'body:end', 'src' => 'themes/softUi/js/soft-ui-dashboard.min.js?v=1.0.3'),
            array('pos' => 'body:end', 'src' => 'themes/softUi/js/script.js'),
        ),
        'css' => array(
            array('pos' => 'head', 'src' => 'themes/softUi/css/nucleo-icons.css'),
            array('pos' => 'head', 'src' => 'themes/softUi/css/nucleo-svg.css'),
            array('pos' => 'head', 'src' => 'themes/softUi/css/soft-ui-dashboard.css?v=1.0.3'),
        )
    ),

    'onix' => array(
        'js' => array(
            array('pos' => 'head', 'src' => 'themes/onix/js/owl-carousel.js'),
            array('pos' => 'head', 'src' => 'themes/onix/js/animation.js'),
            array('pos' => 'head', 'src' => 'themes/onix/js/owl-carousel.js'),
            array('pos' => 'body:end', 'src' => 'themes/onix/js/custom.js'),
        ),
        'css' => array(
            
            array('pos' => 'head', 'src' => 'themes/onix/css/fontawesome.css'),
            array('pos' => 'head', 'src' => 'themes/onix/css/templatemo-onix-digital.css'),
            array('pos' => 'head', 'src' => 'themes/onix/css/animated.css'),
            array('pos' => 'head', 'src' => 'themes/onix/css/owl.css'),
        ),
    ),
    'dore' => array(
        'css' => array(
            array('pos' => 'head', 'src' => 'themes/dore/css/dore.light.green.css'),
            array('pos' => 'head', 'src' => 'themes/dore/css/main.css')
        ),
        'js' => array(
            array('pos' => 'head', 'src' => 'themes/dore/js/script.js'),
            array('pos' => 'head', 'src' => 'themes/dore/js/dore.script.js'),
        )
    ),
    'mievent' => array(
        'css' => array(
            array('src' => "themes/mievent/css/bootstrap.css", 'pos' => 'head'),
            // array('src' => "themes/mievent/css/font-awesome.min.css", 'pos' => 'head'),
            array('pos' => 'head', 'src' => 'vendor/fontawesome/css/all.min.css'),
            array('src' => "themes/mievent/css/flexslider.css", 'pos' => 'head'),
            array('src' => "themes/mievent/css/superslides.css", 'pos' => 'head'),
            array('src' => "themes/mievent/css/animate.css", 'pos' => 'head'),
            array('src' => "themes/mievent/css/schedule.css", 'pos' => 'head'),
            array('src' => "themes/mievent/css/gridgallery.css", 'pos' => 'head'),
            array('src' => "themes/mievent/css/venobox.css", 'pos' => 'head'),
            array('src' => "themes/mievent/css/styles.css", 'pos' => 'head'),
            array('src' => "themes/mievent/css/queries.css", 'pos' => 'head'),
        ),
        'js' => array(
            // array('pos' => 'head', 'src' => "themes/jquery/jquery-3.3.1.min.js'),
            array('pos' => 'head', 'src' => "themes/mievent/js/jquery-1.11.0.min.js"),
            array('pos' => 'head', 'src' => "$=jQuery", 'type' => 'inline'),
            array('pos' => 'body:end', 'src' => "themes/mievent/js/jquery-ui-1.10.4.min.js"),
        
            // VIMEO VIDEO//     
            array('pos' => 'body:end', 'src' => "themes/mievent/js/venobox.js"),
        
            // 3D GALLERY//
            array('pos' => 'body:end', 'src' => "themes/mievent/js/classie.grid.gallery.js"),
            array('pos' => 'body:end', 'src' => "themes/mievent/js/modernizr.gridgallery.js"),
            array('pos' => 'body:end', 'src' => "themes/mievent/js/cbpGridGallery.js"),
        
            array('pos' => 'body:end', 'src' => "themes/mievent/js/classie.js"),
            array('pos' => 'body:end', 'src' => "themes/mievent/js/modalEffects.js"),
        
            // array('pos' => 'body:end', 'src' => "themes/mievent/js/nlform.js"),
            // array('pos' => 'body:end', 'src' =>"nlform = new NLForm( document.getElementById( 'nl-form' ) )", 'type' => 'inline'),
            array('pos' => 'body:end', 'src' => "themes/mievent/js/bootstrap.min.js"),
        
            // TEAM SLIDER  //
            array('pos' => 'body:end', 'src' => "themes/mievent/js/jquery.flexslider.js"),
        
            // SCHEDULE TABS  //
            array('pos' => 'body:end', 'src' => "themes/mievent/js/modernizr.js"),
            array('pos' => 'body:end', 'src' => "themes/mievent/js/cbpFWTabs.js"),
        
            // SPONSOR SLIDER//
            // array('pos' => 'body:end', 'src' => "themes/mievent/js/jssor.core.js"),
            // array('pos' => 'body:end', 'src' => "themes/mievent/js/jssor.utils.js"),
            // array('pos' => 'body:end', 'src' => "themes/mievent/js/jssor.slider.js"),
            // array('pos' => 'body:end', 'src' => "themes/mievent/js/sponsor_init.js"),
        
            // SMOOTH SCROLL  //
            array('pos' => 'body:end', 'src' => "themes/mievent/js/smooth-scroll.js"),
        
            // NICE SCROLL  //
            array('pos' => 'body:end', 'src' => "themes/mievent/js/jquery.nicescroll.js"),
        
            // SUBSCRIPTION FORM  //
            array('pos' => 'body:end', 'src' => "themes/mievent/js/notifyMe.js"),
        
            array('pos' => 'body:end', 'src' => "themes/mievent/js/jquery.placeholder.js"),
        
            // ANIMATION  //
            array('pos' => 'body:end', 'src' => "themes/mievent/js/wow.min.js"),
        
            // LANDINGPAGE SLIDER  //
            array('pos' => 'body:end', 'src' => "themes/mievent/js/hammer.min.js"),
            array('pos' => 'body:end', 'src' => "themes/mievent/js/jquery.mobile-1.4.3.js"),
            array('pos' => 'body:end', 'src' => "themes/mievent/js/jquery.superslides.js"),
        
            // INITIALIZATION  //
            array('pos' => 'body:end', 'src' => "themes/mievent/js/init.js"),
        )
    ),
    'funden' => array(
        'css' => array(
            array('pos' => 'head', 'src' => "themes/funden/css/animate.min.css"),
            array('pos' => 'head', 'src' => "themes/funden/css/bootstrap.min.css"),
            array('pos' => 'head', 'src' => "themes/funden/css/font-awesome.min.css"),
            array('pos' => 'head', 'src' => "themes/funden/css/flaticon.css"),
            array('pos' => 'head', 'src' => "themes/funden/css/slick.min.css"),
            array('pos' => 'head', 'src' => "themes/funden/css/lity.min.css"),
            array('pos' => 'head', 'src' => "themes/funden/css/main.css"),
            array('pos' => 'head', 'src' => "themes/funden/css/responsive.css"),
        ),
        'js' => array(
           array('pos' => 'head', 'src' => "themes/funden/js/jquery.min.js"),
           array('pos' => 'head', 'src' => "themes/funden/js/bootstrap.min.js"),    
           array('pos' => 'body:end', 'src' => "themes/funden/js/jquery.inview.min.js"),    
           array('pos' => 'body:end', 'src' => "themes/funden/js/slick.min.js"),    
           array('pos' => 'body:end', 'src' => "themes/funden/js/lity.min.js"),    
           array('pos' => 'body:end', 'src' => "themes/funden/js/wow.min.js"),    
           array('pos' => 'body:end', 'src' => "themes/funden/js/main.js"),       
        )
    ),
    'form' => array(
        'js' => array(
            array('pos' => 'head', 'src' => 'vendor/jquery/jquery.form.js'),
            array('pos' => 'head', 'src' => 'vendor/jquery-validation/dist/jquery.validate.min.js'),
            array('pos' => 'head', 'src' => 'vendor/jquery-validation/lang/id.js'),
            array('pos' => 'head', 'src' => 'vendor/select2/dist/js/select2.min.js'),
            array('pos' => 'head', 'src' => 'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js', 'type' => 'file'),
            array('pos' => 'head', 'src' => 'vendor/datepicker/js/bootstrap-datepicker.js'),
            array('pos' => 'head', 'src' => 'vendor/timepicker/jquery.timepicker.js'),
            // array('pos' => 'head', 'src' => 'js/utils/main.init.js'),
        ),
        'css' => array(
            array('pos' => 'head', 'src' => 'vendor/select2/dist/css/select2.css'),
            array('pos' => 'head', 'src' => 'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css', 'type' => 'file'),
            array('pos' => 'head', 'src' => 'vendor/datepicker/css/datepicker.css'),
            array('pos' => 'head', 'src' => 'vendor/timepicker/jquery.timepicker.css'),
        )
    ),

    'datatables' => array(
        'css' => array(
            array('src' => 'vendor/datatables/dataTables.bootstrap4.min.css', 'pos' => 'head'),
            array('src' => 'vendor/datatables/datatables.responsive.bootstrap4.min.css', 'pos' => 'head'),
            // array('src' => 'vendor/datatables/jquery.dataTables.min.css', 'pos' => 'head'),
            array('src' => 'vendor/datatables/select.dataTables.css', 'pos' => 'head'),
            array('pos' => 'head', 'src' => 'vendor/dt-checkbox/css/dataTables.checkboxes.css'),

        ),
        'js' => array(
            array('pos' => 'head', 'src' => 'vendor/datatables/datatables.min.js'),
            array('pos' => 'head', 'src' => 'vendor/datatables/buttons.datatables.js'),
            array('pos' => 'head', 'src' => 'vendor/datatables/dt.select.js'),
            array('pos' => 'head', 'src' => 'vendor/datatables/btn.zip.js'),
            array('pos' => 'head', 'src' => 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js', 'type' => 'file'),
            array('pos' => 'head', 'src' => 'vendor/datatables/btn.pfs.js'),
            array('pos' => 'head', 'src' => 'vendor/datatables/btn.html-buttons.js'),
            array('pos' => 'head', 'src' => 'vendor/datatables/btn.print.js'),
            array('pos' => 'head', 'src' => 'vendor/dt-checkbox/js/dataTables.checkboxes.min.js'),
            array('pos' => 'head', 'src' => 'https://cdn.datatables.net/fixedcolumns/4.0.0/js/dataTables.fixedColumns.min.js', 'type' => 'file'),
            // array('pos' => 'head', 'src' => 'vendor/datatables/jquery.dataTables.min.js'),
            // array('pos' => 'head', 'src' => 'vendor/datatables/dataTables.select.min.js'),
        )
    ), 
    'icon' => array(
        'js' => [],
        'css' => [
            array('pos' => 'head', 'src' => 'vendor/fontawesome/css/all.min.css'),
            array('pos' => 'head', 'src' => 'vendor/icon/iconsmind/style.css' ),
            array('pos' => 'head', 'src' => 'vendor/icon/simple-line-icons/css/simple-line-icons.css')
        ]
    ),

    'heremap' => array(
        'js' => [
            array('pos' => 'head', 'src' => 'https://js.api.here.com/v3/3.1/mapsjs-core.js', 'type' => 'file'),
            array('pos' => 'head', 'src' => 'https://js.api.here.com/v3/3.1/mapsjs-service.js', 'type' => 'file'),
            array('pos' => 'head', 'src' => 'https://js.api.here.com/v3/3.1/mapsjs-ui.js', 'type' => 'file'),
            array('pos' => 'head', 'src' => 'https://js.api.here.com/v3/3.1/mapsjs-mapevents.js', 'type' => 'file'),
        ],
        'css' => [
            array('pos' => 'head', 'src' => 'https://js.api.here.com/v3/3.1/mapsjs-ui.css', 'type' => 'file')
        ]
    ),
    'login' => array(
        'js' => array(
            array('type' => 'file', 'pos' => 'head', 'src' => 'themes/login/vendor/animsition/js/animsition.min.js'),
            array('type' => 'file', 'pos' => 'head', 'src' => 'themes/login/js/main.js')

        ),
        'css' => array(
            array('type' => 'file','src' => 'themes/login/fonts/iconic/css/material-design-iconic-font.min.css', 'pos' => 'head'),
            array('type' => 'file','src' => 'themes/login/vendor/animate/animate.css', 'pos' => 'head'),
            array('type' => 'file','src'=> "themes/login/vendor/css-hamburgers/hamburgers.min.css", 'pos' => 'head'),
            array('type' => 'file','src'=> "themes/login/vendor/animsition/css/animsition.min.css", 'pos' => 'head'),
            array('type' => 'file','src'=> "themes/login/vendor/select2/select2.min.css", 'pos' => 'head'),
            array('type' => 'file','src'=> "themes/login/vendor/daterangepicker/daterangepicker.css", 'pos' => 'head'),
            array('type' => 'file','src'=> "themes/login/css/util.css", 'pos' => 'head'),
            array('type' => 'file','src'=> "themes/login/css/main.css", 'pos' => 'head'),
        ),
    )
);