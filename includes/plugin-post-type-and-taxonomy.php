<?php

if ( ! function_exists('sc_embassy_post_type') ) :
/**
 * Register Embassy Post Type
 * 
 * @return void
 */
function sc_embassy_post_type() {

    $labels = array(
        'name'                => _x( 'Embassies', 'Post Type General Name', 'jr_embassy' ),
        'singular_name'       => _x( 'Embassy', 'Post Type Singular Name', 'jr_embassy' ),
        'menu_name'           => __( 'Embassies', 'jr_embassy' ),
        'parent_item_colon'   => __( 'Parent Item:', 'jr_embassy' ),
        'all_items'           => __( 'All Items', 'jr_embassy' ),
        'view_item'           => __( 'View Item', 'jr_embassy' ),
        'add_new_item'        => __( 'Add New Item', 'jr_embassy' ),
        'add_new'             => __( 'Add New', 'jr_embassy' ),
        'edit_item'           => __( 'Edit Item', 'jr_embassy' ),
        'update_item'         => __( 'Update Item', 'jr_embassy' ),
        'search_items'        => __( 'Search Item', 'jr_embassy' ),
        'not_found'           => __( 'Not found', 'jr_embassy' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'jr_embassy' ),
    );

    $args = array(
        'label'               => __( 'embassy_post', 'jr_embassy' ),
        'description'         => __( 'This post type includes word embassies', 'jr_embassy' ),
        'labels'              => $labels,
        'supports'            => array( 'title', 'thumbnail', 'comments', 'trackbacks', 'revisions', 'custom-fields', 'author' ),
        'taxonomies'          => array(),
        'hierarchical'        => true,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'rewrite'             => array( 'slug' => 'embassy' ),
        'capability_type'     => 'page',
    );
    register_post_type( 'embassy', $args );

}
add_action( 'init', 'sc_embassy_post_type', 0 );

endif; // sc_embassy_post_type();


if ( ! function_exists('sc_countries_tax') ) :
/**
 * Register Embassy Country Taxonomy
 * 
 * @return void
 */
function sc_countries_tax() {

    $labels = array(
        'name'                       => _x( 'Countries', 'Countries General Name', 'text_domain' ),
        'singular_name'              => _x( 'Country', 'Country Singular Name', 'text_domain' ),
        'menu_name'                  => __( 'Countries', 'text_domain' ),
        'all_items'                  => __( 'All Items', 'text_domain' ),
        'parent_item'                => __( 'Parent Item', 'text_domain' ),
        'parent_item_colon'          => __( 'Parent Item:', 'text_domain' ),
        'new_item_name'              => __( 'New Item Name', 'text_domain' ),
        'add_new_item'               => __( 'Add New Item', 'text_domain' ),
        'edit_item'                  => __( 'Edit Item', 'text_domain' ),
        'update_item'                => __( 'Update Item', 'text_domain' ),
        'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
        'search_items'               => __( 'Search Items', 'text_domain' ),
        'add_or_remove_items'        => __( 'Add or remove items', 'text_domain' ),
        'choose_from_most_used'      => __( 'Choose from the most used items', 'text_domain' ),
        'not_found'                  => __( 'Not Found', 'text_domain' ),
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
    );
    register_taxonomy( 'countries', array( 'embassy' ), $args );

}
add_action( 'init', 'sc_countries_tax', 0 );

endif; // sc_countries_tax();


if ( ! function_exists('sc_embassy_type_tax') ) :
/**
 * Register Embasy Types Taxonomy
 * 
 * @return void
 */
function sc_embassy_type_tax() {

    $labels = array(
        'name'                       => _x( 'Types', 'Types General Name', 'text_domain' ),
        'singular_name'              => _x( 'Type', 'Type Singular Name', 'text_domain' ),
        'menu_name'                  => __( 'Types', 'text_domain' ),
        'all_items'                  => __( 'All Items', 'text_domain' ),
        'parent_item'                => __( 'Parent Item', 'text_domain' ),
        'parent_item_colon'          => __( 'Parent Item:', 'text_domain' ),
        'new_item_name'              => __( 'New Item Name', 'text_domain' ),
        'add_new_item'               => __( 'Add New Item', 'text_domain' ),
        'edit_item'                  => __( 'Edit Item', 'text_domain' ),
        'update_item'                => __( 'Update Item', 'text_domain' ),
        'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
        'search_items'               => __( 'Search Items', 'text_domain' ),
        'add_or_remove_items'        => __( 'Add or remove items', 'text_domain' ),
        'choose_from_most_used'      => __( 'Choose from the most used items', 'text_domain' ),
        'not_found'                  => __( 'Not Found', 'text_domain' ),
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
    );
    register_taxonomy( 'types', array( 'embassy' ), $args );

}
add_action( 'init', 'sc_embassy_type_tax', 0 );

endif; // sc_embassy_type_tax();
