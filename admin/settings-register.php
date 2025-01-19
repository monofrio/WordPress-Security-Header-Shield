<?php
include 'site-list.php';

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {

    exit;

}

// register plugin settings
function security_header_register_settings() {

    register_setting(
        'security_header_options',
        'security_header_options',
        'security_header_callback_validate_options'
    );

// -- Settings Sections

    // Referrer-Policy
    add_settings_section(
        'security_header_callback_section_referrer_policy',
        'Referrer-Policy Header',
        'security_header_callback_section_referrer_policy',
        'SecurityHeader'
    );

    // X-Content-Type-Options
    add_settings_section(
        'security_header_callback_section_x_content_type_options',
        'X-Content-Type-Options Header',
        'security_header_callback_section_x_content_type_options',
        'SecurityHeader'
    );

    // X-XSS-Protection
    add_settings_section(
        'security_header_section_x_xss_protection',
        'X-XSS-Protection Header',
        'security_header_callback_section_x_xss_protection',
        'SecurityHeader'
    );

    // Content-Security-Policy - frame-src
    add_settings_section(
        'security_header_section_content-security_policy_framesrc',
        'Content-Security-Policy: frame-src',
        'security_header_callback_section_field_checkbox_content_security_policy',
        'SecurityHeader'
    );

/*

add_settings_field(
    string   $id,
    string   $title,
    callable $callback,
    string   $page,
    string   $section = 'default',
    array    $args = []
);

*/

// -- Setting Fields

    // Referrer-Policy
    add_settings_field(
        'referrer-policy',
        'Referrer-Policy',
        'security_header_callback_field_radio',
        'SecurityHeader',
        'security_header_callback_section_referrer_policy',
        [   'id' => 'referrer-policy',
            'label' => 'Custom Referrer-Policy',
            'options' => referrer_policy_options_radio() ]
    );

    // X-Content-Type-Options
    add_settings_field(
        'x-content-type-options',
        'X-Content-Type-Options',
        'security_header_callback_field_radio',
        'SecurityHeader',
        'security_header_callback_section_x_content_type_options',
        [ 'id' => 'x-content-type-options',
            'label' => 'Custom X-Content-Type-Options',
            'options' => xctp_options_radio() ]
    );

    // X-XSS-Protection
    add_settings_field(
        'x-xss-protection',
        'X-XSS-Protection',
        'security_header_callback_field_radio',
        'SecurityHeader',
        'security_header_section_x_xss_protection',
        [ 'id' => 'x-xss-protection',
            'label' => 'Custom X-XSS-Protection',
            'options' => xss_options_radio() ]
    );

    // Content-Security-Policy
    add_settings_field(
        'content-security-policy-frame-src',
        '<span style="font-size: 1rem;">Enable CSP: frame-src</span>',
        'security_header_callback_field_radio',
        'SecurityHeader',
        'security_header_section_content-security_policy_framesrc',
        [ 'id' => 'content-security-policy-frame-src',
            'label' => 'Enable Content-Security-Policy',
            'options' => csp_frame_src_options_radio ()  ]
    );

    $sh_site_list = sh_default_sites_list ();
    foreach($sh_site_list as $id => $value){

        add_settings_field(
            'content-security-policy-' . $id,
            $value[0],
            'security_header_callback_field_checkbox',
            'SecurityHeader',
            'security_header_section_content-security_policy_framesrc',
            ['id'=> $id, 'label' => $value[1]]
        );

    }

    // Other Sites
    add_settings_field(
    'content-security-policy-other-sites',
    'Other Sites',
        'security_header_callback_field_textarea',
        'SecurityHeader',
        'security_header_section_content-security_policy_framesrc',
        ['id'=> 'content-security-policy-other-sites', 'label' => '']
    );

}
add_action( 'admin_init', 'security_header_register_settings' );