<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

function vmv_preloader_deactivate() {
  $option = vmv_preloader_get_option();
  if ( $option['delete_option_on_deactivate'] === true ) {
    // Delete options
    delete_option( VMV_ONE__OPTION );

    $postIdDemo = $option['previewIdPage'];
    wp_delete_post( $postIdDemo, true );

  }
}