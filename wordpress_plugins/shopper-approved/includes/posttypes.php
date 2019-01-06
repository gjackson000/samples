<?php
/**
 * Creates the custom posttypes for the plugin.
 */

/**
 * Call to chain init events if needed
 * @since 1.0
 */
function init_sa_connect_structures() {
    sa_register_post_types();
}
add_action('init', 'init_sa_connect_structures', 1);

/**
 * Register the Reviews custom posttype
 * @since 1.0
 */
function sa_register_post_types() {
    // Register Review CPT
    register_post_type('sa_review', array(
        'labels' => array(
            'name' => __('Reviews', 'shopper-approved'),
            'singular_name' => __('Review', 'shopper-approved'),
            'add_new' => __('Add New', 'shopper-approved'),
            'add_new_item' => __('Add New Review', 'shopper-approved'),
            'edit_item' => __('Edit Review', 'shopper-approved'),
            'new_item' => __('New Review', 'shopper-approved'),
            'all_items' => __('Reviews', 'shopper-approved'),
            'view_item' => __('View Review', 'shopper-approved'),
            'search_items' => __('Search Reviews', 'shopper-approved'),
            'not_found' => __('No Reviews found', 'shopper-approved'),
            'not_found_in_trash' => __('No Reviews found in Trash', 'shopper-approved'),
            'parent_item_colon' => '',
            'menu_name' => __('Reviews', 'shopper-approved')
        ),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => false,
        'query_var' => true,
        'rewrite' => array('slug'=>'reviews'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 70,
        'supports' => array('title')
    ));
}

/**
 * Add a custom meta box
 * @since 1.0
 */
function sa_custom_review_meta_boxes() {

    add_meta_box(
        'sa_review_details',
        __( 'Review Details', 'shopper-approved' ),
        'sa_admin_review_meta',
        'sa_review');

}
add_action('add_meta_boxes', 'sa_custom_review_meta_boxes');

function sa_admin_review_meta() {
    global $post;
    $review_meta = get_post_meta($post->ID);

    if (is_array($review_meta) && count($review_meta) > 0) {
        /** @var \ShopperApproved\ShopperApprovedReview $review */
        $review = new \ShopperApproved\ShopperApprovedReview();
        $review->init_from_meta($review_meta);
        ?>
        <div class="sa_review_description">
            <h3>Content</h3>
            <div>
                <?php echo apply_filters('the_content', $post->post_content); ?>
            </div>
        </div>
        <div class="sa_review_status">
            <h3>Review Status</h3>
            <div>
                <strong>API ID: </strong><span><?php echo $review->getApiId(); ?></span>
            </div>
            <div>
                <strong>Last Sync: </strong><span><?php echo $review->getLastSync(); ?></span>
            </div>
        </div>
        <div class="sa_review_details">
            <h3>Review Details</h3>
            <div>
                <strong>Name: </strong><span><?php echo $review->getName(); ?></span>
            </div>
            <div>
                <strong>Rating: </strong><span><?php echo $review->getStars(); ?></span>
            </div>
            <div>
                <strong>URL: </strong><span><?php echo $review->getURL(); ?></span>
            </div>
        </div>
        <?php
    }
}