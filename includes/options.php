<?php
/**
 * Option definitions shared by the front end and the settings screen.
 */

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {

    exit;

}

// Browser features the Permissions-Policy header can switch off. ID => label
function security_header_permissions_features(): array
{
    return array(
        'camera'          => 'Camera',
        'microphone'      => 'Microphone',
        'geolocation'     => 'Location',
        'display-capture' => 'Screen capture',
        'payment'         => 'Payment Request API',
        'usb'             => 'USB devices',
        'accelerometer'   => 'Accelerometer',
        'gyroscope'       => 'Gyroscope',
        'magnetometer'    => 'Magnetometer',
    );
}

/**
 * Every on/off checkbox (except the frame-src site list), with its settings section,
 * the text shown on the settings page and its default.
 */
function security_header_toggle_fields(): array
{
    $fields = array(

        // Content-Security-Policy directives
        'csp-frame-ancestors' => array(
            'section' => 'csp',
            'title'   => "frame-ancestors 'self'",
            'label'   => 'Only your own site may show your pages in a frame. The modern replacement for X-Frame-Options.',
            'default' => 1,
        ),
        'csp-object-src' => array(
            'section' => 'csp',
            'title'   => "object-src 'none'",
            'label'   => 'Block old plugin content such as Flash and Java applets.',
            'default' => 1,
        ),
        'csp-base-uri' => array(
            'section' => 'csp',
            'title'   => "base-uri 'self'",
            'label'   => 'Stop injected <base> tags from changing where relative links and scripts point.',
            'default' => 1,
        ),
        'csp-form-action' => array(
            'section' => 'csp',
            'title'   => "form-action 'self'",
            'label'   => 'Forms may only submit to this site. Leave off if a form posts to another service (Mailchimp, PayPal, etc.).',
            'default' => 0,
        ),
        'csp-upgrade-insecure-requests' => array(
            'section' => 'csp',
            'title'   => 'upgrade-insecure-requests',
            'label'   => 'Load any leftover http:// images, scripts and styles over https://. Not applied in Report-Only mode.',
            'default' => 1,
        ),

        // Cross-Origin-Opener-Policy
        'cross-origin-opener-policy' => array(
            'section' => 'coop',
            'title'   => 'Cross-Origin-Opener-Policy',
            'label'   => 'Send same-origin: other sites cannot reach into your pages through popups or tabs they open. Can break "sign in with" or payment popups that need to talk back to your page.',
            'default' => 1,
        ),

        // Hardening
        'hide-wp-version' => array(
            'section' => 'hardening',
            'title'   => 'Hide WordPress version',
            'label'   => 'Remove the generator tag and replace ?ver= on core files with a site-specific code. readme.html is a static file, so delete or block it separately.',
            'default' => 1,
        ),
        'disable-xmlrpc' => array(
            'section' => 'hardening',
            'title'   => 'Disable XML-RPC',
            'label'   => 'Block xmlrpc.php, a common target for password guessing. Leave off if you use Jetpack or the WordPress mobile app.',
            'default' => 0,
        ),
        'rest-api-headers' => array(
            'section' => 'hardening',
            'title'   => 'Headers on REST API',
            'label'   => 'Also send these security headers on /wp-json/ responses.',
            'default' => 1,
        ),

    );

    foreach ( security_header_permissions_features() as $feature => $name ) {
        $fields[ 'pp-' . $feature ] = array(
            'section' => 'permissions',
            'title'   => $name,
            'label'   => 'Turn off ' . $feature,
            'default' => 1,
        );
    }

    return $fields;
}

// default plugin options
function security_header_options_default(): array
{
    $defaults = array(
        'referrer-policy'                     => 'enable',
        'x-content-type-options'              => 'enable',
        'x-xss-protection'                    => '0',
        'strict-transport-security'           => 'disable',
        'csp-mode'                            => 'enforce',
        'content-security-policy-frame-src'   => 'disable',
        'content-security-policy-other-sites' => '',
    );

    foreach ( security_header_toggle_fields() as $id => $field ) {
        $defaults[ $id ] = $field['default'];
    }

    return $defaults;
}

// saved options merged over the defaults, so every key is always present
function security_header_get_options(): array
{
    $saved = get_option( 'security_header_options', array() );

    if ( ! is_array( $saved ) ) {
        $saved = array();
    }

    // older versions stored null for some fields; treat that as "use the default"
    $saved = array_filter( $saved, function ( $value ) { return null !== $value; } );

    return array_merge( security_header_options_default(), $saved );
}

/**
 * Turn free-form "Other Sites" text into a list of valid CSP source expressions.
 *
 * Accepts entries separated by spaces, commas or new lines. Anything that is not a
 * scheme source (https:) or host source (https://*.example.com:443/path) is dropped,
 * which keeps semicolons, quotes and new lines out of the header. A bare "*" is
 * rejected on purpose: it would allow every site and defeat the policy.
 */
function security_header_parse_sources( $raw ): array
{
    if ( ! is_string( $raw ) || '' === trim( $raw ) ) {
        return array();
    }

    $pattern = '~^(?:'
        . '[a-z][a-z0-9+.\-]*:'                                        // scheme source, e.g. https:
        . '|'
        . '(?:[a-z][a-z0-9+.\-]*://)?'                                 // optional scheme
        . '(?:localhost|(?:\*\.)?[a-z0-9\-]+(?:\.[a-z0-9\-]+)+)'        // host with a dot, optionally *.host
        . '(?::(?:[0-9]+|\*))?'                                        // optional port
        . '(?:/[^\s;,\'"]*)?'                                          // optional path
        . ')$~i';

    $sources = array();

    foreach ( preg_split( '/[\s,]+/', $raw, -1, PREG_SPLIT_NO_EMPTY ) as $token ) {
        if ( preg_match( $pattern, $token ) ) {
            $sources[] = $token;
        }
    }

    return array_values( array_unique( $sources ) );
}
