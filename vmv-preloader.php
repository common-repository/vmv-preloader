<?php
/*
Plugin Name: VMV preloader
Description: Add to your site modern preloader with cool animation.
Version: 1.3.1
Author: Aleksandr Zidyganov
Author URI: https://vmv.one
Author Email: alex@csscoder.pro
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

	Copyright 2023 VMV.ONE (alex@vmv.one)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 3, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA

*/

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

$VMV_ONE__PLUGIN_DIR_NAME_ = explode( '/', dirname( __FILE__ ) );

const VMV_ONE__ADMIN_FUNC = 'vmv_preloader_admin_init';
const VMV_ONE__ADMIN_PAGE = 'vmv-preloader';
const VMV_ONE__ADMIN_REQCAP = 'add_users';
const VMV_ONE__BASE = 'vmv/';
const VMV_ONE__OPTION = 'vmv_preloader_settings';
define( 'VMV_ONE__PLUGIN_ADMIN_URL', admin_url( 'admin.php?page=' . VMV_ONE__ADMIN_PAGE ) );
define( 'VMV_ONE__PLUGIN_DIR', plugin_dir_path( __FILE__ ) . VMV_ONE__BASE );
define( 'VMV_ONE__PLUGIN_DIR_NAME', end( $VMV_ONE__PLUGIN_DIR_NAME_ ) );
const VMV_ONE__UPLOAD_DIR = 'premium-animations';
const VMV_ONE__PLUGIN_FILE = __FILE__;
const VMV_ONE__PLUGIN_ICON = 'dashicons-art';
const VMV_ONE__PLUGIN_MENU_POS = '80.00000000000003';
const VMV_ONE__PLUGIN_NAME = 'VMV Preloader : power of SVG animation.';
const VMV_ONE__PLUGIN_SHORT_NAME = 'VMV Preloader';
define( 'VMV_ONE__AJAX_URL', admin_url( 'admin-ajax.php' ) );
define( 'VMV_ONE__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
const VMV_ONE__PLUGIN_VERSION = '1.2.1';

foreach ( glob( VMV_ONE__PLUGIN_DIR . '*.php' ) as $file ) {
  require_once( $file );
}
add_action( 'admin_enqueue_scripts', 'vmv_admin_frontend' );
add_action( 'admin_menu', 'vmv_admin_setup_menu' );
add_action( 'init', 'vmv_preloader_activate' );

// Plugin init actions
add_action( 'wp_head', 'vmv_preloader_head_section', 1 );

// ajax actions
add_action( 'wp_ajax_vmv_get_params', 'get_ajax_params' );
add_action( 'wp_ajax_vmv_set_params', 'set_ajax_params' );
add_action( 'wp_ajax_vmv_list_base_animations', 'vmv_list_base_animations' );
add_action( 'wp_ajax_vmv_upload_file_premium_animations', 'vmv_upload_file_premium_animations' );
add_action( 'wp_ajax_vmv_delete_file_premium_animations', 'vmv_delete_file_premium_animations' );
add_action( 'wp_ajax_vmv_list_premium_animations', 'vmv_list_premium_animations' );

add_filter( 'plugin_action_links', 'vmv_admin_plugin_links', 10, 2 );

register_activation_hook( VMV_ONE__PLUGIN_FILE, 'vmv_preloader_on_activation' );
register_deactivation_hook( VMV_ONE__PLUGIN_FILE, 'vmv_preloader_on_deactivation' );
function vmv_preloader_on_activation() {
  vmv_preloader_activate();
}
function vmv_preloader_on_deactivation() {
  vmv_preloader_deactivate();
}
