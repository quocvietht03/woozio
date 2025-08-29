<?php

namespace WoozioElementorWidgets\Widgets\ProductShowcase;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

class Widget_ProductShowcase extends Widget_Base
{

	public function get_name()
	{
		return 'bt-product-showcase';
	}

	public function get_title()
	{
		return __('Product Showcase', 'woozio');
	}

	public function get_icon()
	{
		return 'eicon-posts-ticker';
	}

	public function get_categories()
	{
		return ['woozio'];
	}

	public function get_script_depends()
	{
		return ['swiper-slider', 'elementor-widgets'];
	}

	public function get_supported_products()
	{
		$supported_products = [];

		$args = array(
			'post_type' => 'product',
			'posts_per_page' => -1,
			'post_status' => 'publish'
		);

		$products = get_posts($args);

		if (!empty($products)) {
			foreach ($products as $product) {
				$supported_products[$product->ID] = $product->post_title;
			}
		}

		return $supported_products;
	}
	protected function register_layout_section_controls()
	{
		$this->start_controls_section(
			'section_layout',
			[
				'label' => __('Content', 'woozio'),
			]
		);
		$this->add_control(
			'products',
			[
				'label' => __('Select Product', 'woozio'),
				'type' => Controls_Manager::SELECT2,
				'options' => $this->get_supported_products(),
				'label_block' => true,
				'multiple' => false,
			]
		);
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail',
				'label' => __('Image Size', 'woozio'),
				'show_label' => true,
				'default' => 'medium',
				'exclude' => ['custom'],
			]
		);

		$this->add_responsive_control(
			'image_ratio',
			[
				'label' => __('Image Ratio', 'woozio'),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'min' => 0.3,
						'max' => 2,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bt-product-showcase--item-image .bt-cover-image' => 'padding-bottom: calc( {{SIZE}} * 100% );',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_section_controls()
	{
		$this->start_controls_section(
			'section_style_image',
			[
				'label' => esc_html__('Image', 'woozio'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'img_border_radius',
			[
				'label' => __('Border Radius', 'woozio'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .bt-product-showcase--item-image .bt-cover-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs('thumbnail_effects_tabs');

		$this->start_controls_tab(
			'thumbnail_tab_normal',
			[
				'label' => __('Normal', 'woozio'),
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'thumbnail_filters',
				'selector' => '{{WRAPPER}} .bt-product-showcase--item-image img',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'thumbnail_tab_hover',
			[
				'label' => __('Hover', 'woozio'),
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'thumbnail_hover_filters',
				'selector' => '{{WRAPPER}} .bt-product-showcase--item-image:hover img',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__('Content', 'woozio'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'product_content_bg_color',
			[
				'label' => __('Content Background Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-product-showcase--item-content' => 'background-color: {{VALUE}};',
				],
			]
		);

		// Typography & Color for Product Title
		$this->add_control(
			'product_title_style_heading',
			[
				'label' => esc_html__('Product Title', 'woozio'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'product_title_typography',
				'label' => esc_html__('Typography', 'woozio'),
				'selector' => '{{WRAPPER}} .bt-product-showcase--item-content .bt-product--title',
			]
		);

		$this->add_control(
			'product_title_color',
			[
				'label' => esc_html__('Color', 'woozio'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-product-showcase--item-content .bt-product--title' => 'color: {{VALUE}};',
				],
			]
		);

		// Typography & Color for Product Price
		$this->add_control(
			'product_price_style_heading',
			[
				'label' => esc_html__('Product Price', 'woozio'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'product_price_typography',
				'label' => esc_html__('Typography', 'woozio'),
				'selector' => '{{WRAPPER}} .bt-product-showcase--item-content .bt-product--price, {{WRAPPER}} .bt-product-showcase--item-content .bt-product--price .amount',
			]
		);

		$this->add_control(
			'product_price_color',
			[
				'label' => __('Price Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-product-showcase--item-content .bt-product--price .woocommerce-Price-amount' => 'color: {{VALUE}};',
					'{{WRAPPER}} .bt-product-showcase--item-content .bt-product--price' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'product_regular_price_color',
			[
				'label' => __('Regular Price Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-product-showcase--item-content .bt-product--price del .woocommerce-Price-amount' => 'color: {{VALUE}};',
				],
			]
		);
		// Typography & Color for Product Category
		$this->add_control(
			'product_category_style_heading',
			[
				'label' => esc_html__('Product Category', 'woozio'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'product_category_typography',
				'label' => esc_html__('Typography', 'woozio'),
				'selector' => '{{WRAPPER}} .bt-product-showcase--item-content .bt-product--category',
			]
		);

		$this->add_control(
			'product_category_color',
			[
				'label' => esc_html__('Color', 'woozio'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-product-showcase--item-content .bt-product--category' => 'color: {{VALUE}};',
				],
			]
		);

		// Typography & Color for Product Short Description
		$this->add_control(
			'product_desc_style_heading',
			[
				'label' => esc_html__('Product Short Description', 'woozio'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'product_desc_typography',
				'label' => esc_html__('Typography', 'woozio'),
				'selector' => '{{WRAPPER}} .bt-product-showcase--item-content .bt-product--short-description',
			]
		);

		$this->add_control(
			'product_desc_color',
			[
				'label' => esc_html__('Color', 'woozio'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-product-showcase--item-content .bt-product--short-description' => 'color: {{VALUE}};',
				],
			]
		);

		// Typography & Color for Add to Cart Button
		$this->add_control(
			'add_to_cart_style_heading',
			[
				'label' => esc_html__('Add to Cart Button', 'woozio'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'add_to_cart_typography',
				'label' => esc_html__('Typography', 'woozio'),
				'selector' => '{{WRAPPER}} .bt-product-showcase--item-content .bt-btn-add-to-cart, {{WRAPPER}} .bt-product-showcase--item-content .bt-btn-add-to-cart-variable',
			]
		);

		$this->add_control(
			'add_to_cart_text_color',
			[
				'label' => esc_html__('Text Color', 'woozio'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-product-showcase--item-content .bt-btn-add-to-cart, {{WRAPPER}} .bt-product-showcase--item-content .bt-btn-add-to-cart-variable' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'add_to_cart_bg_color',
			[
				'label' => esc_html__('Background Color', 'woozio'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-product-showcase--item-content .bt-btn-add-to-cart, {{WRAPPER}} .bt-product-showcase--item-content .bt-btn-add-to-cart-variable' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();
	}
	protected function register_controls()
	{
		$this->register_layout_section_controls();
		$this->register_style_section_controls();
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();

		$products = $settings['products'];
		$thumbnail_size = $settings['thumbnail_size'];
		if (empty($products)) {
			return;
		}
		$args = array(
			'post_type' => 'product',
			'p' => $products,
			'posts_per_page' => -1,
			'post_status' => 'publish',
			'orderby' => 'post__in',
		)
?>
		<div class="bt-elwg-product-showcase--default">
			<?php
			$query = new \WP_Query($args);
			if ($query->have_posts()) :
				while ($query->have_posts()) : $query->the_post();
					global $product;

					if (empty($product) || ! $product->is_visible()) {
						continue;
					}

					$product_id = $product->get_id();
					$product_name = $product->get_name();
					$product_link = get_permalink($product_id);

					// 1. Get thumbnail image
					$product_thumbnail = get_the_post_thumbnail($product_id, $thumbnail_size);

					// 2. Get first image from product gallery, fallback to thumbnail if gallery is empty
					$gallery_image_html = '';
					$gallery_image_ids = $product->get_gallery_image_ids();

					if (!empty($gallery_image_ids)) {
						$first_gallery_image_id = $gallery_image_ids[0];
						$gallery_image_html = wp_get_attachment_image($first_gallery_image_id, $thumbnail_size);
					} else {
						$gallery_image_html = $product_thumbnail;
					}

			?>
					<div class="bt-product-showcase">
						<div class="bt-col-product bt-product-showcase--item-content">
							<div class="bt-product--category">
								<?php
								$categories = get_the_terms($product_id, 'product_cat');
								if ($categories && !is_wp_error($categories)) {
									$cat_links = array();
									foreach ($categories as $category) {
										$cat_links[] = '<a href="' . esc_url(get_term_link($category)) . '">' . esc_html($category->name) . '</a>';
									}
									echo implode(', ', $cat_links);
								}
								?>
							</div>
							<h3 class="bt-product--title">
								<a href="<?php echo esc_url($product_link); ?>">
									<?php echo esc_html($product_name); ?>
								</a>
							</h3>
							<div class="bt-product--infor">
								<div class="bt-product--info">
									<?php if ($product->get_price_html()) : ?>
										<div class="bt-product--price"><?php echo $product->get_price_html(); ?></div>
									<?php endif; ?>
									<?php do_action('woozio_woocommerce_show_product_loop_sale_flash'); ?>
								</div>
								<?php if ($short_description = $product->get_short_description()) : ?>
									<div class="bt-product--short-description">
										<?php echo wp_kses_post($short_description); ?>
									</div>
								<?php endif; ?>
								<div class="bt-product--add-to-cart">
									<?php
									if (!$product->is_type('variable')) {
										do_action('woozio_woocommerce_template_loop_add_to_cart');
									} else {
										do_action('woozio_woocommerce_template_loop_add_to_cart_variable');
									}
									?>
								</div>
							</div>
						</div>
						<div class="bt-col-product bt-product-showcase--item-image">
							<div class="bt-cover-image">
								<?php echo $product_thumbnail; ?>
							</div>
						</div>
						<div class="bt-col-product bt-product-showcase--item-image">
							<div class="bt-cover-image">
								<?php echo $gallery_image_html; ?>
							</div>
						</div>
					</div>
			<?php
				endwhile;
				wp_reset_postdata();
			endif;
			?>
		</div>
<?php
	}

	protected function content_template() {}
}
