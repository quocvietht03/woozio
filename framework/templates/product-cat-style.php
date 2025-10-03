<?php
$shop_page_url = get_permalink(wc_get_page_id('shop'));
$category_url = $shop_page_url . '?product_cat=' . $args['category']->slug;
?>
<div class="bt-product-category--item">
    <a class="bt-product-category--inner" href="<?php echo esc_url($category_url); ?>">
        <div class="bt-product-category--thumb">
            <div class="bt-cover-image">
                <?php
                $thumbnail_id = get_term_meta($args['category']->term_id, 'thumbnail_id', true);
                if ($thumbnail_id) {
                    echo wp_get_attachment_image($thumbnail_id, $args['image-size'], false);
                }else{
                    echo '<img src="' . esc_url(wc_placeholder_img_src('woocommerce_thumbnail')) . '" alt="' . esc_html__('Awaiting product image', 'woozio') . '" class="wp-post-image" />';
                }
                ?>
            </div>
        </div>
        <h3 class="bt-product-category--content">
            <span class="bt-product-category--name"><?php echo esc_html($args['category']->name); ?></span>
            <?php if (isset($args['show_count']) && $args['show_count'] === 'yes'): ?>
                <span class="bt-product-category--count"><?php echo sprintf(_n('%s item', '%s items', $args['category']->count, 'woozio'), $args['category']->count); ?></span>
            <?php endif; ?>
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                <path d="M19.2504 8V17.75C19.2504 17.9489 19.1714 18.1397 19.0307 18.2803C18.8901 18.421 18.6993 18.5 18.5004 18.5C18.3015 18.5 18.1107 18.421 17.9701 18.2803C17.8294 18.1397 17.7504 17.9489 17.7504 17.75V9.81031L7.03104 20.5306C6.89031 20.6714 6.69944 20.7504 6.50042 20.7504C6.30139 20.7504 6.11052 20.6714 5.96979 20.5306C5.82906 20.3899 5.75 20.199 5.75 20C5.75 19.801 5.82906 19.6101 5.96979 19.4694L16.6901 8.75H8.75042C8.5515 8.75 8.36074 8.67098 8.22009 8.53033C8.07943 8.38968 8.00042 8.19891 8.00042 8C8.00042 7.80109 8.07943 7.61032 8.22009 7.46967C8.36074 7.32902 8.5515 7.25 8.75042 7.25H18.5004C18.6993 7.25 18.8901 7.32902 19.0307 7.46967C19.1714 7.61032 19.2504 7.80109 19.2504 8Z" fill="currentColor" />
            </svg>
        </h3>
    </a>
</div>