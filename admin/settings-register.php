<?php

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

    // Strict-Transport-Security
    add_settings_section(
        'security_header_section_hsts',
        'Strict-Transport-Security Header',
        'security_header_callback_section_hsts',
        'SecurityHeader'
    );

    // Content-Security-Policy - mode and general directives
    add_settings_section(
        'security_header_section_csp',
        'Content-Security-Policy',
        'security_header_callback_section_csp',
        'SecurityHeader'
    );

    // Content-Security-Policy - frame-src
    add_settings_section(
        'security_header_section_content-security_policy_framesrc',
        'Content-Security-Policy: frame-src (allowed embeds)',
        'security_header_callback_section_field_checkbox_content_security_policy',
        'SecurityHeader'
    );

    // Cross-Origin-Opener-Policy
    add_settings_section(
        'security_header_section_coop',
        'Cross-Origin-Opener-Policy Header',
        'security_header_callback_section_coop',
        'SecurityHeader'
    );

    // Permissions-Policy
    add_settings_section(
        'security_header_section_permissions',
        'Permissions-Policy Header',
        'security_header_callback_section_permissions',
        'SecurityHeader'
    );

    // Hardening
    add_settings_section(
        'security_header_section_hardening',
        'Hardening',
        'security_header_callback_section_hardening',
        'SecurityHeader'
    );

// -- Setting Fields

    // Referrer-Policy
    add_settings_field(
        'referrer-policy',
        'Referrer-Policy',
        'security_header_callback_field_radio',
        'SecurityHeader',
        'security_header_callback_section_referrer_policy',
        [ 'id' => 'referrer-policy',
          'options' => security_header_referrer_policy_options() ]
    );

    // X-Content-Type-Options
    add_settings_field(
        'x-content-type-options',
        'X-Content-Type-Options',
        'security_header_callback_field_radio',
        'SecurityHeader',
        'security_header_callback_section_x_content_type_options',
        [ 'id' => 'x-content-type-options',
          'options' => security_header_x_content_type_options() ]
    );

    // X-XSS-Protection
    add_settings_field(
        'x-xss-protection',
        'X-XSS-Protection',
        'security_header_callback_field_radio',
        'SecurityHeader',
        'security_header_section_x_xss_protection',
        [ 'id' => 'x-xss-protection',
          'options' => security_header_xss_options() ]
    );

    // Strict-Transport-Security
    add_settings_field(
        'strict-transport-security',
        'Strict-Transport-Security',
        'security_header_callback_field_radio',
        'SecurityHeader',
        'security_header_section_hsts',
        [ 'id' => 'strict-transport-security',
          'options' => security_header_hsts_options() ]
    );

    // Content-Security-Policy mode
    add_settings_field(
        'csp-mode',
        'Mode',
        'security_header_callback_field_radio',
        'SecurityHeader',
        'security_header_section_csp',
        [ 'id' => 'csp-mode',
          'options' => security_header_csp_mode_options() ]
    );

    // On/off toggles: CSP directives, COOP, Permissions-Policy features, hardening
    foreach ( security_header_toggle_fields() as $id => $field ) {

        add_settings_field(
            $id,
            esc_html( $field['title'] ),
            'security_header_callback_field_checkbox',
            'SecurityHeader',
            'security_header_section_' . $field['section'],
            [ 'id' => $id, 'label' => $field['label'] ]
        );

    }

    // Content-Security-Policy frame-src
    add_settings_field(
        'content-security-policy-frame-src',
        'Enable CSP: frame-src',
        'security_header_callback_field_radio',
        'SecurityHeader',
        'security_header_section_content-security_policy_framesrc',
        [ 'id' => 'content-security-policy-frame-src',
          'options' => security_header_csp_frame_src_options() ]
    );

    foreach ( security_header_default_sites_list() as $id => $site ) {

        add_settings_field(
            'content-security-policy-' . $id,
            esc_html( $site[0] ),
            'security_header_callback_field_checkbox',
            'SecurityHeader',
            'security_header_section_content-security_policy_framesrc',
            [ 'id' => $id, 'label' => $site[1] ]
        );

    }

    // Other Sites
    add_settings_field(
        'content-security-policy-other-sites',
        'Other Sites',
        'security_header_callback_field_textarea',
        'SecurityHeader',
        'security_header_section_content-security_policy_framesrc',
        [ 'id' => 'content-security-policy-other-sites',
          'label' => 'One per line, e.g. https://example.com or https://*.example.com. Invalid entries are removed when you save.' ]
    );

}
add_action( 'admin_init', 'security_header_register_settings' );
