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

use Automattic\WooCommerce\Enums\ProductType;

if (!function_exists('wc_get_gallery_image_html')) {
    return;
}

global $product;

// Check if we're in quick view mode
// Note: In quick view, always use 'bottom-thumbnail' layout for better UX in the popup
$is_quick_view = (isset($_REQUEST['action']) && $_REQUEST['action'] === 'woozio_products_quick_view');

if ($is_quick_view) {
    // Force bottom-thumbnail layout for quick view
    $product_layout = 'bottom-thumbnail';
} else {
    // Use product meta or URL parameter for regular product pages
    $product_layout = get_post_meta($product->get_id(), '_layout_product', true);
    if(isset($_GET['layout']) && !empty($_GET['layout'])) {
        $product_layout = sanitize_text_field($_GET['layout']);
    }
}
$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$post_thumbnail_id = $product->get_image_id();
$wrapper_classes   = apply_filters(
	'woocommerce_single_product_image_gallery_classes',
	array(
		'woocommerce-product-gallery',
		'woocommerce-product-gallery--' . ( $post_thumbnail_id ? 'with-images' : 'without-images' ),
		'woocommerce-product-gallery--columns-' . absint( $columns ),
		'images',
		'bt-' . $product_layout
	)
);

$attachment_ids = $product->get_gallery_image_ids();

?>
<div class="<?php echo esc_attr(implode(' ', array_map('sanitize_html_class', $wrapper_classes))); ?>" style="opacity: 0; transition: opacity .25s ease-in-out;">
    <?php
    // Only show video and 360 buttons on single product pages
    if (is_product()) {
        // Product Video Button and Popup
        $video_type = get_post_meta($product->get_id(), '_product_video_type', true);
        $video_link = get_post_meta($product->get_id(), '_product_video_link', true);
        
        if (!empty($video_link) && function_exists('woozio_get_product_video_embed')) {
            $video_embed = woozio_get_product_video_embed($video_type, $video_link);
            
            if (!empty($video_embed)) {
                ?>
                <div class="bt-button-product-video">
                    <!-- Popup Container -->
                    <div id="bt_product_video" class="bt-product-video__popup mfp-content__popup mfp-hide">
                        <div class="bt-product-video__content mfp-content__inner">
                            <?php echo $video_embed; ?>
                        </div>
                    </div>
                    
                    <!-- Video Button Trigger -->
                    <a href="#bt_product_video" class="bt-product-video__link bt-js-open-popup-link" title="<?php echo esc_attr__('Watch Product Video', 'woozio'); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M9.5 8.5L15.5 12L9.5 15.5V8.5Z" fill="currentColor" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                        </svg>
                        <span><?php echo esc_html__('Watch Video', 'woozio'); ?></span>
                    </a>
                </div>
                <?php
            }
        }
        
        // Product 360 Button and Popup
        $product_360_file = get_post_meta($product->get_id(), '_product_360_images', true);
        
        if (!empty($product_360_file)) {
            $file_url = wp_get_attachment_url($product_360_file);
            
            if ($file_url) {
                ?>
                <!-- Load model-viewer as ES6 module -->
                <script type="module" src="<?php echo get_template_directory_uri(); ?>/assets/libs/model-viewer/model-viewer.min.js"></script>
                
                <div class="bt-button-product-360">
                    <!-- Popup Container -->
                    <div id="bt_product_360" class="bt-product-360__popup mfp-content__popup mfp-hide">
                        <div class="bt-product-360__content mfp-content__inner">
                            <model-viewer 
                                src="<?php echo esc_url($file_url); ?>"
                                alt="<?php echo esc_attr($product->get_name()); ?> 360° View"
                                auto-rotate
                                camera-controls
                                shadow-intensity="1"
                                style="width: 100%; height: 600px;">
                            </model-viewer>
                        </div>
                    </div>
                    
                    <!-- 360 Button Trigger -->
                    <a href="#bt_product_360" class="bt-product-360__link bt-js-open-popup-link" title="<?php echo esc_attr__('View 360°', 'woozio'); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M12 2C8.13 2 5 3.79 5 6V18C5 20.21 8.13 22 12 22C15.87 22 19 20.21 19 18V6C19 3.79 15.87 2 12 2Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M5 6C5 8.21 8.13 10 12 10C15.87 10 19 8.21 19 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 14V18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        <span><?php echo esc_html__('View 360°', 'woozio'); ?></span>
                    </a>
                </div>
                <?php
            }
        }
    }
    ?>
