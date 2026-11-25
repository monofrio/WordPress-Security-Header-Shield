<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {

    exit;

}


// radio field options - Referrer-Policy
function security_header_referrer_policy_options(): array
{
    return array(
        'enable'  => 'Enable Referrer-Policy (<strong>strict-origin-when-cross-origin</strong>)',
        'disable' => 'Disable Referrer-Policy',
    );
}

// radio field options - X-Content-Type-Options
function security_header_x_content_type_options(): array
{
    return array(
        'enable'  => 'Enable X-Content-Type-Options (<strong>nosniff</strong>)',
        'disable' => 'Disable X-Content-Type-Options',
    );
}

// radio field options - X-XSS-Protection
function security_header_xss_options(): array
{
    return array(
        '0'      => '<strong>0</strong>: Disables XSS filtering (recommended)',
        '1'      => '<strong>1</strong>: Enables XSS filtering',
        '1-mode' => '<strong>1; mode=block</strong>: Prevent rendering of the page if an attack is detected',
    );
}

// radio field options - Strict-Transport-Security
function security_header_hsts_options(): array
{
    return array(
        'disable'           => 'Disabled',
        'enable'            => '<strong>max-age=31536000</strong>: Require HTTPS on this domain for one year',
        'enable-subdomains' => '<strong>max-age=31536000; includeSubDomains</strong>: Also require HTTPS on every subdomain for one year',
    );
}

// radio field options - Content-Security-Policy mode
function security_header_csp_mode_options(): array
{
    return array(
        'enforce'     => '<strong>Enforce</strong>: browsers block anything the policy does not allow',
        'report-only' => '<strong>Report-Only</strong>: nothing is blocked; browsers log what would be blocked in the developer console. Use this to test changes first.',
    );
}

// radio field options - Content-Security-Policy frame-src
function security_header_csp_frame_src_options(): array
{
    return array(
        'enable'  => 'Enable Content-Security-Policy frame-src \'self\'',
        'disable' => 'Disable Content-Security-Policy frame-src',
    );
}

// every radio field, keyed by option id (used for rendering and validation)
function security_header_radio_fields(): array
{
    return array(
        'referrer-policy'                   => security_header_referrer_policy_options(),
        'x-content-type-options'            => security_header_x_content_type_options(),
        'x-xss-protection'                  => security_header_xss_options(),
        'strict-transport-security'         => security_header_hsts_options(),
        'csp-mode'                          => security_header_csp_mode_options(),
        'content-security-policy-frame-src' => security_header_csp_frame_src_options(),
    );
}



// Section callback: Referrer-Policy
function security_header_callback_section_referrer_policy() {

    echo '<p><a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Referrer-Policy" target="_blank" rel="noopener noreferrer">More information on Referrer-Policy</a></p><p>Value sent: <strong>strict-origin-when-cross-origin</strong>. Other sites see only your domain, never the full page URL.</p>';

}

// Section callback: X-Content-Type-Options
function security_header_callback_section_x_content_type_options() {
    echo '<p><a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Content-Type-Options" target="_blank" rel="noopener noreferrer">More information on X-Content-Type-Options</a></p> <p>Value sent: <strong>nosniff</strong></p>';
}

// Section callback: X-XSS-Protection
function security_header_callback_section_x_xss_protection() {
    echo '<p><a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-XSS-Protection" target="_blank" rel="noopener noreferrer">More information on X-XSS-Protection</a></p> <p><small>Modern browsers have removed the XSS filter this header controls, and in older browsers the filter itself could be abused to leak information. <strong>0</strong> is the current recommendation; use a Content-Security-Policy for real XSS protection.</small></p>';
}

// Section callback: Strict-Transport-Security
function security_header_callback_section_hsts() {
    echo '<p><a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Strict-Transport-Security" target="_blank" rel="noopener noreferrer">More information on Strict-Transport-Security</a></p>';
    echo '<p><small>Only sent on HTTPS requests. Browsers remember it for a full year, so only enable it once the whole site works over HTTPS. Only choose <strong>includeSubDomains</strong> if every subdomain (mail, staging, etc.) also has a valid certificate.</small></p>';
}

