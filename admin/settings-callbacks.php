<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {

    exit;

}


// radio field options =  Referrer-Policy
function referrer_policy_options_radio (): array
{

    return array(

        'enable'  => 'Enable Referrer-Policy',
        'disable' => 'Disable Referrer-Policy'

    );

}

// radio field options - X-Content-Type-Options
function xctp_options_radio (): array
{
    return array (
        'enable'  => 'Enable X-Content-Type-Options',
        'disable' => 'Disable X-Content-Type-Options'
    );
}

// radio field options X-XSS-Protection
function  xss_options_radio (): array
{
    return array(
        '0'  => '<strong>0</strong>: Disables XSS filtering',
        '1' => '<strong>1</strong>: Enables XSS filtering',
        '1-mode' => '<strong>1; mode=block</strong>: Prevent rendering of the page if an attack is detected'
    );
}

// radio fields options Content-Security-Policy frame-src
function csp_frame_src_options_radio (): array{
    return array(
        'enable'  => 'Enable Content-Security-Policy frame-src "self"',
        'disable' => 'Disable Content-Security-Policy frame-src "self'
    );
}



// Section callback: Referrer-Policy
function security_header_callback_section_referrer_policy() {

    echo '<p><a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Referrer-Policy" target="_blank" >More information on Referrer-Policy</a></p><p>Default value: <strong>no-referrer-when-downgrade</strong></p>';

}

// Section callback: X-Content-Type-Options
function security_header_callback_section_x_content_type_options() {
    echo '<p><a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Content-Type-Options" target="_blank">More information on X-Cotent-Type-Options</a></p> <p>Default Value: <strong>nosniff</strong></p>';
}

// Section callback: X-XSS-Protection
function security_header_callback_section_x_xss_protection() {
    echo '<p><a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-XSS-Protection" target="_blank">More information on X-XSS-Protection</a></p> <p><small>The HTTP X-XSS-Protection response header is a feature of Internet Explorer, Chrome and Safari that stops pages from loading when they detect reflected cross-site scripting (XSS) attacks. These protections are largely unnecessary in modern browsers when sites implement a strong Content-Security-Policy that disables the use of inline JavaScript (\'unsafe-inline\').</small></p>';
}

// Section callback: Content Security Policy
function security_header_callback_section_field_checkbox_content_security_policy () {
    $site_options = get_option('security_header_options');
    echo '<p><a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy" target="_blank">More information on Content-Security-Policy</a> </p>';
    echo '<p>Must have it enabled for the domains to be set below for frame-src</p>';
}



// callback: radio field
function security_header_callback_field_radio( $args ) {

    $options = get_option( 'security_header_options', security_header_options_default() );

    $id    = isset( $args['id'] )    ? $args['id']    : '';
    $label = isset( $args['label'] ) ? $args['label'] : '';
    $radio_options = isset( $args['options']) ? $args['options'] : '';

    $selected_option = isset( $options[$id] ) ? sanitize_text_field( $options[$id] ) : '';

    foreach ( $radio_options as $value => $label ) {

        $checked = checked( $selected_option === $value, true, false );
        echo '<label><input name="security_header_options['. $id .']" type="radio" value="'. $value .'"'. $checked .'> ';
        echo '<span>'. $label .'</span></label><br />';

    }

}

// callback: text field
function security_header_callback_field_text( $args ) {

    $options = get_option( 'security_header_options', security_header_options_default() );

    $id    = isset( $args['id'] )    ? $args['id']    : '';
    $label = isset( $args['label'] ) ? $args['label'] : '';

    $value = isset( $options[$id] ) ? sanitize_text_field( $options[$id] ) : '';

    echo '<input id="security_header_options_'. $id .'" name="security_header_options['. $id .']" type="text" size="40" value="'. $value .'"><br />';
    echo '<label for="security_header_options_'. $id .'">'. $label .'</label>';


}

// callback: textarea field
function security_header_callback_field_textarea( $args ) {

    $options = get_option( 'security_header_options', security_header_options_default() );

    $id    = isset( $args['id'] )    ? $args['id']    : '';
    $label = isset( $args['label'] ) ? $args['label'] : '';

    $allowed_tags = wp_kses_allowed_html( 'post' );

    $value = isset( $options[$id] ) ? wp_kses( stripslashes_deep( $options[$id] ), $allowed_tags ) : '';

    echo '<label for="security_header_options_'. $id .'">'. $label .'</label> ';
    echo '<textarea id="security_header_options_'. $id .'" name="security_header_options['. $id .']" rows="5" cols="50">'. $value .'</textarea><br />';


}

// callback: checkbox field
function security_header_callback_field_checkbox( $args ) {

    $options = get_option( 'security_header_options', security_header_options_default() );


    $id    = $args['id'] ?? '';
    $value = $args['label'] ?? '';

    $checked = isset( $options[$id] ) ? checked( $options[$id], $value, false ) : '';

    echo '<input id="security_header_options_'. $id .'" name="security_header_options['. $id .']" type="checkbox" value="' . $value . '" '. $checked .'> ';
    echo '<label for="security_header_options_'. $id .'">'. $value .'</label>';

}

// callback: select field
function security_header_callback_field_select( $args ) {

    $options = get_option( 'security_header_options', security_header_options_default() );

    $id    = isset( $args['id'] )    ? $args['id']    : '';
    $label = isset( $args['label'] ) ? $args['label'] : '';

    $selected_option = isset( $options[$id] ) ? sanitize_text_field( $options[$id] ) : '';

    $select_options = array(

        'default'   => 'Default',
        'light'     => 'Light',
        'blue'      => 'Blue',
        'coffee'    => 'Coffee',
        'ectoplasm' => 'Ectoplasm',
        'midnight'  => 'Midnight',
        'ocean'     => 'Ocean',
        'sunrise'   => 'Sunrise',

    );

    echo '<select id="security_header_options_'. $id .'" name="security_header_options['. $id .']">';

    foreach ( $select_options as $value => $option ) {

        $selected = selected( $selected_option === $value, true, false );

        echo '<option value="'. $value .'"'. $selected .'>'. $option .'</option>';

    }

    echo '</select> <label for="security_header_options_'. $id .'">'. $label .'</label>';

}