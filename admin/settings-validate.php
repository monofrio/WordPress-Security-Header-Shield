<?php // Validate Settings

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {

    exit;

}

// callback: validate options
function security_header_callback_validate_options( $input ) {

    // Validate Radio Field Options
    $validate_radio_lists =  array(
        'referrer-policy' => referrer_policy_options_radio(),
        'x-content-type-options' => xctp_options_radio(),
        'x-xss-protection' => xss_options_radio(),
        'content-security-policy-frame-src' => csp_frame_src_options_radio ()
    );

    foreach( $validate_radio_lists as $id => $options ){

        $radio_options = $options;

        if ( ! isset( $input[$id] ) ) {

            $input[$id] = null;

        }
        if ( ! array_key_exists( $input[$id], $radio_options ) ) {

            $input[$id] = null;

        }
    }

    // Validate Checkbox Field Options
    foreach(sh_default_sites_list () as $id => $options){

        if ( ! isset( $input['content-security-policy-' . $id] ) ) {

            $input['content-security-policy-' . $id] = null;

        }

        $input['content-security-policy-' . $id] = ($input['content-security-policy-' . $id] == 1 ? 1 : 0);
    }

    // Validate Other Sites Field
    if ( isset( $input['content-security-policy-other-sites'] ) ) {

        $input['content-security-policy-other-sites'] = wp_kses_post( $input['content-security-policy-other-sites'] );

    }




    return $input;

}
