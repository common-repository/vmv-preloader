<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}
function vmv_preloader_admin_init() {
//  $update = vmv_preloader_update_settings();
//  $option = vmv_preloader_get_option();
//  $file_paths = list_files( VMV_ONE__PLUGIN_DIR . "uploaded-animations", 1 );
  ?>
  <div
    id="app-vmv"
    data-vmv-action="<?php echo VMV_ONE__AJAX_URL ?>"
    data-vmv-token="<?php echo wp_create_nonce( 'vmv_preloader_token' ) ?>">
  </div>
  <?php
}