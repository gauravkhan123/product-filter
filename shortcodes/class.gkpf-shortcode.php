<?php
class gkpf_shortcode {
    public function __construct() {
        add_shortcode('gk_product_filter', array($this, 'render_shortcode'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_pfp_filter_products', array($this, 'filter_products'));
        add_action('wp_ajax_nopriv_pfp_filter_products', array($this, 'filter_products'));
    }

    public function render_shortcode($atts) {
        ob_start();
        ?>
        <div id="product-filter" class="container">
            <div class="row mb-3">
                <h3>Filter Products</h3>
                <div class="col-md-3">
                    <label for="size-filter" class="form-label">Size</label>
                    <select id="size-filter" class="form-select">
                        <option value="">All Sizes</option>
                        <?php
                        $sizes = get_terms(array('taxonomy' => 'size', 'hide_empty' => false));
                        foreach ($sizes as $size) {
                            echo '<option value="' . $size->slug . '">' . $size->name . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="color-filter" class="form-label">Color</label>
                    <select id="color-filter" class="form-select">
                        <option value="">All Colors</option>
                        <?php
                        $colors = get_terms(array('taxonomy' => 'color', 'hide_empty' => false));
                        foreach ($colors as $color) {
                            echo '<option value="' . $color->slug . '">' . $color->name . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div id="product-list" class="row">
                <?php
                $args = array('post_type' => 'gkpf_product', 'posts_per_page' => -1);
                $products = new WP_Query($args);
                if ($products->have_posts()) {
                    while ($products->have_posts()) {
                        $products->the_post();
                        $sizes = wp_get_post_terms(get_the_ID(), 'size', array('fields' => 'names'));
                        $colors = wp_get_post_terms(get_the_ID(), 'color', array('fields' => 'names'));
                        $featured_image = has_post_thumbnail() ? get_the_post_thumbnail_url() : GKPF_URL. 'default-image.jpg';
                        ?>
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <img src="<?php echo esc_url($featured_image); ?>" class="card-img-top" alt="<?php the_title_attribute(); ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?php the_title(); ?></h5>
                                    <p class="card-text"><?php the_excerpt(); ?></p>
                                    <p class="card-text"><strong>Size:</strong> <?php echo implode(', ', $sizes); ?></p>
                                    <p class="card-text"><strong>Color:</strong> <?php echo implode(', ', $colors); ?></p>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<div class="col-12"><p>No products found</p></div>';
                }
                wp_reset_postdata();
            ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function enqueue_scripts() {
        wp_enqueue_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
        wp_enqueue_script('pfp-ajax', GKPF_URL. '/js/filter.js', array(), '1.0', true);
        wp_localize_script('pfp-ajax', 'pfp_ajax', array('ajax_url' => admin_url('admin-ajax.php')));
    }

    public function filter_products() {
        $size = isset($_POST['size']) ? sanitize_text_field($_POST['size']) : '';
        $color = isset($_POST['color']) ? sanitize_text_field($_POST['color']) : '';

        $tax_query = array('relation' => 'AND');
        if ($size) {
            $tax_query[] = array('taxonomy' => 'size', 'field' => 'slug', 'terms' => $size);
        }
        if ($color) {
            $tax_query[] = array('taxonomy' => 'color', 'field' => 'slug', 'terms' => $color);
        }

        $args = array(
            'post_type' => 'gkpf_product',
            'tax_query' => $tax_query,
            'posts_per_page' => -1,
        );

        $products = new WP_Query($args);
        if ($products->have_posts()) {
            while ($products->have_posts()) {
                $products->the_post();
                $sizes = wp_get_post_terms(get_the_ID(), 'size', array('fields' => 'names'));
                $colors = wp_get_post_terms(get_the_ID(), 'color', array('fields' => 'names'));
                $featured_image = has_post_thumbnail() ? get_the_post_thumbnail_url() : GKPF_URL. 'default-image.jpg';
                ?>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <img src="<?php echo esc_url($featured_image); ?>" class="card-img-top" alt="<?php the_title_attribute(); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php the_title(); ?></h5>
                            <p class="card-text"><?php the_excerpt(); ?></p>
                            <p class="card-text"><strong>Size:</strong> <?php echo implode(', ', $sizes); ?></p>
                            <p class="card-text"><strong>Color:</strong> <?php echo implode(', ', $colors); ?></p>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo '<div class="col-12"><p>No products found</p></div>';
        }
        wp_reset_postdata();
        wp_die();
    }
}