<div class="woocommerce-product-gallery__wrapper<?php echo (!empty($attachment_ids) && has_post_thumbnail()) ? ' bt-has-slide-thumbs' : ''; ?>">
        <?php
        
        if ( $post_thumbnail_id ) {
            ?>
            <div class="woocommerce-product-gallery__slider bt-gallery-lightbox bt-gallery-zoomable">
                <div class="swiper-wrapper">
                <?php
                    $html = woozio_get_gallery_image_html( $post_thumbnail_id, true, true );

                    if(!empty($attachment_ids)) {
                        foreach ( $attachment_ids as $key => $attachment_id ) {
                            $html .= woozio_get_gallery_image_html( $attachment_id, true, true );
                        }
                    }
                    echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id ); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
                ?>
                </div>
                
                <div class="swiper-button-prev"><svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.4995 10.0003C17.4995 10.1661 17.4337 10.3251 17.3165 10.4423C17.1992 10.5595 17.0403 10.6253 16.8745 10.6253H4.63311L9.1917 15.1832C9.24977 15.2412 9.29583 15.3102 9.32726 15.386C9.35869 15.4619 9.37486 15.5432 9.37486 15.6253C9.37486 15.7075 9.35869 15.7888 9.32726 15.8647C9.29583 15.9405 9.24977 16.0095 9.1917 16.0675C9.13363 16.1256 9.0647 16.1717 8.98882 16.2031C8.91295 16.2345 8.83164 16.2507 8.74951 16.2507C8.66739 16.2507 8.58607 16.2345 8.5102 16.2031C8.43433 16.1717 8.3654 16.1256 8.30733 16.0675L2.68233 10.4425C2.62422 10.3845 2.57812 10.3156 2.54667 10.2397C2.51521 10.1638 2.49902 10.0825 2.49902 10.0003C2.49902 9.91821 2.51521 9.83688 2.54667 9.76101C2.57812 9.68514 2.62422 9.61621 2.68233 9.55816L8.30733 3.93316C8.4246 3.81588 8.58366 3.75 8.74951 3.75C8.91537 3.75 9.07443 3.81588 9.1917 3.93316C9.30898 4.05044 9.37486 4.2095 9.37486 4.37535C9.37486 4.5412 9.30898 4.70026 9.1917 4.81753L4.63311 9.37535H16.8745C17.0403 9.37535 17.1992 9.4412 17.3165 9.55841C17.4337 9.67562 17.4995 9.83459 17.4995 10.0003Z"/>
                </svg></div>
                <div class="swiper-button-next"><svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.3172 10.4425L11.6922 16.0675C11.5749 16.1848 11.4159 16.2507 11.25 16.2507C11.0841 16.2507 10.9251 16.1848 10.8078 16.0675C10.6905 15.9503 10.6247 15.7912 10.6247 15.6253C10.6247 15.4595 10.6905 15.3004 10.8078 15.1832L15.3664 10.6253H3.125C2.95924 10.6253 2.80027 10.5595 2.68306 10.4423C2.56585 10.3251 2.5 10.1661 2.5 10.0003C2.5 9.83459 2.56585 9.67562 2.68306 9.55841C2.80027 9.4412 2.95924 9.37535 3.125 9.37535H15.3664L10.8078 4.81753C10.6905 4.70026 10.6247 4.5412 10.6247 4.37535C10.6247 4.2095 10.6905 4.05044 10.8078 3.93316C10.9251 3.81588 11.0841 3.75 11.25 3.75C11.4159 3.75 11.5749 3.81588 11.6922 3.93316L17.3172 9.55816C17.3753 9.61621 17.4214 9.68514 17.4528 9.76101C17.4843 9.83688 17.5005 9.91821 17.5005 10.0003C17.5005 10.0825 17.4843 10.1638 17.4528 10.2397C17.4214 10.3156 17.3753 10.3845 17.3172 10.4425Z"/>
                </svg></div>
            </div>

            <div class="woocommerce-product-gallery__slider-thumbs">
                <div class="swiper-wrapper">
                    <?php
                        $html = woozio_get_gallery_image_html( $post_thumbnail_id, false, true );
                        echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id ); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
                        do_action('woocommerce_product_thumbnails');
                    ?>
                </div>
            </div>
            <?php
        } else {
            $wrapper_classname = $product->is_type( ProductType::VARIABLE ) && ! empty( $product->get_available_variations( 'image' ) ) ?
				'woocommerce-product-gallery__image woocommerce-product-gallery__image--placeholder' :
				'woocommerce-product-gallery__image--placeholder';
                $html = sprintf( '<div class="%s">', esc_attr( $wrapper_classname ) );
                $html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ), esc_html__( 'Awaiting product image', 'woozio' ) );
                $html .= '</div>';

            echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id ); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
        }
        ?>
    </div>
</div>
