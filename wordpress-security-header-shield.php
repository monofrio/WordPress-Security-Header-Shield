<?php
/*
Plugin Name: Security Header Shield
Plugin URI: https://markonofrio.com/
Description: A drop-in plugin that adds HTTP security headers to your site.
Author: Mark Onofrio
Version: 1.2.0
Author URI: https://markonofrio.com
Requires at least: 5.0
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) { exit; }

// shared by the front end and the settings screen
require_once plugin_dir_path( __FILE__ ) . 'includes/options.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/site-list.php';

if ( is_admin() ) {

    // include dependencies
    require_once plugin_dir_path( __FILE__ ) . 'admin/admin-menu.php';
    require_once plugin_dir_path( __FILE__ ) . 'admin/settings-page.php';
    require_once plugin_dir_path( __FILE__ ) . 'admin/settings-register.php';
    require_once plugin_dir_path( __FILE__ ) . 'admin/settings-callbacks.php';
    require_once plugin_dir_path( __FILE__ ) . 'admin/settings-validate.php';

}


// -- Building the headers

// the Content-Security-Policy value, or '' when no directive is switched on
function security_header_csp_value( array $options ): string
{
    $report_only = 'report-only' === $options['csp-mode'];
    $directives  = array();

    if ( ! empty( $options['csp-frame-ancestors'] ) ) {
        $directives[] = "frame-ancestors 'self'";
    }

    if ( 'enable' === $options['content-security-policy-frame-src'] ) {

        $sources = array( "'self'" );

        foreach ( security_header_default_sites_list() as $id => $site ) {
            if ( ! empty( $options[ $id ] ) ) {
                $sources = array_merge( $sources, preg_split( '/\s+/', $site[1], -1, PREG_SPLIT_NO_EMPTY ) );
            }
        }

        $sources = array_merge( $sources, security_header_parse_sources( $options['content-security-policy-other-sites'] ) );

        $directives[] = 'frame-src ' . implode( ' ', array_unique( $sources ) );

    }

    if ( ! empty( $options['csp-object-src'] ) ) {
        $directives[] = "object-src 'none'";
    }

    if ( ! empty( $options['csp-base-uri'] ) ) {
        $directives[] = "base-uri 'self'";
    }

    if ( ! empty( $options['csp-form-action'] ) ) {
        $directives[] = "form-action 'self'";
    }

    // browsers ignore (and warn about) this directive in a report-only policy
    if ( ! empty( $options['csp-upgrade-insecure-requests'] ) && ! $report_only ) {
        $directives[] = 'upgrade-insecure-requests';
    }

    return implode( '; ', $directives );
}

// the Permissions-Policy value, or '' when every feature is left on
function security_header_permissions_policy_value( array $options ): string
{
    $members = array();

    foreach ( security_header_permissions_features() as $feature => $name ) {
        if ( ! empty( $options[ 'pp-' . $feature ] ) ) {
            $members[] = $feature . '=()';
        }
    }

    return implode( ', ', $members );
}

/**
 * Combine Permissions-Policy values into one. When two values set the same feature,
 * the later one wins, so pass ours last.
 */
function security_header_merge_permissions_policy( array $values ): string
{
    $members = array();

    foreach ( $values as $value ) {
        // members are separated by commas; split only where a new "feature=" starts
        foreach ( preg_split( '/,\s*(?=[a-z][a-z0-9\-]*=)/i', trim( (string) $value ) ) as $member ) {

            $member = trim( $member );
            if ( '' === $member ) {
                continue;
            }

            $name = strtolower( trim( strstr( $member, '=', true ) ?: $member ) );
            unset( $members[ $name ] );
            $members[ $name ] = $member;

        }
    }

    return implode( ', ', $members );
}

// build the security headers from the saved options
function security_header_build_headers(): array
{
    $options = security_header_get_options();
    $headers = array();

    if ( 'enable' === $options['referrer-policy'] ) {
        $headers['Referrer-Policy'] = 'strict-origin-when-cross-origin';
    }

    if ( 'enable' === $options['x-content-type-options'] ) {
        $headers['X-Content-Type-Options'] = 'nosniff';
    }

    $xss = (string) $options['x-xss-protection'];
    if ( '1-mode' === $xss ) {
        $headers['X-XSS-Protection'] = '1; mode=block';
    } elseif ( '0' === $xss || '1' === $xss ) {
        $headers['X-XSS-Protection'] = $xss;
    }

    $csp = security_header_csp_value( $options );
    if ( '' !== $csp ) {
        $csp_header             = 'report-only' === $options['csp-mode'] ? 'Content-Security-Policy-Report-Only' : 'Content-Security-Policy';
        $headers[ $csp_header ] = $csp;
    }

    $headers['X-Frame-Options']                   = 'SAMEORIGIN';
    $headers['X-Permitted-Cross-Domain-Policies'] = 'none';

    if ( ! empty( $options['cross-origin-opener-policy'] ) ) {
        $headers['Cross-Origin-Opener-Policy'] = 'same-origin';
    }

    $permissions = security_header_permissions_policy_value( $options );
    if ( '' !== $permissions ) {
        $headers['Permissions-Policy'] = $permissions;
    }

    // browsers ignore HSTS over plain HTTP, so only send it on HTTPS requests
    if ( is_ssl() ) {
        if ( 'enable' === $options['strict-transport-security'] ) {
            $headers['Strict-Transport-Security'] = 'max-age=31536000';
        } elseif ( 'enable-subdomains' === $options['strict-transport-security'] ) {
            $headers['Strict-Transport-Security'] = 'max-age=31536000; includeSubDomains';
        }
    }

    return $headers;
}


