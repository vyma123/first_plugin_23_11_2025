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
    $message = 'Tab priorities have been updated.';
    $submitt = 'Save Changes';
    $title_tabs = 'WooCommerce Product Tabs';
    $custom_tabs = get_option('custom_product_tabs', []);

    ?>
    <div class="wrap">
        <h1><?php echo esc_html($title_tabs); ?></h1>
        
        <?php
        // Check if the form is submitted

        if (isset($_POST['submit']) && check_admin_referer('my_plugin_action', 'my_plugin_nonce')) {
            if(isset($_POST['description_priority'])){
                $description_priority = intval($_POST['description_priority']);
            }
            if(isset($_POST['description_n'])){
                $description_n = sanitize_text_field(wp_unslash($_POST['description_n']));
            }
            if(isset($_POST['reviews_n'])){
                $reviews_n = sanitize_text_field(wp_unslash($_POST['reviews_n']));
            }
            if(isset($_POST['reviews_priority'])){
                $reviews_priority = intval($_POST['reviews_priority']);
            }
            
            
            // Update the priorities for product tabs
            update_option('description_priority', $description_priority);
            update_option('description_n', $description_n);
            update_option('reviews_n', $reviews_n);
            update_option('reviews_priority', $reviews_priority);
            
            // Display a success message

             // Lưu các tab tùy chỉnh
             if (isset($_POST['custom_tabs']) && is_array($_POST['custom_tabs'])) {
                $new_tabs = array_map(function ($tab) {
                return [
                    'title' => sanitize_text_field($tab['title']),
                    'priority' => intval($tab['priority']),
                    'content' => wp_kses_post($tab['content']),
                ];
            }, wp_unslash($_POST['custom_tabs']));

            update_option('custom_product_tabs', $new_tabs);
        } else {
            update_option('custom_product_tabs', []);
        }

        echo '<div class="updated"><p>' . esc_html($message) . '</p></div>';

        }

        // Get saved tab priorities or set defaults
        $description_n = get_option('description_n', 'Description');
        $reviews_n = get_option('reviews_n', 'Reviews');
        $description_priority = get_option('description_priority', 1);
        $reviews_priority = get_option('reviews_priority', 2);
        ?>
        
        <form method="post" action="">
        <?php wp_nonce_field('my_plugin_action', 'my_plugin_nonce'); ?>
            <table class="form-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Priority</th>
                    </tr>
                </thead>
                <tr valign="top">
                    <th scope="row">Description Tab</th>
                    <td>
                        <input type="text" name="description_n" value="<?php echo esc_attr($description_n); ?>" />
                    </td>
                    <td>
                        <input type="number" name="description_priority" value="<?php echo esc_attr($description_priority); ?>" min='0'/>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Reviews Tab</th>
                    <td>
                        <input type="text" name="reviews_n" value="<?php echo esc_attr($reviews_n); ?>" />
                    </td>
                    <td>
                        <input type="number" name="reviews_priority" value="<?php echo esc_attr($reviews_priority); ?>" min='0' />
                    </td>
                </tr>
            </table>

            <h2>Custom Tabs</h2>
            <div id="custom-tabs-container">
                <?php foreach ($custom_tabs as $index => $tab) : ?>
                    <div class="custom-tab">
                        <input type="text" name="custom_tabs[<?php echo esc_attr($index); ?>][title]" value="<?php echo esc_attr($tab['title']); ?>" placeholder="Tab Name" required />
                        <input min='0' type="number" name="custom_tabs[<?php echo esc_attr($index); ?>][priority]" value="<?php echo esc_attr($tab['priority']); ?>" placeholder="Priority" required/>
                        <textarea name="custom_tabs[<?php echo esc_attr($index); ?>][content]" placeholder="Tab Content"><?php echo esc_textarea($tab['content']); ?></textarea>
                        <button type="button" class="remove-tab">Remove</button>
                    </div>
                <?php endforeach; ?>
            </div>

            <button type="button" id="add-new-tab">Add New Tab</button>

            <p class="submit">
                <input type="submit" name="submit" class="button-primary" value="<?php  echo esc_attr($submitt);?>" />
            </p>
        </form>
    </div>

    <script>
        document.getElementById('add-new-tab').addEventListener('click', function() {
            var container = document.getElementById('custom-tabs-container');
            var index = container.children.length;
            
            var html = `<div class="custom-tab">
                            <input type="text" name="custom_tabs[${index}][title]" placeholder="Tab Name" required />
                            <input type="number" min='0' name="custom_tabs[${index}][priority]" placeholder="Priority" required />
                            <textarea name="custom_tabs[${index}][content]" placeholder="Tab Content"></textarea>
                            <button type="button" class="remove-tab">Remove</button>
                        </div>`;
            container.insertAdjacentHTML('beforeend', html);
        });

        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-tab')) {
                event.target.parentElement.remove();
            }
        });

       
    </script>
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


/**
 * Rename product data tabs
 */
add_filter( 'woocommerce_product_tabs', 'woo_rename_tabs', 98 );
function woo_rename_tabs( $tabs ) {
    $description_n = get_option('description_n', $tabs['description']['title']);
    $reviews_n = get_option('reviews_n', $tabs['reviews']['title']);

    if (isset($tabs['description'])) {
        $tabs['description']['title'] = $description_n;		// Rename the description tab
    }
    if (isset($tabs['reviews'])) {
        $tabs['reviews']['title'] = $reviews_n;		// Rename the description tab
    }

	return $tabs;
}


add_filter('woocommerce_product_tabs', 'woo_add_custom_tabs', 98);
function woo_add_custom_tabs($tabs) {
    $custom_tabs = get_option('custom_product_tabs', []);

    foreach ($custom_tabs as $index => $tab) {
        $tabs['custom_tab_' . $index] = [
            'title'    => esc_html($tab['title']),
            'priority' => intval($tab['priority']),
            'callback' => function() use ($tab) {
                echo '<p>' . wp_kses_post($tab['content']) . '</p>';
            },
        ];
    }

    return $tabs;
}
?>










