<?php
/* Register Sidebar */
if (!function_exists('woozio_register_sidebar')) {
	function woozio_register_sidebar()
	{
		register_sidebar(array(
			'name' => esc_html__('Main Sidebar', 'woozio'),
			'id' => 'main-sidebar',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="wg-title">',
			'after_title' => '</h4>',
		));
	}
	add_action('widgets_init', 'woozio_register_sidebar');
}

/* Add Support Upload Image Type SVG and 3D Models */
function woozio_mime_types($mimes)
{
	$mimes['svg'] = 'image/svg+xml';
	$mimes['glb'] = 'model/gltf-binary';
	$mimes['gltf'] = 'model/gltf+json';
	return $mimes;
}
add_filter('upload_mimes', 'woozio_mime_types');

/* Fix WordPress check filetype for GLB files */
function woozio_fix_mime_type_glb($data, $file, $filename, $mimes, $real_mime)
{
	if (!empty($data['ext']) && !empty($data['type'])) {
		return $data;
	}

	$wp_file_type = wp_check_filetype($filename, $mimes);

	// Check for GLB files
	if ($wp_file_type['ext'] === 'glb') {
		$data['ext'] = 'glb';
		$data['type'] = 'model/gltf-binary';
	}

	// Check for GLTF files
	if ($wp_file_type['ext'] === 'gltf') {
		$data['ext'] = 'gltf';
		$data['type'] = 'model/gltf+json';
	}

	return $data;
}
add_filter('wp_check_filetype_and_ext', 'woozio_fix_mime_type_glb', 10, 5);

/* Get icon SVG HTML */
function woozio_get_icon_svg_html($icon_file_name)
{

	if (!empty($icon_file_name)) {
		$file_path =  get_template_directory_uri() . '/assets/images/' . $icon_file_name . '.svg';
		$file = file_get_contents($file_path);
		if ($file !== false) {
			return $file;
		} else {
			return 'Error: File does not exist.';
		}
	} else {
		return 'Error: Invalid file name or file name is missing.';
	}
}

