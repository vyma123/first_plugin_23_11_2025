<?php

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

    /**
     * Register the "book" custom post type
     */
    function pluginprefix_setup_post_type() {
        register_post_type( 'book', ['public' => true ] ); 
    } 
    add_action( 'init', 'pluginprefix_setup_post_type' );

    function pluginprefix_menu_content(){
         include PLUGINPREFIX_DIR_PATH . 'admin/plugin-settings.php';
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

    function pluginprefix_add_submenu_page_content() {
    ?>
    <h2>My setting: </h2>
    <p>test</p>
    <h2>99</h2>

    <?php
    }

    function pluginprefix_define_submenu_button()
    {
        add_submenu_page(
            'myplugin',
            'My Submenu',
            'My Submenu',
            'manage_options',
            'submenu',
            'pluginprefix_add_submenu_page_content'
        );
    }
    add_action('admin_menu', 'pluginprefix_define_submenu_button');

    ?>