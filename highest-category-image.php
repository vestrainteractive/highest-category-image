<?php
/*
Plugin Name: Highest Category Image in Post Title
Plugin URI: https://github.com/vestrainteractive/highest-category-image/
Description: Displays the highest category image to the left of the post title.
Version: 1.0
Author: Vestra Interactive
Author URI: https://vestrainteractive.com
*/

// Enqueue CSS for styling the image beside the title
function hci_enqueue_styles() {
    echo '<style>
        .hci-category-image { float: left; margin-right: 10px; }
        </style>';
}
add_action('wp_head', 'hci_enqueue_styles');

// Get the highest priority category with an image
function hci_get_highest_category_image($post_id) {
    // Ensure Category Images plugin function exists
    if (!function_exists('z_taxonomy_image_url')) return '';

    // Get categories sorted by hierarchy
    $categories = get_the_category($post_id);
    if (empty($categories)) return '';

    // Sort categories by parent-child relationship
    $categories = wp_list_sort($categories, 'term_id', 'ASC');
    $highest_category = end($categories);

    // Retrieve category image URL
    $category_image_url = z_taxonomy_image_url($highest_category->term_id);
    return $category_image_url ? '<img src="' . esc_url($category_image_url) . '" class="hci-category-image" />' : '';
}

// Filter post title to prepend category image
function hci_add_category_image_to_title($title, $post_id) {
    if (is_admin() || !is_singular('post')) return $title;

    $category_image = hci_get_highest_category_image($post_id);
    return $category_image . $title;
}
add_filter('the_title', 'hci_add_category_image_to_title', 10, 2);

?>
