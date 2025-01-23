<?php
/*
 * Plugin Name:       Plin tab
 * Plugin URI:        https://villatheme.com
 * Description:       Hello tab
 * Version:           1.10.3
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Vy Ma Van
 * Author URI:        https://villatheme.com
 * License:           GPL v2 or later
 * Domain Path:       /languages
 * Requires Plugins:  woocommerce
 */

/*
Plin tab is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Plin tab is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Plin tab. If not, see {URI to Plugin License}.
*/

if(!defined('PLUGINPREFIX_DIR_PATH')){
    define('PLUGINPREFIX_DIR_PATH', plugin_dir_path(__FILE__));
}

if(!defined('PLUGINPREFIX_DIR_URL')){
    define('PLUGINPREFIX_DIR_URL', plugin_dir_url(__FILE__));
}

if(!defined('PLUGINPREFIX_URL')){
    define('PLUGINPREFIX_URL', plugins_url('', __FILE__));
}


if ( is_admin() ) {
    // we are in admin mode

    include PLUGINPREFIX_DIR_PATH . 'admin/admin-core.php';

}else{
    //front end
}


//  add_action('admin_notices', 'my_function');

//  function my_function() {
    
//  }

add_filter( 'woocommerce_product_tabs', 'woo_rename_tabs', 98 );
function woo_rename_tabs( $tabs ) {

	// $tabs['description']['title'] = __( 'More Information' );		
	// $tabs['reviews']['title'] = __( 'Ratings' );				
  	$tabs['additional_information']['title'] = __( 'Product Data', 'plin-tabs' );	

	return $tabs;
}
?>

