<?php
/**
 * Plugin Name: GK Product Filters
 * Description: this plugin helps to display any product with filter on any page via a shortcodes
 * Version: 1.0
 * Requires at least: 5.6
 * Author: Gaurav Khandelwal
 * Author URI: https://gauravkhandelwal.in/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: gk-product-filter
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit;
}

class Gk_Product_Filter
{
    public function __construct()
    {
        $this->define_constants();
        require_once (GKPF_PATH . 'post-types/class.gkpf-cpt.php');
        $gkpf_posttype = new gkpf_cpt();
        require_once (GKPF_PATH . 'shortcodes/class.gkpf-shortcode.php');
        $gkpf_shortcode = new gkpf_shortcode();
    }
    public function define_constants()
    {
        define('GKPF_PATH', plugin_dir_path(__FILE__));
        define('GKPF_URL', plugin_dir_url(__FILE__));
        define('GKPF_VERSION', '1.0');
    }
    public static function activation_hook()
    {
        update_option('rewrite_rules', '');

    }
    public static function deactivation_hook()
    {
        flush_rewrite_rules();
        unregister_post_type('gkpf_product');
    }
    public static function uninstall_hook()
    {
        $posts = get_posts(
            array(
                'post_type' => 'gkpf_product',
                'number_posts' => -1,
                'post_status' => 'any'
            )
        );

        foreach ($posts as $post) {
            wp_delete_post($post->ID, true);
        }
    }
}
register_activation_hook(__FILE__, array('Gk_Product_Filter', 'activation_hook'));
register_deactivation_hook(__FILE__, array('Gk_Product_Filter', 'deactivation_hook'));
register_uninstall_hook(__FILE__, array('Gk_Product_Filter', 'uninstall_hook'));
$gk_product_filter = new Gk_Product_Filter();
