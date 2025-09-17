<?php

namespace WoozioElementorWidgets\Widgets\ProductLoopItemStyle1;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

class Widget_ProductLoopItemStyle1 extends Widget_Base
{

	public function get_name()
	{
		return 'bt-product-loop-item-style-1';
	}

	public function get_title()
	{
		return __('Product Loop Item Style 1', 'woozio');
	}

	public function get_icon()
	{
		return 'eicon-posts-ticker';
	}

	public function get_categories()
	{
		return ['woozio'];
	}

	protected function register_layout_section_controls() {}

	protected function register_style_section_controls() {}

	protected function register_controls()
	{
		$this->register_layout_section_controls();
		$this->register_style_section_controls();
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();
		global $product;

		if (empty($product) || ! $product->is_visible()) {
			return;
		}
?>
		<div class="bt-elwg-product-loop-item--style-1">
			<div class="bt-product-item-minimal active"
				data-product-id="<?php echo esc_attr($product->get_id()); ?>">
				<div class="bt-product-thumbnail">
					<a href="<?php echo esc_url($product->get_permalink()); ?>">
						<?php
						if (has_post_thumbnail($product->get_id())) {
							echo get_the_post_thumbnail($product->get_id(), 'thumbnail');
						} else {
							echo '<img src="' . esc_url(wc_placeholder_img_src('woocommerce_thumbnail')) . '" alt="' . esc_html__('Awaiting product image', 'woozio') . '" class="wp-post-image" />';
						}
						?>
					</a>
				</div>
				<div class="bt-product-content">
					<h4 class="bt-product-title"><a href="<?php echo esc_url($product->get_permalink()); ?>" class="bt-product-link"><?php echo esc_html($product->get_name()); ?></a></h4>
					<div class="bt-product-price"><?php echo wp_kses_post($product->get_price_html()); ?></div>
					<div class="bt-product-add-to-cart">
						<?php if ($product->is_type('simple') && $product->is_purchasable() && $product->is_in_stock()) : ?>
							<a href="?add-to-cart=<?php echo esc_attr($product->get_id()); ?>" aria-describedby="woocommerce_loop_add_to_cart_link_describedby_<?php echo esc_attr($product->get_id()); ?>" data-quantity="1" class="bt-button product_type_simple add_to_cart_button ajax_add_to_cart bt-button-hover" data-product_id="<?php echo esc_attr($product->get_id()); ?>" data-product_sku="" rel="nofollow"><?php echo esc_html__('Add to cart', 'woozio') ?></a>
						<?php else : ?>
							<a href="<?php echo esc_url($product->get_permalink()); ?>" class="bt-button bt-view-product"><?php echo esc_html__('View Product', 'woozio'); ?></a>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
<?php
	}

	protected function content_template() {}
}