// Section callback: Content-Security-Policy (mode and general directives)
function security_header_callback_section_csp() {
    echo '<p><a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy" target="_blank" rel="noopener noreferrer">More information on Content-Security-Policy</a></p>';
    echo '<p>These rules, plus the frame-src list below, are combined into one Content-Security-Policy header. None of them restrict your own scripts or styles, so they are safe for most sites. If something stops working, switch to Report-Only and check the browser console.</p>';
}

// Section callback: Cross-Origin-Opener-Policy
function security_header_callback_section_coop() {
    echo '<p><a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Cross-Origin-Opener-Policy" target="_blank" rel="noopener noreferrer">More information on Cross-Origin-Opener-Policy</a></p>';
}

// Section callback: Permissions-Policy
function security_header_callback_section_permissions() {
    echo '<p><a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy" target="_blank" rel="noopener noreferrer">More information on Permissions-Policy</a></p>';
    echo '<p>Checked features are turned off for your pages and anything they embed. Uncheck a feature if your site, or an embed on it, needs it (for example, a store map that asks for location). If your host already sends a Permissions-Policy, the two are combined.</p>';
}

// Section callback: Hardening
function security_header_callback_section_hardening() {
    echo '<p>Extra protections that are not headers.</p>';
}

// Section callback: Content Security Policy frame-src
function security_header_callback_section_field_checkbox_content_security_policy() {
    echo '<p><a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/frame-src" target="_blank" rel="noopener noreferrer">More information on Content-Security-Policy: frame-src</a></p>';
    echo '<p>frame-src controls which sites <em>your pages</em> may load in iframes (YouTube, Vimeo, maps, forms). It does not control who can embed your site; X-Frame-Options already handles that.</p>';
    echo '<p>Enable it above for the sites selected below to take effect.</p>';
}



// callback: radio field
function security_header_callback_field_radio( $args ) {

    $options = security_header_get_options();

    $id            = $args['id'] ?? '';
    $radio_options = $args['options'] ?? array();

    $selected_option = isset( $options[ $id ] ) ? (string) $options[ $id ] : '';

    foreach ( $radio_options as $value => $label ) {

        // numeric keys like '0' and '1' come back as integers, so compare as strings
        $value = (string) $value;

        printf(
            '<label><input name="security_header_options[%1$s]" type="radio" value="%2$s"%3$s> <span>%4$s</span></label><br />',
            esc_attr( $id ),
            esc_attr( $value ),
            checked( $selected_option, $value, false ),
            wp_kses( $label, array( 'strong' => array() ) )
        );

    }

}

// callback: textarea field
function security_header_callback_field_textarea( $args ) {

    $options = security_header_get_options();

    $id    = $args['id'] ?? '';
    $label = $args['label'] ?? '';

    $value = isset( $options[ $id ] ) ? (string) $options[ $id ] : '';

    printf(
        '<textarea id="security_header_options_%1$s" name="security_header_options[%1$s]" rows="5" cols="50">%2$s</textarea><br />',
        esc_attr( $id ),
        esc_textarea( $value )
    );
    printf(
        '<label for="security_header_options_%1$s">%2$s</label>',
        esc_attr( $id ),
        esc_html( $label )
    );

}

// callback: checkbox field
function security_header_callback_field_checkbox( $args ) {

    $options = security_header_get_options();

    $id    = $args['id'] ?? '';
    $label = $args['label'] ?? '';

    printf(
        '<input id="security_header_options_%1$s" name="security_header_options[%1$s]" type="checkbox" value="1"%2$s> ',
        esc_attr( $id ),
        checked( ! empty( $options[ $id ] ), true, false )
    );
    printf(
        '<label for="security_header_options_%1$s">%2$s</label>',
        esc_attr( $id ),
        esc_html( $label )
    );

}