// -- Sending the headers

// front-end pages: add our headers to the ones WordPress already set
function security_header_filter_wp_headers( $headers )
{
    if ( ! is_array( $headers ) ) {
        $headers = array();
    }

    // there is no pingback endpoint to advertise when XML-RPC is off
    if ( ! empty( security_header_get_options()['disable-xmlrpc'] ) ) {
        unset( $headers['X-Pingback'] );
    }

    return array_merge( $headers, security_header_build_headers() );
}
add_filter( 'wp_headers', 'security_header_filter_wp_headers' );

/**
 * Some hosts and plugins send their own Permissions-Policy later in the request,
 * which would replace ours. Just before the template loads, fold every
 * Permissions-Policy sent so far into a single header that keeps both.
 */
function security_header_merge_late_permissions_policy()
{
    if ( headers_sent() ) {
        return;
    }

    $ours = security_header_permissions_policy_value( security_header_get_options() );
    if ( '' === $ours ) {
        return;
    }

    $values = array();
    foreach ( headers_list() as $line ) {
        if ( 0 === stripos( $line, 'Permissions-Policy:' ) ) {
            $values[] = substr( $line, strlen( 'Permissions-Policy:' ) );
        }
    }
    $values[] = $ours;

    header( 'Permissions-Policy: ' . security_header_merge_permissions_policy( $values ) );
}
add_action( 'template_redirect', 'security_header_merge_late_permissions_policy', PHP_INT_MAX );

// wp-login.php does not run the wp_headers filter, so send the headers directly
function security_header_send_login_headers()
{
    if ( headers_sent() ) {
        return;
    }

    foreach ( security_header_build_headers() as $name => $value ) {
        header( $name . ': ' . $value );
    }
}
add_action( 'login_init', 'security_header_send_login_headers' );

// REST API responses
function security_header_rest_headers( $result )
{
    if ( $result instanceof WP_HTTP_Response ) {
        foreach ( security_header_build_headers() as $name => $value ) {
            $result->header( $name, $value );
        }
    }

    return $result;
}


// -- Hardening

// swap ?ver=<WordPress version> for a code that still changes on updates but reveals nothing
function security_header_mask_version( $src )
{
    if ( is_admin() || ! is_string( $src ) || false === strpos( $src, 'ver=' ) ) {
        return $src;
    }

    $version = get_bloginfo( 'version' );
    $query   = wp_parse_url( $src, PHP_URL_QUERY );

    if ( ! is_string( $query ) ) {
        return $src;
    }

    parse_str( $query, $args );

    if ( isset( $args['ver'] ) && $args['ver'] === $version ) {
        $src = add_query_arg( 'ver', substr( wp_hash( 'security-header-' . $version ), 0, 10 ), $src );
    }

    return $src;
}

// requests to xmlrpc.php stop here when XML-RPC is disabled
function security_header_block_xmlrpc_request()
{
    status_header( 403 );
    header( 'Content-Type: text/plain; charset=utf-8' );
    echo 'XML-RPC is disabled on this site.';
    exit;
}

// turn on the hardening options that are switched on in the settings
function security_header_setup_hardening()
{
    $options = security_header_get_options();

    if ( ! empty( $options['hide-wp-version'] ) ) {
        add_filter( 'the_generator', '__return_empty_string' );           // <meta name="generator"> and feeds
        add_filter( 'style_loader_src', 'security_header_mask_version', 20 );
        add_filter( 'script_loader_src', 'security_header_mask_version', 20 ); // includes the emoji script
    }

    if ( ! empty( $options['disable-xmlrpc'] ) ) {

        if ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) {
            security_header_block_xmlrpc_request();
        }

        add_filter( 'xmlrpc_enabled', '__return_false' );
        add_filter( 'xmlrpc_methods', '__return_empty_array' );
        remove_action( 'wp_head', 'rsd_link' );

    }

    if ( ! empty( $options['rest-api-headers'] ) ) {
        add_filter( 'rest_post_dispatch', 'security_header_rest_headers', 99 );
    }
}
add_action( 'plugins_loaded', 'security_header_setup_hardening' );
