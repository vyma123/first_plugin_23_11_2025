<?php
add_action('admin_menu', 'my_plugin_create_menu');

function my_plugin_create_menu() {
    add_menu_page(
        'Plin Tabs',                
        'Plin Tabs',              
        'manage_options',           
        'woo-plin-tabs',            
        'my_plugin_page',           
        'dashicons-admin-generic',  
        2                            
    );
}
function my_plugin_page() {
    if (!isset($_POST['my_plugin_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['my_plugin_nonce'])), 'my_plugin_save_tabs')) {
        echo '<div class="error"><p><strong>Security check failed. Form submission is not allowed.</strong></p></div>';
    } else {
        if (!empty($_POST['new_tab_names']) && is_array($_POST['new_tab_names']) && !empty($_POST['new_tab_priorities']) && is_array($_POST['new_tab_priorities'])) {
            $new_tab_names = array_map('sanitize_text_field', wp_unslash($_POST['new_tab_names']));
            $new_tab_priorities = array_map('sanitize_text_field', wp_unslash($_POST['new_tab_priorities']));
            $filtered_tabs = [];

            $existing_priorities = get_option('woo_plin_tabs_priorities', []);
            $description_priority = get_option('woo_description_priority', 1); 
            $reviews_priority = get_option('woo_reviews_priority', 2); 

            $all_priorities = array_merge($existing_priorities, [$description_priority, $reviews_priority]);
            $max_priority = empty($all_priorities) ? 2 : max($all_priorities);

            foreach ($new_tab_names as $index => $tab) {
                if (!empty(trim($tab))) {
                    $filtered_tabs[$index] = $tab;

                    if (empty($new_tab_priorities[$index])) {
                        $max_priority++;  
                        $new_tab_priorities[$index] = $max_priority;
                    }

                    update_option("woo_plin_tab_priority_{$index}", absint($new_tab_priorities[$index]));
                }
            }

            update_option('woo_plin_tabs', array_values($filtered_tabs));
            update_option('woo_plin_tabs_priorities', $new_tab_priorities);

            echo '<div class="updated"><p><strong>Tabs saved successfully.</strong></p></div>';
        }

        if (isset($_POST['description_title']) && isset($_POST['description_priority'])) {
            update_option('woo_description_title', sanitize_text_field(wp_unslash($_POST['description_title'])));
            update_option('woo_description_priority', sanitize_text_field(wp_unslash($_POST['description_priority'])));
        }

        if (isset($_POST['reviews_title']) && isset($_POST['reviews_priority'])) {
            update_option('woo_reviews_title', sanitize_text_field(wp_unslash($_POST['reviews_title'])));
            update_option('woo_reviews_priority', sanitize_text_field(wp_unslash($_POST['reviews_priority'])));
        }
    }

    $saved_tabs = get_option('woo_plin_tabs', []);
    $description_title = get_option('woo_description_title', 'Description');
    $description_priority = get_option('woo_description_priority', 1);
    $reviews_title = get_option('woo_reviews_title', 'Reviews');
    $reviews_priority = get_option('woo_reviews_priority', 2);
?>
    <form method="POST">
        <?php wp_nonce_field('my_plugin_save_tabs', 'my_plugin_nonce'); ?>
        <div>
            <label for="description_title">Description Tab Title:</label>
            <input type="text" name="description_title" value="<?php echo esc_attr($description_title); ?>">
            <input type="number" name="description_priority" value="<?php echo esc_attr($description_priority); ?>">
        </div>
        <div>
            <label for="reviews_title">Reviews Tab Title:</label>
            <input type="text" name="reviews_title" value="<?php echo esc_attr($reviews_title); ?>">
            <input type="number" name="reviews_priority" value="<?php echo esc_attr($reviews_priority); ?>">
        </div>

       

        <button id="addBtn">Add New Tab</button>
        <div id="inputFields">
            <?php
            if ($saved_tabs) {
                foreach ($saved_tabs as $index => $tab) {
                    $tab_priority = get_option("woo_plin_tab_priority_{$index}", 10);
                    echo "<div>
                            <input type='text' name='new_tab_names[]' value='" . esc_attr($tab) . "' placeholder='Tab Name'>
                            <input type='number' name='new_tab_priorities[]' value='" . esc_attr($tab_priority) . "' placeholder='Priority'>
                          </div>";
                }
            }
            ?>
        </div>
        <button type="submit">Save</button>
    </form>
<?php
}

add_filter('woocommerce_product_tabs', 'add_custom_product_tabs');

function add_custom_product_tabs($tabs) {

        $saved_tabs = get_option('woo_plin_tabs', []);
 
    if ($saved_tabs) {
        foreach ($saved_tabs as $index => $tab_name) {
            $tab_priority = get_option("woo_plin_tab_priority_{$index}", 10); 
            $tabs["custom_tab_{$index}"] = array(
                'title'    => esc_html($tab_name), 
                'priority' => absint($tab_priority),
                'callback' => 'display_custom_tab_content',
            );
        }
    }

    return $tabs;
}

function display_custom_tab_content() {
    echo '<h2>Custom Tab Content</h2>';
    echo '<p>This is the content of the custom tab.</p>';
}

/**
 * Rename product data tabs
 */
add_filter('woocommerce_product_tabs', 'woo_rename_tabs', 98);
function woo_rename_tabs($tabs) {
    $description_title = get_option('woo_description_title', 'Description' ); 
    $reviews_title = get_option('woo_reviews_title',  'Reviews' ); 

    if (isset($tabs['description'])) {
        $tabs['description']['title'] = esc_html($description_title);
    }

    if (isset($tabs['reviews']) && $reviews_title === 'Reviews') {
        $tabs['reviews']['title'] = $tabs['reviews']['title'] ;
    }else {
        $tabs['reviews']['title'] = esc_html($reviews_title);
    }

    return $tabs;
}

/**
 * Reorder product data tabs
 */
add_filter( 'woocommerce_product_tabs', 'woo_reorder_tabs', 98 );
function woo_reorder_tabs( $tabs ) {
    $description_priority = get_option('woo_description_priority', '1' ); 
    $reviews_priority = get_option('woo_reviews_priority', '2' ); 

    if (isset($tabs['description'])) {
        $tabs['description']['priority'] = esc_html($description_priority);		
    }

    if (isset($tabs['reviews'])) {
        $tabs['reviews']['priority'] = esc_html($reviews_priority);	
    }

	return $tabs;
}
?>
