<?php

namespace WoozioElementorWidgets;

/**
 * Class ElementorWidgets
 *
 * Main ElementorWidgets class
 * @since 1.0.0
 */
class ElementorWidgets
{

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 * @access private
	 * @static
	 *
	 * @var ElementorWidgets The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return ElementorWidgets An instance of the class.
	 */
	public static function instance()
	{
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public $widgets = array();

	public function widgets_list()
	{

		$this->widgets = array(
			'site-information',
			'site-information-style-1',
			'site-social',
			'site-copyright',
			'site-notification',
			'instagram-posts',
			'banner-product-slider',
			'page-breadcrumb',
			'post-loop-item',
			'post-loop-item-style-1',
			'post-loop-item-style-2',
			'post-loop-item-style-3',
			'product-loop-item',
			'product-loop-item-style-1',
			'product-loop-item-style-2',
			'product-category-loop-item',
			'product-wishlist',
			'product-compare',
			'highlighted-heading',
			'mini-cart',
			'list-faq',
			'account-login',
			'search-product',
			'product-category',
			'product-category-style-1',
			'product-brand',
			'product-item',
			'product-showcase',
			'heading-animation',
			'mini-wishlist',
			'tiktok-shop-slider',
			'product-tooltip-hotspot',
			'product-list-hotspot',
			'product-overlay-hotspot',
			'product-slider-bottom-hotspot',
			'product-testimonial',
			'product-testimonial-slider',
			'testimonial-slider',
			'countdown',
			'our-teams',
			'our-store',
			'currency-switcher',
			'language-switcher',
			'accordion-with-product-slider',
			'collection-banner',
			'site-icon-payment',
			'text-slider',
			'store-locations-slider',
			'product-nav-image'
		);

		return $this->widgets;
	}

	/**
	 * widget_styles
	 *
	 * Load required core files.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function widget_styles()
	{
		wp_enqueue_style('swiper-slider', get_template_directory_uri() . '/assets/libs/swiper/swiper.min.css', array(), false);
		wp_enqueue_style('magnific-popup', get_template_directory_uri() . '/assets/libs/magnific-popup/magnific-popup.css', array(), false);
	}

	/**
	 * widget_scripts
	 *
	 * Load required core files.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function widget_scripts()
	{
		wp_register_script('swiper-slider', get_template_directory_uri() . '/assets/libs/swiper/swiper.min.js', array('jquery'), '', true);
		wp_register_script('elementor-widgets',  get_stylesheet_directory_uri() . '/framework/widgets/frontend.js', ['jquery'], '', true);
		wp_register_script('magnific-popup', get_template_directory_uri() . '/assets/libs/magnific-popup/jquery.magnific-popup.js', array('jquery'), '', true);
	}

	/**
	 * Include Widgets files
	 *
	 * Load widgets files
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function include_widgets_files()
	{

		foreach ($this->widgets_list() as $widget) {
			require_once(get_stylesheet_directory() . '/framework/widgets/' . $widget . '/widget.php');

			foreach (glob(get_stylesheet_directory() . '/framework/widgets/' . $widget . '/skins/*.php') as $filepath) {
				include $filepath;
			}
		}
	}

	/**
	 * Register categories
	 *
	 * Register new Elementor category.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register_categories($elements_manager)
	{

		$elements_manager->add_category(
			'woozio',
			[
				'title' => esc_html__('Woozio', 'woozio')
			]
		);
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register_widgets()
	{
		// Its is now safe to include Widgets files
		$this->include_widgets_files();

		// Register Widgets
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\SiteInformation\Widget_SiteInformation());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\SiteInformationStyle1\Widget_SiteInformationStyle1());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\SiteNotification\Widget_SiteNotification());

		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\SiteSocial\Widget_SiteSocial());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\SiteCopyright\Widget_SiteCopyright());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\InstagramPosts\Widget_InstagramPosts());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\BannerProductSlider\Widget_BannerProductSlider());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\PageBreadcrumb\Widget_PageBreadcrumb());

		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\PostLoopItem\Widget_PostLoopItem());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\PostLoopItemStyle1\Widget_PostLoopItemStyle1());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\PostLoopItemStyle2\Widget_PostLoopItemStyle2());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\PostLoopItemStyle3\Widget_PostLoopItemStyle3());

		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\ProductLoopItem\Widget_ProductLoopItem());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\ProductLoopItemStyle1\Widget_ProductLoopItemStyle1());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\ProductLoopItemStyle2\Widget_ProductLoopItemStyle2());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\ProductCategoryLoopItem\Widget_ProductCategoryLoopItem());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\ProductWishlist\Widget_ProductWishlist());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\ProductCompare\Widget_ProductCompare());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\AccountLogin\Widget_AccountLogin());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\SearchProduct\Widget_SearchProduct());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\ProductCategory\Widget_ProductCategory());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\ProductCategoryStyle1\Widget_ProductCategoryStyle1());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\ProductBrand\Widget_ProductBrand());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\ProductItem\Widget_ProductItem());

		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\HighlightedHeading\Widget_HighlightedHeading());

		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\MiniCart\Widget_MiniCart());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\ListFaq\Widget_ListFaq());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\HeadingAnimation\Widget_HeadingAnimation());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\MiniWishlist\Widget_MiniWishlist());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\TikTokShopSlider\Widget_TikTokShopSlider());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\ProductTooltipHotspot\Widget_ProductTooltipHotspot());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\ProductListHotspot\Widget_ProductListHotspot());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\ProductSliderBottomHotspot\Widget_ProductSliderBottomHotspot());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\ProductTestimonial\Widget_ProductTestimonial());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\ProductTestimonialSlider\Widget_ProductTestimonialSlider());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\TestimonialSlider\Widget_TestimonialSlider());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\CountDown\Widget_CountDown());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\OurTeams\Widget_OurTeams());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\OurStore\Widget_OurStore());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\CurrencySwitcher\Widget_CurrencySwitcher());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\LanguageSwitcher\Widget_LanguageSwitcher());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\AccordionWithProductSlider\Widget_AccordionWithProductSlider());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\CollectionBanner\Widget_CollectionBanner());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\ProductOverlayHotspot\Widget_ProductOverlayHotspot());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\IconPayment\Widget_IconPayment());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\TextSlider\Woozio_TextSlider());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\ProductShowcase\Widget_ProductShowcase());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\StoreLocationsSlider\Widget_StoreLocationsSlider());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\ProductNavImage\Widget_ProductNavImage());
	}

	/**
	 *  ElementorWidgets class constructor
	 *
	 * Register action hooks and filters
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct()
	{

		// Register widget styles
		add_action('elementor/frontend/after_register_styles', [$this, 'widget_styles']);

		// Register widget scripts
		add_action('elementor/frontend/after_register_scripts', [$this, 'widget_scripts']);

		// Register categories
		add_action('elementor/elements/categories_registered', [$this, 'register_categories']);

		// Register widgets
		add_action('elementor/widgets/widgets_registered', [$this, 'register_widgets']);
	}
}

// Instantiate ElementorWidgets Class
ElementorWidgets::instance();
