<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {

    exit;

}


// add sub-level administrative menu
function security_header_add_sublevel_menu() {

    add_submenu_page(
        'options-general.php',
        'Security Header Settings',
        'Security Header',
        'manage_options',
        'r3SecurityHeader',
        'security_header_display_settings_page'
    );

}
add_action( 'admin_menu', 'security_header_add_sublevel_menu' );
