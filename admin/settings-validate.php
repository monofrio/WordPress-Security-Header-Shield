<?php // Validate Settings

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {

    exit;

}

// callback: validate options
// Builds a fresh array so only known keys are ever saved.
function security_header_callback_validate_options( $input ) {

    $input    = is_array( $input ) ? $input : array();
    $defaults = security_header_options_default();
    $output   = array();

    // Radio fields: keep the submitted choice if it is a valid one, otherwise use the default
    foreach ( security_header_radio_fields() as $id => $choices ) {

        $value = isset( $input[ $id ] ) ? (string) $input[ $id ] : '';

        $output[ $id ] = array_key_exists( $value, $choices ) ? $value : $defaults[ $id ];

    }

    // Checkboxes (site list and on/off toggles): stored as 1 / 0
    $checkbox_ids = array_merge(
        array_keys( security_header_default_sites_list() ),
        array_keys( security_header_toggle_fields() )
    );

    foreach ( $checkbox_ids as $id ) {

        $output[ $id ] = empty( $input[ $id ] ) ? 0 : 1;

    }

    // Other Sites: keep only valid CSP source expressions, one per line
    $other_sites = isset( $input['content-security-policy-other-sites'] ) ? (string) $input['content-security-policy-other-sites'] : '';

    $output['content-security-policy-other-sites'] = implode( "\n", security_header_parse_sources( $other_sites ) );

    return $output;

}
