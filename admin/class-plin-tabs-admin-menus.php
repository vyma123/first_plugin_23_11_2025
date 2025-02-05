<?php
// Hook to add admin menu
add_action('admin_menu', 'my_plugin_create_menu');

// Create the menu page
function my_plugin_create_menu() {
    add_menu_page(
        'Plin Tabs',                // Page title
        'Plin Tabs',                // Menu title
        'manage_options',           // Capability
        'woo-plin-tabs',            // Menu slug
        'my_plugin_page',           // Function to display the page content
        'dashicons-admin-generic',  // Icon
        2                            // Position
    );
}

// Display the plugin settings page
function my_plugin_page() {
    // Check if the nonce is valid
    if (isset($_POST['my_plugin_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['my_plugin_nonce'])), 'my_plugin_save_tabs')) {
        // Handle saving new tabs
        
        if (isset($_POST['new_tab_names']) && is_array($_POST['new_tab_names'])) {
            $sanitized_tabs = array_map('sanitize_text_field', wp_unslash($_POST['new_tab_names']));
            update_option('woo_plin_tabs', $sanitized_tabs);
        }
        
        if (isset($_POST['description_title'])) {
            update_option('woo_description_title', sanitize_text_field(wp_unslash($_POST['description_title'])));
        }
        if (isset($_POST['reviews_title'])) {
            update_option('woo_reviews_title', sanitize_text_field(wp_unslash($_POST['reviews_title'])));
        }

    } else {
        echo '<div class="error"><p><strong>Security check failed. Form submission is not allowed.</strong></p></div>';
    }

    // Get saved tabs
    $saved_tabs = get_option('woo_plin_tabs', []);
    $description_title = get_option('woo_description_title', 'Description'); 
    $reviews_title = get_option('woo_reviews_title', 'Reviews'); 
    ?>
    <form method="POST">
        <?php
        // Add a nonce field to the form for security
        wp_nonce_field('my_plugin_save_tabs', 'my_plugin_nonce');
        ?>
        <div>
            <label for="description_title">Description Tab Title:</label>
            <input type="text" name="description_title" value="<?php echo esc_attr($description_title); ?>">
        </div>
        <div>
            <label for="reviews_title">Reviews Tab Title:</label>
            <input type="text" name="reviews_title" value="<?php echo esc_attr($reviews_title); ?>">
        </div>

        <button id='addBtn'>Add New Tab</button>
        <div id="inputFields">
            <?php
            // Prepopulate existing tabs if any
            if ($saved_tabs) {
                foreach ($saved_tabs as $tab) {
                    echo "<div><input type='text' name='new_tab_names[]' value='" . esc_attr($tab) . "'></div>";
                }
            }
            ?>
        </div>
        <button type="submit">Save</button>
    </form>
    <?php
}

// Hook to add custom tabs to WooCommerce products
add_filter('woocommerce_product_tabs', 'add_custom_product_tabs');

function add_custom_product_tabs($tabs) {
    // Get the saved tabs
    $saved_tabs = get_option('woo_plin_tabs', []);
    

    if ($saved_tabs) {
        foreach ($saved_tabs as $index => $tab_name) {
            $tabs["custom_tab_{$index}"] = array(
                'title'    => esc_html($tab_name), 
                'callback' => 'display_custom_tab_content',
            );
        }
    }

    return $tabs;
}

// Callback to display the custom tab content
function display_custom_tab_content() {
    echo '<h2>Custom Tab Content</h2>';
    echo '<p>This is the content of the custom tab.</p>';
}

/**
 * Rename product data tabs
 */
add_filter('woocommerce_product_tabs', 'woo_rename_tabs', 98);
function woo_rename_tabs($tabs) {
    // Get the saved titles for Description and Reviews
    $description_title = get_option('woo_description_title', 'Description' ); // Default title
    $reviews_title = get_option('woo_reviews_title',  'Reviews' ); // Default title

    // Rename the description tab
    if (isset($tabs['description'])) {
        $tabs['description']['title'] = esc_html($description_title);
    }

    // Rename the reviews tab
    if (isset($tabs['reviews']) && $reviews_title === 'Reviews') {
        $tabs['reviews']['title'] = $tabs['reviews']['title'] ;
    }else {
        $tabs['reviews']['title'] = esc_html($reviews_title);
    }

    return $tabs;
}

?>
