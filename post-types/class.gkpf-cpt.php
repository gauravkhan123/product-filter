<?php
class gkpf_cpt {
    public function __construct() {
        add_action('init', array($this, 'register_cpt'));
        add_action('init', array($this, 'register_taxonomies'));
    }

    public function register_cpt() {
        $labels = array(
            'name' => 'Products',
            'singular_name' => 'Product',
            'add_new' => 'Add New Product',
            'add_new_item' => 'Add New Product',
            'edit_item' => 'Edit Product',
            'new_item' => 'New Product',
            'view_item' => 'View Product',
            'search_items' => 'Search Products',
            'not_found' => 'No products found',
            'not_found_in_trash' => 'No products found in Trash',
            'all_items' => 'All Products',
            'menu_name' => 'Products',
            'name_admin_bar' => 'Product'
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'exclude_from_search'   => false,
            'show_ui'   => true,
            'show_in_menu'  => true,
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'publicly_queryable' => true,
            'rewrite' => array('slug' => 'products'),
            'supports' => array('title', 'editor', 'thumbnail'),
            'show_in_rest' => true,
        );

        register_post_type('gkpf_product', $args);
    }

    public function register_taxonomies() {
        register_taxonomy('size', 'gkpf_product', array(
            'labels' => array(
                'name' => 'Sizes',
                'singular_name' => 'Size',
                'search_items' => 'Search Sizes',
                'all_items' => 'All Sizes',
                'edit_item' => 'Edit Size',
                'update_item' => 'Update Size',
                'add_new_item' => 'Add New Size',
                'new_item_name' => 'New Size Name',
                'menu_name' => 'Size'
            ),
            'show_admin_column' => true,
            'hierarchical' => true,
            'show_in_rest' => true
        ));

        register_taxonomy('color', 'gkpf_product', array(
            'labels' => array(
                'name' => 'Colors',
                'singular_name' => 'Color',
                'search_items' => 'Search Colors',
                'all_items' => 'All Colors',
                'edit_item' => 'Edit Color',
                'update_item' => 'Update Color',
                'add_new_item' => 'Add New Color',
                'new_item_name' => 'New Color Name',
                'menu_name' => 'Color'
            ),
            'show_admin_column' => true,
            'hierarchical' => true,
            'show_in_rest' => true
        ));
    }
}
