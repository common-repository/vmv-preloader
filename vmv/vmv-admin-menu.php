<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

function vmv_admin_setup_menu() {
  add_menu_page(
    VMV_ONE__PLUGIN_NAME,
    VMV_ONE__PLUGIN_SHORT_NAME,
    VMV_ONE__ADMIN_REQCAP,
    VMV_ONE__ADMIN_PAGE,
    VMV_ONE__ADMIN_FUNC,
    VMV_ONE__PLUGIN_ICON,
    VMV_ONE__PLUGIN_MENU_POS
  );
}