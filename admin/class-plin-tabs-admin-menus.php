<?php

defined( 'ABSPATH' ) || exit;

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
    if (isset($_POST['saveTab'])) {
        if (!isset($_POST['my_plugin_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['my_plugin_nonce'])), 'my_plugin_save_tabs')) {
            $formNotAllowed = "<div class='error'><p><strong>Form submission is not allowed.</strong></p></div>";
        } else {
            $existing_tabs = get_option('woo_plin_tabs', []);
            $existing_tabs_des = get_option('woo_description_title', []);
            $existing_tabs_rev = get_option('woo_reviews_title', []);

            $existing_priorities = get_option('woo_plin_tabs_priorities', []);
            $existing_priorities_des = get_option('woo_description_priority', []);
            $existing_priorities_rev = get_option('woo_reviews_priority', []);
            
            $description_priority = get_option('woo_description_priority', 1); 
            $reviews_priority = get_option('woo_reviews_priority', 2); 
            $new_tab_names = isset($_POST['new_tab_names']) ? array_map('sanitize_text_field', wp_unslash($_POST['new_tab_names'])) : [];
            $new_tab_names_des = isset($_POST['description_title']) ?  sanitize_text_field(wp_unslash($_POST['description_title'])) : [];
            $new_tab_names_rev = isset($_POST['reviews_title']) ?  sanitize_text_field(wp_unslash($_POST['reviews_title'])) : [];
            $new_tab_priorities_des = isset($_POST['description_priority']) ?  sanitize_text_field(wp_unslash($_POST['description_priority'])) : [];
            $new_tab_priorities_rev = isset($_POST['reviews_priority']) ?  sanitize_text_field(wp_unslash($_POST['reviews_priority'])) : [];
         
            $new_tab_priorities = isset($_POST['new_tab_priorities']) ? array_map('sanitize_text_field', wp_unslash($_POST['new_tab_priorities'])) : [];

            if ($existing_tabs == $new_tab_names && $existing_priorities == $new_tab_priorities && $existing_tabs_des == $new_tab_names_des && $existing_tabs_rev == $new_tab_names_rev && $existing_priorities_des == $new_tab_priorities_des && $existing_priorities_rev == $new_tab_priorities_rev) {
                $saveSuccess = '<div id="no_change_detected" class="notice notice-warning"><p><strong>No changes detected.</strong></p></div>';
            } else {
                $filtered_tabs = [];
                $all_priorities = array_merge($existing_priorities, [$description_priority, $reviews_priority]);
                $max_priority =  max($all_priorities);

                foreach ($new_tab_names as $index => $tab) {
                    if (!empty(trim($tab))) {
                        $filtered_tabs[$index] = $tab;
                
                        if (empty($new_tab_priorities[$index])) {
                            $max_priority++;
                            $new_tab_priorities[$index] = $max_priority;
                        }
                    }
                }
                update_option('woo_plin_tabs_priorities', $new_tab_priorities);

                update_option('woo_plin_tabs', array_values($filtered_tabs));
                update_option('woo_plin_tabs_priorities', $new_tab_priorities);

                $saveSuccess = '<div id="tab_save_success" class="updated"><p><strong>Tabs saved successfully.</strong></p></div>';
            }

            if ((get_option('woo_description_title') !== $_POST['description_title']) || (get_option('woo_description_priority') !== $_POST['description_priority'])) {
                update_option('woo_description_title', sanitize_text_field(wp_unslash($_POST['description_title'])));
                update_option('woo_description_priority', sanitize_text_field(wp_unslash($_POST['description_priority'])));
            }

            if ((get_option('woo_reviews_title') !== $_POST['reviews_title']) || (get_option('woo_reviews_priority') !== $_POST['reviews_priority'])) {
                update_option('woo_reviews_title', sanitize_text_field(wp_unslash($_POST['reviews_title'])));
                update_option('woo_reviews_priority', sanitize_text_field(wp_unslash($_POST['reviews_priority'])));
            }
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
        <h1 class='heading-plin-tabs'>Plin Tabs</h1>
        <button id="addBtn" class="ui primary basic button">Add New Tab</button>
        <div id='added_new_tab' class='notice notice-info d-none'></div>

        <?php if(!empty($saveSuccess)){
            echo wp_kses_post($saveSuccess);
        } 
        if(!empty($formNotAllowed)){
            echo wp_kses_post($formNotAllowed);
        } 
        ?>

        <div class="ui form">
        <div class="fields">
            <div class="field">
            <label>Name</label>
            <input type="text" name="description_title" value="<?php echo esc_attr($description_title); ?>">
            </div>
            <div class="field">
            <label>Priority</label>
            <input type="number" name="description_priority" value="<?php echo esc_attr($description_priority); ?>">
            </div>
        </div>
        <div class="fields">
            <div class="field">
            <label>Name</label>
            <input type="text" name="reviews_title" value="<?php echo esc_attr($reviews_title); ?>">
            </div>
            <div class="field">
            <label>Priority</label>
            <input type="number" name="reviews_priority" value="<?php echo esc_attr($reviews_priority); ?>">
            </div>
        </div>
        </div>

        <div id="inputFields">
            <?php
            if ($saved_tabs) {
                foreach ($saved_tabs as $index => $tab) {
                    $all_priorities = get_option('woo_plin_tabs_priorities', []);
                    $tab_priority = isset($all_priorities[$index]) ? $all_priorities[$index] : 10;

                    echo "
                         <div class='ui form'>
                         <div class='fields'>
                            <div class='field'>
                            <label>Name</label>
                            <input type='text' name='new_tab_names[]' value='" . esc_attr($tab) . "' placeholder='Tab Name'>
                            </div>
                            <div class='field'>
                            <label>Priority</label>
                            <input type='number' name='new_tab_priorities[]' value='" . esc_attr($tab_priority) . "' placeholder='Priority'>
                            </div>
                         </div>
                         </div>
                    ";
                }
            }
            ?>
        </div>
        <button type="submit" name='saveTab' class="ui green button">Save</button>
    </form>
<?php
}

add_filter('woocommerce_product_tabs', 'add_custom_product_tabs');

function add_custom_product_tabs($tabs) {

        $saved_tabs = get_option('woo_plin_tabs', []);
 
    if ($saved_tabs) {
        foreach ($saved_tabs as $index => $tab_name) {
            $all_priorities = get_option('woo_plin_tabs_priorities', []);
            $tab_priority = isset($all_priorities[$index]) ? $all_priorities[$index] : 10;
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
    echo '<h2>Tab Content</h2>';
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
