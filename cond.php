<?php

if ( upload_is_valid( $settings, $key ) ) {
    return '1';
}

function upload_is_valid( $settings, $key ) {
    return isset( $settings['wp-uploads'] ) && $settings['wp-uploads'] && in_array( $key, array( 'copy-to-s3', 'serve-from-s3' ) );
}
