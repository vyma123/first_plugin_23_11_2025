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
    ?>
    <div class="wrap">
        <h1>Reorder WooCommerce Product Tabs</h1>
        
        <?php
        // Check if the form is submitted
        if (isset($_POST['submit'])) {
            $description_priority = intval($_POST['description_priority']);
            $reviews_priority = intval($_POST['reviews_priority']);
            
            // Update the priorities for product tabs
            update_option('description_priority', $description_priority);
            update_option('reviews_priority', $reviews_priority);
            
            // Display a success message
            echo '<div class="updated"><p>Tab priorities have been updated.</p></div>';
        }

        // Get saved tab priorities or set defaults
        $description_priority = get_option('description_priority', 1);
        $reviews_priority = get_option('reviews_priority', 2);
        ?>
        
        <form method="post" action="">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Description Tab Priority</th>
                    <td>
                        <input type="number" name="description_priority" value="<?php echo esc_attr($description_priority); ?>" min="1" />
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Reviews Tab Priority</th>
                    <td>
                        <input type="number" name="reviews_priority" value="<?php echo esc_attr($reviews_priority); ?>" min="1" />
                    </td>
                </tr>
            </table>

            <p class="submit">
                <input type="submit" name="submit" class="button-primary" value="Save Changes" />
            </p>
        </form>
    </div>
    <?php
}

// Reorder product tabs based on the saved priorities
add_filter('woocommerce_product_tabs', 'woo_reorder_tabs', 98);
function woo_reorder_tabs($tabs) {
    // Get the saved tab priorities
    $description_priority = get_option('description_priority', 1);
    $reviews_priority = get_option('reviews_priority', 2);

    // Reorder the tabs based on the saved priorities
    if (isset($tabs['description'])) {
        $tabs['description']['priority'] = $description_priority;
    }

    if (isset($tabs['reviews'])) {
        $tabs['reviews']['priority'] = $reviews_priority;
    }

    return $tabs;
}
?>