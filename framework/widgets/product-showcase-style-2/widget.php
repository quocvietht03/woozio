<?php

namespace WoozioElementorWidgets\Widgets\ProductShowcaseStyle2;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

class Widget_ProductShowcaseStyle2 extends Widget_Base
{

	public function get_name()
	{
		return 'bt-product-showcase-style-2';
	}

	public function get_title()
	{
		return __('Product Showcase Style 2', 'woozio');
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
			'layout',
			[
				'label' => __('Layout', 'woozio'),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => __('Default', 'woozio'),
					'layout-01' => __('Layout 01', 'woozio'),
				],
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
				'default' => 'medium_large',
				'exclude' => ['custom'],
			]
		);

		$this->add_control(
			'image_fit',
			[
				'label' => __('Image Fit', 'woozio'),
				'type' => Controls_Manager::SELECT,
				'default' => 'cover',
				'options' => [
					'cover' => __('Cover', 'woozio'),
					'contain' => __('Contain', 'woozio'),
					'fill' => __('Fill', 'woozio'),
					'none' => __('None', 'woozio'),
				],
				'selectors' => [
					'{{WRAPPER}} .bt-product-showcase--item-image .bt-cover-image img' => 'object-fit: {{VALUE}};',
				],
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

		$this->add_control(
			'show_view_details',
			[
				'label' => __('Show View Details ', 'woozio'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'woozio'),
				'label_off' => __('Hide', 'woozio'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'view_details_text',
			[
				'label' => __('View Details Text', 'woozio'),
				'type' => Controls_Manager::TEXT,
				'default' => __('VIEW FULL DETAILS', 'woozio'),
				'label_block' => true,
				'condition' => [
					'show_view_details' => 'yes',
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

		$this->add_control(
			'image_background_color',
			[
				'label' => esc_html__('Background Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-product-showcase--item-image .bt-cover-image' => 'background: {{VALUE}};',
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

		$this->add_responsive_control(
			'product_content_padding',
			[
				'label' => __('Padding', 'woozio'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .bt-product-showcase--item-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		// Typography & Color for Product Category
		$this->add_control(
			'product_category_style_heading',
			[
				'label' => esc_html__('Product Category', 'woozio'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
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
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-product-showcase--item-content .bt-product--category' => 'color: {{VALUE}};',
					'{{WRAPPER}} .bt-product-showcase--item-content .bt-product--category a' => 'color: {{VALUE}};',
				],
			]
		);
		// Typography & Color for Product Title
		$this->add_control(
			'product_title_style_heading',
			[
				'label' => esc_html__('Product Title', 'woozio'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
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
				'type' => Controls_Manager::COLOR,
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
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
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

		// Typography & Color for Product Short Description
		$this->add_control(
			'product_desc_style_heading',
			[
				'label' => esc_html__('Product Short Description', 'woozio'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
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
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-product-showcase--item-content .bt-product--short-description' => 'color: {{VALUE}};',
				],
			]
		);

		// Color for Variable
		$this->add_control(
			'variable_style_heading',
			[
				'label' => esc_html__('Variable Product', 'woozio'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs('variable_style_tabs');

		$this->start_controls_tab(
			'variable_normal_tab',
			[
				'label' => esc_html__('Normal', 'woozio'),
			]
		);

		$this->add_control(
			'variable_color',
			[
				'label' => esc_html__('Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-product-showcase--item-content .bt-attributes-wrap .bt-attributes--item .bt-attributes--value .bt-item-value' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'variable_background_color',
			[
				'label' => esc_html__('Background Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-product-showcase--item-content .bt-attributes-wrap .bt-attributes--item .bt-attributes--value .bt-item-value' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'variable_border',
				'label' => esc_html__('Border', 'woozio'),
				'selector' => '{{WRAPPER}} .bt-product-showcase--item-content .bt-attributes-wrap .bt-attributes--item .bt-attributes--value .bt-item-value',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'variable_active_tab',
			[
				'label' => esc_html__('Active', 'woozio'),
			]
		);

		$this->add_control(
			'variable_active_color',
			[
				'label' => esc_html__('Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-product-showcase--item-content .bt-attributes-wrap .bt-attributes--item .bt-attributes--value .bt-item-value.active' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'variable_active_background_color',
			[
				'label' => esc_html__('Background Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-product-showcase--item-content .bt-attributes-wrap .bt-attributes--item .bt-attributes--value .bt-item-value.active' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'variable_active_border',
				'label' => esc_html__('Border', 'woozio'),
				'selector' => '{{WRAPPER}} .bt-product-showcase--item-content .bt-attributes-wrap .bt-attributes--item .bt-attributes--value .bt-item-value.active',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// Typography & Color for Add to Cart Button
		$this->add_control(
			'add_to_cart_style_heading',
			[
				'label' => esc_html__('Add to Cart Button', 'woozio'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
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
				'type' => Controls_Manager::COLOR,
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
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-product-showcase--item-content .bt-btn-add-to-cart, {{WRAPPER}} .bt-product-showcase--item-content .bt-btn-add-to-cart-variable' => 'background-color: {{VALUE}};',
				],
			]
		);

		// Typography & Color for View Details Button
		$this->add_control(
			'view_details_style_heading',
			[
				'label' => esc_html__('View Details Button', 'woozio'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_view_details' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'view_details_typography',
				'label' => esc_html__('Typography', 'woozio'),
				'selector' => '{{WRAPPER}} .bt-product-showcase--item-content .bt-btn-view-details',
				'condition' => [
					'show_view_details' => 'yes',
				],
			]
		);

		$this->start_controls_tabs('view_details_style_tabs');

		$this->start_controls_tab(
			'view_details_normal_tab',
			[
				'label' => esc_html__('Normal', 'woozio'),
				'condition' => [
					'show_view_details' => 'yes',
				],
			]
		);

		$this->add_control(
			'view_details_text_color',
			[
				'label' => esc_html__('Text Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-product-showcase--item-content .bt-btn-view-details' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_view_details' => 'yes',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'view_details_hover_tab',
			[
				'label' => esc_html__('Hover', 'woozio'),
				'condition' => [
					'show_view_details' => 'yes',
				],
			]
		);

		$this->add_control(
			'view_details_hover_text_color',
			[
				'label' => esc_html__('Text Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-product-showcase--item-content .bt-btn-view-details:hover' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_view_details' => 'yes',
				],
			]
		);


		$this->end_controls_tab();

		$this->end_controls_tabs();

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

		$layout = $settings['layout'];
		$products = $settings['products'];
		$thumbnail_size = $settings['thumbnail_size'];
		$show_view_details = $settings['show_view_details'];
		$view_details_text = $settings['view_details_text'];
		
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
		<div class="bt-elwg-product-showcase--style-2 bt-layout-<?php echo esc_attr($layout); ?> js-product-showcase">
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
			?>
					<div class="bt-product-showcase bt-product-showcase--horizontal<?php echo $product->is_type('variable') ? 'bt-product-variable' : ''; ?>">
						<?php if ($layout === 'layout-01') : 
							// Layout 01: Render product images with thumbnail slider
							// Get layout from product meta, only allow thumbnail layouts
							$product_layout = get_post_meta($product_id, '_layout_product', true);
							$allowed_layouts = ['bottom-thumbnail', 'left-thumbnail', 'right-thumbnail'];
							
							// If product layout is not set or not in allowed layouts, use default left-thumbnail
							if (!in_array($product_layout, $allowed_layouts)) {
								$product_layout = 'left-thumbnail';
							}
							
							$post_thumbnail_id = $product->get_image_id();
							$attachment_ids = $product->get_gallery_image_ids();
							$columns = apply_filters('woocommerce_product_thumbnails_columns', 4);
							$wrapper_classes = apply_filters(
								'woocommerce_single_product_image_gallery_classes',
								array(
									'woocommerce-product-gallery',
									'woocommerce-product-gallery--' . ($post_thumbnail_id ? 'with-images' : 'without-images'),
									'woocommerce-product-gallery--columns-' . absint($columns),
									'images',
									'bt-' . $product_layout
								)
							);
						?>
							<div class="bt-product-showcase--item-images">
								<div class="<?php echo esc_attr(implode(' ', array_map('sanitize_html_class', $wrapper_classes))); ?>">
									<div class="woocommerce-product-gallery__wrapper<?php echo (!empty($attachment_ids) && has_post_thumbnail()) ? ' bt-has-slide-thumbs' : ''; ?>">
										<?php if ($post_thumbnail_id) : ?>
											<div class="woocommerce-product-gallery__slider bt-gallery-lightbox bt-gallery-zoomable">
												<div class="swiper-wrapper">
													<?php
													$html = woozio_get_gallery_image_html($post_thumbnail_id, true, true);
													if (!empty($attachment_ids)) {
														foreach ($attachment_ids as $key => $attachment_id) {
															$html .= woozio_get_gallery_image_html($attachment_id, true, true);
														}
													}
													echo apply_filters('woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id);
													?>
												</div>
												<div class="swiper-button-prev"><svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M17.4995 10.0003C17.4995 10.1661 17.4337 10.3251 17.3165 10.4423C17.1992 10.5595 17.0403 10.6253 16.8745 10.6253H4.63311L9.1917 15.1832C9.24977 15.2412 9.29583 15.3102 9.32726 15.386C9.35869 15.4619 9.37486 15.5432 9.37486 15.6253C9.37486 15.7075 9.35869 15.7888 9.32726 15.8647C9.29583 15.9405 9.24977 16.0095 9.1917 16.0675C9.13363 16.1256 9.0647 16.1717 8.98882 16.2031C8.91295 16.2345 8.83164 16.2507 8.74951 16.2507C8.66739 16.2507 8.58607 16.2345 8.5102 16.2031C8.43433 16.1717 8.3654 16.1256 8.30733 16.0675L2.68233 10.4425C2.62422 10.3845 2.57812 10.3156 2.54667 10.2397C2.51521 10.1638 2.49902 10.0825 2.49902 10.0003C2.49902 9.91821 2.51521 9.83688 2.54667 9.76101C2.57812 9.68514 2.62422 9.61621 2.68233 9.55816L8.30733 3.93316C8.4246 3.81588 8.58366 3.75 8.74951 3.75C8.91537 3.75 9.07443 3.81588 9.1917 3.93316C9.30898 4.05044 9.37486 4.2095 9.37486 4.37535C9.37486 4.5412 9.30898 4.70026 9.1917 4.81753L4.63311 9.37535H16.8745C17.0403 9.37535 17.1992 9.4412 17.3165 9.55841C17.4337 9.67562 17.4995 9.83459 17.4995 10.0003Z"/></svg></div>
												<div class="swiper-button-next"><svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M17.3172 10.4425L11.6922 16.0675C11.5749 16.1848 11.4159 16.2507 11.25 16.2507C11.0841 16.2507 10.9251 16.1848 10.8078 16.0675C10.6905 15.9503 10.6247 15.7912 10.6247 15.6253C10.6247 15.4595 10.6905 15.3004 10.8078 15.1832L15.3664 10.6253H3.125C2.95924 10.6253 2.80027 10.5595 2.68306 10.4423C2.56585 10.3251 2.5 10.1661 2.5 10.0003C2.5 9.83459 2.56585 9.67562 2.68306 9.55841C2.80027 9.4412 2.95924 9.37535 3.125 9.37535H15.3664L10.8078 4.81753C10.6905 4.70026 10.6247 4.5412 10.6247 4.37535C10.6247 4.2095 10.6905 4.05044 10.8078 3.93316C10.9251 3.81588 11.0841 3.75 11.25 3.75C11.4159 3.75 11.5749 3.81588 11.6922 3.93316L17.3172 9.55816C17.3753 9.61621 17.4214 9.68514 17.4528 9.76101C17.4843 9.83688 17.5005 9.91821 17.5005 10.0003C17.5005 10.0825 17.4843 10.1638 17.4528 10.2397C17.4214 10.3156 17.3753 10.3845 17.3172 10.4425Z"/></svg></div>
											</div>
											<div class="woocommerce-product-gallery__slider-thumbs">
												<div class="swiper-wrapper">
													<?php
													$html = woozio_get_gallery_image_html($post_thumbnail_id, false, true);
													echo apply_filters('woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id);
													do_action('woocommerce_product_thumbnails');
													?>
												</div>
											</div>
										<?php endif; ?>
									</div>
								</div>
							</div>
						<?php else : 
							// Default layout: Show 2 images (thumbnail + first gallery)
							if (has_post_thumbnail($product_id)) {
								$product_thumbnail = get_the_post_thumbnail($product_id, $thumbnail_size);
							} else {
								$product_thumbnail = '<img src="' . esc_url(wc_placeholder_img_src('woocommerce_thumbnail')) . '" alt="' . esc_html__('Awaiting product image', 'woozio') . '" class="wp-post-image" />';
							}

							$gallery_image_html = '';
							$gallery_image_ids = $product->get_gallery_image_ids();

							if (!empty($gallery_image_ids)) {
								$first_gallery_image_id = $gallery_image_ids[0];
								$gallery_image_html = wp_get_attachment_image($first_gallery_image_id, $thumbnail_size);
							} else {
								$gallery_image_html = $product_thumbnail;
							}
						?>
							<div class="bt-product-showcase--item-images">
								<div class="bt-product-showcase--item-image">
									<div class="bt-cover-image">
										<?php echo $product_thumbnail; ?>
									</div>
								</div>
								<div class="bt-product-showcase--item-image">
									<div class="bt-cover-image">
										<?php echo $gallery_image_html; ?>
									</div>
								</div>
							</div>
						<?php endif; ?>
						<div class="bt-product-showcase--item-content js-check-bg-color">
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
								<?php do_action('woozio_woocommerce_template_single_countdown');  ?>
								<div class="bt-product--actions">
									<div class="bt-product--add-to-cart">
										<?php
										if (!$product->is_type('variable')) {
											do_action('woozio_woocommerce_template_loop_add_to_cart');
										} else {
											do_action('woozio_woocommerce_template_loop_add_to_cart_variable');
										}
										?>
									</div>
									<?php if ($show_view_details === 'yes' && !empty($view_details_text)) : ?>
										<div class="bt-product--view-details">
											<a href="<?php echo esc_url($product_link); ?>" class="bt-btn bt-btn-view-details">
												<?php echo esc_html($view_details_text); ?>
											</a>
										</div>
									<?php endif; ?>
								</div>
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
