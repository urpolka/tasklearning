<?php
wp_enqueue_style( 'wp-theme-layout-style', get_stylesheet_uri() );
register_nav_menus([
    'primary' => 'Головне меню',
    'footer'  => 'Меню у підвалі',
]);
    
/?>