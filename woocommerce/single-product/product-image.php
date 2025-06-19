<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.7.0
 */

defined('ABSPATH') || exit;

if (!function_exists('wc_get_gallery_image_html')) {
    return;
}

global $product;

// Setup variables
$columns = apply_filters('woocommerce_product_thumbnails_columns', 4);
$post_thumbnail_id = $product->get_image_id();
$full_size_image = wp_get_attachment_image_src($post_thumbnail_id, 'full');
$placeholder = has_post_thumbnail() ? 'with-images' : 'without-images';
$wrapper_classes = apply_filters('woocommerce_single_product_image_gallery_classes', [
    'woocommerce-product-gallery',
    'woocommerce-product-gallery--' . $placeholder,
    'woocommerce-product-gallery--columns-' . absint($columns),
    'images',
]);

// Helper function to generate image attributes
function get_image_attributes($attachment_id) {
    $full_size = wp_get_attachment_image_src($attachment_id, 'full');
    return [
        'title' => get_post_field('post_title', $attachment_id),
        'data-caption' => get_post_field('post_excerpt', $attachment_id),
        'data-src' => $full_size[0] ?? '',
        'data-large_image' => $full_size[0] ?? '',
        'data-large_image_width' => $full_size[1] ?? '',
        'data-large_image_height' => $full_size[2] ?? '',
    ];
}

// Helper function to generate image HTML
function generate_image_html($attachment_id, $size = 'shop_single', $zoom_class = false) {
    $thumbnail = wp_get_attachment_image_src($attachment_id, 'shop_thumbnail');
    $attributes = get_image_attributes($attachment_id);
    
    $html = '<div data-thumb="' . esc_url($thumbnail[0] ?? wc_placeholder_img_src()) . '" class="woocommerce-product-gallery__image' . ($zoom_class ? ' woocommerce-product-zoom__image' : '') . '">';
    $html .= wp_get_attachment_image($attachment_id, $size, false, $attributes);
    $html .= '</div>';
    
    return $html;
}
$attachment_ids = $product->get_gallery_image_ids();
?>
<div class="<?php echo esc_attr(implode(' ', array_map('sanitize_html_class', $wrapper_classes))); ?>" data-columns="<?php echo esc_attr($columns); ?>" style="opacity: 0; transition: opacity .25s ease-in-out;">
    <figure class="woocommerce-product-gallery__wrapper<?php echo (!empty($attachment_ids) && has_post_thumbnail()) ? ' bt-active-nav-gallery' : ''; ?>">
        <?php
        
        if (!empty($attachment_ids) && has_post_thumbnail()) {
            ?>
            <div class="woocommerce-product-gallery__slider">
                <?php
                echo apply_filters('woocommerce_single_product_image_thumbnail_html', generate_image_html($post_thumbnail_id, 'shop_single', true), $post_thumbnail_id);
                
                foreach ($attachment_ids as $attachment_id) {
                    echo apply_filters('woocommerce_single_product_image_thumbnail_html', generate_image_html($attachment_id, 'shop_single', true), $attachment_id);
                }
                ?>
            </div>

            <div class="woocommerce-product-gallery__slider-nav">
                <?php
                echo apply_filters('woocommerce_single_product_image_thumbnail_html', generate_image_html($post_thumbnail_id, 'shop_thumbnail'), $post_thumbnail_id);
                do_action('woocommerce_product_thumbnails');
                ?>
            </div>
            <?php
        } else {
            if (has_post_thumbnail()) {
                $html = generate_image_html($post_thumbnail_id, 'shop_single', true);
            } else {
                $html = '<div class="woocommerce-product-gallery__image--placeholder">';
                $html .= sprintf('<img src="%s" alt="%s" class="wp-post-image" />', esc_url(wc_placeholder_img_src()), esc_html__('Awaiting product image', 'woozio'));
                $html .= '</div>';
            }
            echo apply_filters('woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id);
        }
        ?>
    </figure>
</div>
