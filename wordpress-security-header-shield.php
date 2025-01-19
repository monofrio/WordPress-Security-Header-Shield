<?php
/*
Plugin Name: Security Headers
Plugin URI: https://markonofrio.com/
Description: A drop-in plugin that adds security headers.
Author: Mark Onofrio
Version: 1.0.0
Author URI: https://markonofrio.com
*/

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( is_admin() )
{

    //include dependencies
    require_once plugin_dir_path(__FILE__ ) . 'admin/admin-menu.php';
    require_once plugin_dir_path(__FILE__ ) . 'admin/settings-page.php';
    require_once plugin_dir_path(__FILE__ ) . 'admin/settings-register.php';
    require_once plugin_dir_path(__FILE__ ) . 'admin/settings-callbacks.php';
    require_once plugin_dir_path(__FILE__ ) . 'admin/settings-validate.php';
//    require_once plugin_dir_path(__FILE__ ) . 'admin/site-list.php';


}

// default plugin options
function security_header_options_default(): array
{
    return array(
        'referrer-policy' => 'enable',
        'x-content-type-options' => 'enable',
        'x-xss-protection' => '1-mode',
        'content-security-policy-frame-src' => 'disable',

        'content-security-policy-other-sites' => ' '
    );
}



// Security Header Options
function security_header()
{
    $site_options = get_option('security_header_options');
        if ( ! is_admin() ) {

            if( $site_options['referrer-policy'] ?? 'enable') {

                $headers['Referrer-Policy']  =  'no-referrer-when-downgrade';
                $headers['X-Permitted-Cross-Domain-Policies'] = 'none';

            }

            if($site_options['x-content-type-options'] ?? 'enable') {

                $headers['X-Content-Type-Options'] = 'nosniff';

            }

            if( isset( $site_options['x-xss-protection'] ) ) {

                $xss_options = $site_options['x-xss-protection'];

                if($xss_options == '1-mode'){

                    $headers['X-XSS-Protection'] = "1; mode=block";

                } else {

                    $headers['X-XSS-Protection'] = $xss_options;

                }

            }

            // need to develop the other options
            if( false ){

                $headers['Permissions-Policy']  = 'autoplay self';

            }

            include 'admin/site-list.php';
            $defaultSiteList = sh_default_sites_list ();
            $activeSiteList = '';
            foreach($defaultSiteList as $site => $value ) {
                if(isset($site_options[$site])){
                    $activeSiteList .= $site_options[$site] . ' ';
                }
            }

            if($site_options['content-security-policy-frame-src'] ?? '0' == 'enable'){
                $default_site_options = "frame-src 'self' " .
                    $activeSiteList . " " .
                strlen( $site_options['content-security-policy-other-sites']) > 0 ? $site_options['content-security-policy-other-sites'] : "";
                $headers['Content-Security-Policy'] =  $default_site_options;
            }

            $headers['X-Frame-Options']  = 'SAMEORIGIN';
            $headers['Strict-Transport-Security'] = "max-age=31536000; includeSubDomains";

        }

        return $headers;
}
add_filter( 'wp_headers', 'security_header' );