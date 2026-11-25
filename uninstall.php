<?php

// only run when WordPress is uninstalling this plugin
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

delete_option( 'security_header_options' );