/* Enqueue Script */
if (!function_exists('woozio_enqueue_scripts')) {
	function woozio_enqueue_scripts()
	{
		wp_enqueue_style('woozio-fonts', get_template_directory_uri() . '/assets/css/fonts.css',  array(), false);

		if (is_archive('product')) {
			wp_enqueue_script('nouislider-script', get_template_directory_uri() . '/assets/libs/nouislider/nouislider.min.js', array('jquery'), '', true);
			wp_enqueue_style('nouislider-style', get_template_directory_uri() . '/assets/libs/nouislider/nouislider.min.css', array(), false);
		}
		if (class_exists('WooCommerce')) {
			wp_enqueue_script('wc-cart-fragments');
			wp_enqueue_script('wc-add-to-cart-variation');
			wp_enqueue_script('swiper-slider', get_template_directory_uri() . '/assets/libs/swiper/swiper.min.js', array('jquery'), '', true);
			wp_enqueue_style('swiper-slider', get_template_directory_uri() . '/assets/libs/swiper/swiper.min.css', array(), false);
			wp_enqueue_script('zoomable', get_template_directory_uri() . '/assets/libs/zoomable.js', array('jquery'), '', true);
			wp_enqueue_script('magnific-popup', get_template_directory_uri() . '/assets/libs/magnific-popup/jquery.magnific-popup.js', array('jquery'), '', true);
			wp_enqueue_style('magnific-popup', get_template_directory_uri() . '/assets/libs/magnific-popup/magnific-popup.css', array(), false);
		}

		if (is_singular('post') && comments_open() || is_singular('product')) {
			wp_enqueue_script('jquery-validate', get_template_directory_uri() . '/assets/libs/jquery-validate/jquery.validate.min.js', array('jquery'), '', true);
		}
		wp_enqueue_script('select2', get_template_directory_uri() . '/assets/libs/select2/select2.min.js', array('jquery'), '', true);
		wp_enqueue_style('select2', get_template_directory_uri() . '/assets/libs/select2/select2.min.css', array(), false);

		wp_enqueue_style('woozio-main', get_template_directory_uri() . '/assets/css/main.css',  array(), false);
		wp_enqueue_style('woozio-style', get_template_directory_uri() . '/style.css',  array(), false);
		wp_enqueue_script('woozio-main', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), '', true);
		if (function_exists('get_field')) {
			$dev_mode = get_field('dev_mode', 'options');
			/* Load custom style */
			$custom_style = '';

			$custom_style = get_field('custom_css_code', 'options');
			if ($dev_mode && !empty($custom_style)) {
				wp_add_inline_style('woozio-style', $custom_style);
			}

			/* Custom script */
			$custom_script = '';
			$custom_script = get_field('custom_js_code', 'options');
			if ($dev_mode && !empty($custom_script)) {
				wp_add_inline_script('woozio-main', $custom_script);
			}
		}
		// Get cart URL
		$cart_url = function_exists('wc_get_cart_url') ? wc_get_cart_url() : '';
		// Get shop URL
		$shop_url = home_url('/shop/');
		if (function_exists('wc_get_page_id')) {
			$shop_page_id = wc_get_page_id('shop');
			if ($shop_page_id > 0) {
				$shop_url = get_permalink($shop_page_id);
			}
		}
		$wishlist_toast = $compare_toast = $cart_toast = '';

		if (function_exists('get_field')) {
			$archive_shop = get_field('archive_shop', 'options');

			if (is_array($archive_shop)) {
				$wishlist_toast = isset($archive_shop['wishlist_toast']) ? $archive_shop['wishlist_toast'] : '';
				$wishlist_toast_time = isset($archive_shop['time_show_wishlist']) ? $archive_shop['time_show_wishlist'] : 3000;
				$compare_toast = isset($archive_shop['compare_toast']) ? $archive_shop['compare_toast'] : '';
				$compare_toast_time = isset($archive_shop['time_show_compare']) ? $archive_shop['time_show_compare'] : 3000;
				$cart_toast = isset($archive_shop['cart_toast']) ? $archive_shop['cart_toast'] : '';
				$cart_toast_time = isset($archive_shop['time_show_cart']) ? $archive_shop['time_show_cart'] : 3000;
			}
		}
		/* Options to script */
		$js_options = array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'shop' => $shop_url,
			'cart' => $cart_url,
			'wishlist_toast' => $wishlist_toast,
			'compare_toast' => $compare_toast,
			'cart_toast' => $cart_toast,
			'wishlist_toast_time' => $wishlist_toast_time,
			'compare_toast_time' => $compare_toast_time,
			'cart_toast_time' => $cart_toast_time,
			'user_info' => wp_get_current_user(),
		);

		wp_localize_script('woozio-main', 'AJ_Options', $js_options);
		wp_enqueue_script('woozio-main');
	}
	add_action('wp_enqueue_scripts', 'woozio_enqueue_scripts');
}
/* Add Stylesheet And Script Backend */
if (!function_exists('woozio_enqueue_admin_scripts')) {
	function woozio_enqueue_admin_scripts($hook)
	{
		wp_enqueue_style('woozio-fonts', get_template_directory_uri() . '/assets/css/fonts.css',  array(), false);
		wp_enqueue_script('woozio-admin-main', get_template_directory_uri() . '/assets/js/admin-main.js', array('jquery'), '', true);
		wp_enqueue_style('woozio-admin-main', get_template_directory_uri() . '/assets/css/admin-main.css', array(), false);
		
		// Localize script for Product Extra Content (in admin-main.js)
		$screen = get_current_screen();
		if ($screen && ($screen->post_type === 'product' || $screen->post_type === 'extra_content_prod')) {
			wp_localize_script('woozio-admin-main', 'woozioExtraContent', array(
				'nonce' => wp_create_nonce('woozio-extra-content-nonce'),
				'ajaxUrl' => admin_url('admin-ajax.php'),
			));
		}
	}
	add_action('admin_enqueue_scripts', 'woozio_enqueue_admin_scripts');
}

/**
 * Theme install
 */
require_once get_template_directory() . '/install/plugin-required.php';
require_once get_template_directory() . '/install/import-pack/import-functions.php';

/* ACF Options */
require_once get_template_directory() . '/framework/acf-options.php';

/* Template functions */
require_once get_template_directory() . '/framework/template-helper.php';

/* Post Functions */
require_once get_template_directory() . '/framework/templates/post-helper.php';

/* Block Load */
require_once get_template_directory() . '/framework/block-load.php';

/* Widgets Load */
require_once get_template_directory() . '/framework/widget-load.php';

/* Cron Functions */
require_once get_template_directory() . '/framework/cron-helper.php';

/* Woocommerce functions */
if (class_exists('Woocommerce')) {
	require_once get_template_directory() . '/woocommerce/shop-helper.php';
}

/* Product Extra Content */
require_once get_template_directory() . '/framework/product-extra-content.php';

if (function_exists('get_field')) {

	function woozio_body_class($classes)
	{
		$effect_load_heading = get_field('effect_load_heading', 'options');
		$button_hover = get_field('effect_button_hover', 'options');
		if ($effect_load_heading) {
			$classes[] = 'bt-effect-heading-enable';
		}
		if ($button_hover) {
			$classes[] = 'bt-button-hover-enable';
		}
		return $classes;
	}
	add_filter('body_class', 'woozio_body_class');
}

/* Custom search posts */
function bt_custom_search_filter($query)
{
	if ($query->is_search() && !is_admin()) {
		if (!is_post_type_archive('product') && !is_tax('product_cat') && !is_singular('product') && !is_page_template('woocommerce/template-nosidebar-dropdown.php') && !is_page_template('woocommerce/template-nosidebar-popup.php') && !is_page_template('woocommerce/template-sidebar.php')) {
			$query->set('post_type', 'post');
		}
	}
}
add_action('pre_get_posts', 'bt_custom_search_filter');

