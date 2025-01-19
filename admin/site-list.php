<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {

    exit;

}

function sh_default_sites_list (): array
{
    // ID => [ Title, Value ]
    return array(
        'googleapis_com' => ['Google APIs', 'https://*.google.com https://*.gstatic.com https://*.googleapis.com'],
        'google-analytics_com' => ['Google Analytics', 'https://*.google-analytics.com'],
        'googletagmanager_com' => ['Google Tag Manager', 'https://*.googletagmanager.com'],

        'bing_com' => ['Bing', 'https://*.bing.com'],
        'bizible_com' => ['Bizible','https://*.bizible.com'],
        'bizzabo_com' => ['Bizzabo', 'https://*.bizzabo.com'],
        'gravatar_com' => ['Gavatar', 'https://*.gravatar.com'],
        'youtube_com' => ['YouTube', 'https://www.youtube.com'],
        'doubleclick_net' => ['DoubleClick', 'http://*.doubleclick.net'],
        'hotjar_com' => ['Hot Jar', 'https://*.hotjar.com'],
        'facebook_com' => ['Facebook', 'https://www.facebook.com/'],
        'vimeo_com' => ['Vimeo', 'https://*.vimeo.com'],
        'zoominfo_com' => ['Zoom', 'https://*.zoominfo.com'],
        'pardot_com' => ['Pardot', 'https://*.pardot.com'],
        '6sc_co' => ['6sense', 'https://*.6sc.co'],
        'adsrvr_org' => ['The Trade Desk: Cookies ', 'https://*.adsrvr.org'],
        'cloudfront_net' => ['CloudFront', 'https://*.cloudfront.net'],
        'slideshare_net' => ['Slideshare', 'https://*.slideshare.net'],
        'newrelic_com' => ['New Relic', 'https://*.newrelic.com https://*.nr-data.net'],


    );
}