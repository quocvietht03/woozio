<?php
defined('ABSPATH') || exit;

global $product;
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class('bt-' . $args['layout'], $product); ?>>
    <div class="bt-product-inner">
        <?php

        /**
         * Hook: woocommerce_before_single_product_summary.
         *
         * @hooked woocommerce_show_product_sale_flash - 10
         * @hooked woocommerce_show_product_images - 20
         */
        //   do_action('woocommerce_before_single_product_summary');

        $attachment_ids = $product->get_gallery_image_ids();
        $featured_image_id = $product->get_image_id();
        $itemgallery = count($attachment_ids) + 1;

        if ($args['layout'] === 'gallery-two-column') {
            $show_number = 6;
        } elseif ($args['layout'] === 'gallery-stacked') {
            $show_number = 5;
        } else {
            $show_number = 3;
        }

        ?>
        <div class="images bt-gallery-products" data-items="<?php echo esc_attr($itemgallery); ?>" data-shown="<?php echo esc_attr($show_number); ?>">
            
            <div class="bt-gallery-product bt-gallery-lightbox bt-gallery-zoomable">
                <?php
                if ($featured_image_id) {
                    $image_url = wp_get_attachment_image_url($featured_image_id, 'full');
                    echo '<div data-thumb="' . esc_url($image_url) . '" class="bt-gallery-product--image">';
                    echo '<div class="bt-cover-image zoomable">';
                    echo wp_get_attachment_image($featured_image_id, 'full', false, array(
                        'class' => 'wp-post-image',
                        'title' => get_post_field('post_title', $featured_image_id),
                        'alt' => get_post_meta($featured_image_id, '_wp_attachment_image_alt', true)
                    ));
                    echo '</div>';
                    echo '</div>';
                }

                if ($attachment_ids) {
                    foreach ($attachment_ids as $index => $attachment_id) {
                        $image_url = wp_get_attachment_image_url($attachment_id, 'full');
                        echo '<div data-thumb="' . esc_url($image_url) . '" class="bt-gallery-product--image">';
                        echo '<div class="bt-cover-image zoomable">';
                        echo wp_get_attachment_image($attachment_id, 'full', false, array(
                            'class' => 'gallery-image',
                            'title' => get_post_field('post_title', $attachment_id),
                            'alt' => get_post_meta($attachment_id, '_wp_attachment_image_alt', true)
                        ));
                        echo '</div>';
                        echo '</div>';
                    }
                }
                ?>
            </div>
            <?php
            echo '<button class="bt-show-more">' . esc_html__('Show More', 'woozio') . '</button>';
            ?>
        </div>
        <div class="summary entry-summary">
            <div class="woocommerce-product-rating-sold">
                <?php
                do_action('woozio_woocommerce_shop_loop_item_label');
                do_action('woozio_woocommerce_template_single_rating');
                ?>
            </div>
            <?php
            do_action('woozio_woocommerce_template_single_title');
            ?>
            <div class="woocommerce-product-price-wrap">
                <?php
                do_action('woozio_woocommerce_template_single_price');
                do_action('woozio_woocommerce_show_product_loop_sale_flash');

                ?>
            </div>
            <div class="bt-product-excerpt-add-to-cart">
                <?php
                do_action('woozio_woocommerce_template_single_excerpt');
                do_action('woozio_woocommerce_template_single_countdown'); 
                do_action('woozio_woocommerce_template_single_add_to_cart');
                do_action('woozio_woocommerce_template_single_more_information');
                ?>
            </div>
            <?php 
            do_action('woozio_woocommerce_template_single_meta');
            do_action('woozio_woocommerce_template_upsell_products');
            do_action('woozio_woocommerce_template_single_safe_checkout');
            do_action('woozio_woocommerce_template_single_toggle');
            ?>

        </div>
    </div>

    <?php

    /**
     * Hook: woozio_woocommerce_template_related_products.
     *
     * @hooked woocommerce_output_related_products - 20
     */
    if (function_exists('get_field')) {
        $enable_related_product = get_field('enable_related_product', 'options');
        if ($enable_related_product) {
            do_action('woozio_woocommerce_template_related_products');
        }
    }
    ?>


</div>