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
		<div class="bt-elwg-product-loop-item-style-1">
			<div class="bt-product">
				<div class="bt-product--image">
					<a href="<?php echo esc_url(get_permalink()); ?>">
						<?php echo woocommerce_get_product_thumbnail(); ?>
					</a>
				</div>
				<div class="bt-product--content">
					<h3 class="bt-product--title">
						<a href="<?php echo esc_url(get_permalink()); ?>">
							<?php echo get_the_title(); ?>
						</a>
					</h3>
					<div class="bt-product--infor">
						<div class="bt-product--info">
							<?php if ($product->get_price_html()) : ?>
								<?php echo '<div class="bt-product--price">' . $product->get_price_html() . '</div>'; ?>
							<?php endif; ?>
							<?php if ($product->get_average_rating()) :
								do_action('woozio_woocommerce_template_loop_rating');
							endif; ?>

						</div>
						<div class="bt-product--add-to-cart">
							<?php
							woocommerce_template_loop_add_to_cart();
							?>
						</div>
					</div>

				</div>
			</div>
		</div>
<?php
	}

	protected function content_template() {}
}
