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


/**
 * Register the "book" custom post type
 */
function pluginprefix_setup_post_type() {
	register_post_type( 'book', ['public' => true ] ); 
} 
add_action( 'init', 'pluginprefix_setup_post_type' );

if ( is_admin() ) {
    // we are in admin mode
    /**
     * Activate the plugin.
     */
    function pluginprefix_activate() { 
        // Trigger our function that registers the custom post type plugin.
        pluginprefix_setup_post_type(); 
        // Clear the permalinks after the post type has been registered.
        flush_rewrite_rules(); 
    }
    register_activation_hook( __FILE__, 'pluginprefix_activate' );

    /**
     * Deactivation hook.
     */
    function pluginprefix_deactivate() {
        // Unregister the post type, so the rules are no longer in memory.
        unregister_post_type( 'book' );
        // Clear the permalinks to remove our post type's rules from the database.
        flush_rewrite_rules();
    }
    register_deactivation_hook( __FILE__, 'pluginprefix_deactivate' );

    function pluginprefix_menu_content(){
        ?>
        <h2>My setting: </h2>
        <p>test</p>
        <?php
    }

    add_action( 'admin_menu', 'pluginprefix_menu_button' );
    function pluginprefix_menu_button() {
        add_menu_page(
            'My plugin title',
            'My Plugin',
            'manage_options',
            'myplugin',
            'pluginprefix_menu_content',
            'dashicons-admin-tools',
            20
        );
    }

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

