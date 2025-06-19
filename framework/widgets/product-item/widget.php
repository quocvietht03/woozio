<?php

namespace WoozioElementorWidgets\Widgets\ProductItem;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

class Widget_ProductItem extends Widget_Base
{

	public function get_name()
	{
		return 'bt-product-item';
	}

	public function get_title()
	{
		return __('Product Item', 'woozio');
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
					'ajax' => __('Ajax', 'woozio'),
				],
			]
		);
		$this->add_control(
			'products',
			[
				'label' => __('Select Products', 'woozio'),
				'type' => Controls_Manager::SELECT2,
				'options' => $this->get_supported_products(),
				'label_block' => true,
				'multiple' => true,
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
					'{{WRAPPER}} .bt-product-item--thumb .bt-cover-image' => 'padding-bottom: calc( {{SIZE}} * 100% );',
				],
				'condition' => [
					'layout' => 'default',
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
					'{{WRAPPER}} .bt-product-item--thumb .bt-cover-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .bt-product-item--thumb img',
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
				'selector' => '{{WRAPPER}} .bt-product-item--item:hover .bt-product-item--thumb img',
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
			'name_cat_style',
			[
				'label' => __('Name Category', 'woozio'),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'name_cat_color',
			[
				'label' => __('Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-product-item--name' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'name_cat_color_hover',
			[
				'label' => __('Color Hover', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-product-item--name:hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'name_cat_background',
			[
				'label' => __('Background', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-product-item--name' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'name_cat_background_hover',
			[
				'label' => __('Background Hover', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-product-item--name:hover' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'name_cat_typography',
				'label' => __('Typography', 'woozio'),
				'default' => '',
				'selector' => '{{WRAPPER}} .bt-product-item--name',
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
			'post__in' => $products,
			'posts_per_page' => count($products),
			'post_status' => 'publish',
			'orderby' => 'post__in',
		)
?>
		<div class="bt-product-item bt-elwg-product-item--default">
			<?php
			$products = new \WP_Query($args);
			if ($products->have_posts()) :
				while ($products->have_posts()) : $products->the_post();
					global $product;
					$product_id = $product->get_id();
					$product_name = $product->get_name();
					$product_link = get_permalink($product_id);
					$product_image = get_the_post_thumbnail($product_id, $thumbnail_size);
					$product_image_id = $product->get_image_id();
					$variations = $product->get_available_variations();
			?>
					<div class="bt-product-item--item">
						<div class="bt-product-item--images">
							<?php if ($product_image_id) : ?>
								<a href="<?php echo esc_url($product_link); ?>" class="bt-product-item--thumb<?php echo ($settings['layout'] != 'ajax') ? ' bt-thumb-load-default' : ''; ?>">
									<?php
									if ($product->is_type('variable')) {
										if ($settings['layout'] === 'ajax') {
											if (!empty($variations)) {
												$first_variation = $variations[0];
												if (!empty($first_variation['image_id'])) {
													$product_image_id = $first_variation['image_id'];
													echo wp_get_attachment_image($product_image_id, $thumbnail_size);
												}
											}
										} else {
											if (!empty($variations)) {
												$unique_colors = array();
												foreach ($variations as $key => $variation) {
													if (!empty($variation['attributes']['attribute_pa_color'])) {
														$color = $variation['attributes']['attribute_pa_color'];
														if (!isset($unique_colors[$color]) && !empty($variation['image_id'])) {
															$unique_colors[$color] = true;
															$active_class = ($key === 0) ? ' active' : '';
															echo '<div class="bt-color ' . esc_attr($color) . $active_class . '">';

															// Add skip-lazy class to all images except the first one
															if ($key === 0) {
																echo wp_get_attachment_image($variation['image_id'], $thumbnail_size);
															} else {
																echo wp_get_attachment_image($variation['image_id'], $thumbnail_size, false, array('class' => 'skip-lazy'));
															}

															echo '</div>';
														}
													}
												}
											}
										}
									} else {
										echo wp_get_attachment_image($product_image_id, $thumbnail_size);
									} ?>
								</a>
							<?php endif; ?>
						</div>
						<div class="bt-product-item--icon-btn">
							<a class="bt-icon-btn bt-product-wishlist-btn" href="#" data-id="<?php echo $product_id; ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
									<path d="M16.6875 3C14.7516 3 13.0566 3.8325 12 5.23969C10.9434 3.8325 9.24844 3 7.3125 3C5.77146 3.00174 4.29404 3.61468 3.20436 4.70436C2.11468 5.79404 1.50174 7.27146 1.5 8.8125C1.5 15.375 11.2303 20.6869 11.6447 20.9062C11.7539 20.965 11.876 20.9958 12 20.9958C12.124 20.9958 12.2461 20.965 12.3553 20.9062C12.7697 20.6869 22.5 15.375 22.5 8.8125C22.4983 7.27146 21.8853 5.79404 20.7956 4.70436C19.706 3.61468 18.2285 3.00174 16.6875 3ZM12 19.3875C10.2881 18.39 3 13.8459 3 8.8125C3.00149 7.66921 3.45632 6.57317 4.26475 5.76475C5.07317 4.95632 6.16921 4.50149 7.3125 4.5C9.13594 4.5 10.6669 5.47125 11.3062 7.03125C11.3628 7.16881 11.4589 7.28646 11.5824 7.36926C11.7059 7.45207 11.8513 7.49627 12 7.49627C12.1487 7.49627 12.2941 7.45207 12.4176 7.36926C12.5411 7.28646 12.6372 7.16881 12.6937 7.03125C13.3331 5.46844 14.8641 4.5 16.6875 4.5C17.8308 4.50149 18.9268 4.95632 19.7353 5.76475C20.5437 6.57317 20.9985 7.66921 21 8.8125C21 13.8384 13.71 18.3891 12 19.3875Z" />
								</svg>
							</a>
							<a class="bt-icon-btn bt-product-compare-btn" href="#" data-id="<?php echo $product_id; ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
									<path d="M10.5 14.2504C10.3011 14.2504 10.1103 14.3295 9.96968 14.4701C9.82903 14.6108 9.75001 14.8015 9.75001 15.0004V17.6901L7.09876 15.0379C6.7493 14.6907 6.47224 14.2776 6.28364 13.8224C6.09503 13.3673 5.99862 12.8793 6.00001 12.3867V8.90669C6.707 8.72415 7.32315 8.29002 7.73296 7.68568C8.14277 7.08135 8.3181 6.3483 8.2261 5.62394C8.13409 4.89958 7.78106 4.23364 7.23318 3.75095C6.6853 3.26826 5.98019 3.00195 5.25001 3.00195C4.51983 3.00195 3.81471 3.26826 3.26683 3.75095C2.71895 4.23364 2.36592 4.89958 2.27392 5.62394C2.18191 6.3483 2.35725 7.08135 2.76706 7.68568C3.17687 8.29002 3.79301 8.72415 4.50001 8.90669V12.3876C4.49826 13.0773 4.63324 13.7606 4.89715 14.3978C5.16105 15.035 5.54864 15.6136 6.03751 16.1001L8.6897 18.7504H6.00001C5.8011 18.7504 5.61033 18.8295 5.46968 18.9701C5.32903 19.1108 5.25001 19.3015 5.25001 19.5004C5.25001 19.6994 5.32903 19.8901 5.46968 20.0308C5.61033 20.1714 5.8011 20.2504 6.00001 20.2504H10.5C10.6989 20.2504 10.8897 20.1714 11.0303 20.0308C11.171 19.8901 11.25 19.6994 11.25 19.5004V15.0004C11.25 14.8015 11.171 14.6108 11.0303 14.4701C10.8897 14.3295 10.6989 14.2504 10.5 14.2504ZM3.75001 6.00044C3.75001 5.70377 3.83798 5.41376 4.0028 5.16709C4.16763 4.92041 4.40189 4.72815 4.67598 4.61462C4.95007 4.50109 5.25167 4.47138 5.54264 4.52926C5.83361 4.58714 6.10089 4.73 6.31067 4.93978C6.52045 5.14956 6.66331 5.41683 6.72119 5.70781C6.77906 5.99878 6.74936 6.30038 6.63583 6.57447C6.5223 6.84855 6.33004 7.08282 6.08336 7.24764C5.83669 7.41247 5.54668 7.50044 5.25001 7.50044C4.85218 7.50044 4.47065 7.3424 4.18935 7.0611C3.90804 6.7798 3.75001 6.39827 3.75001 6.00044ZM19.5 15.0942V11.6142C19.5018 10.9245 19.3668 10.2413 19.1029 9.60404C18.839 8.96681 18.4514 8.38822 17.9625 7.90169L15.3103 5.25044H18C18.1989 5.25044 18.3897 5.17142 18.5303 5.03077C18.671 4.89012 18.75 4.69935 18.75 4.50044C18.75 4.30153 18.671 4.11076 18.5303 3.97011C18.3897 3.82946 18.1989 3.75044 18 3.75044H13.5C13.3011 3.75044 13.1103 3.82946 12.9697 3.97011C12.829 4.11076 12.75 4.30153 12.75 4.50044V9.00044C12.75 9.19935 12.829 9.39012 12.9697 9.53077C13.1103 9.67142 13.3011 9.75044 13.5 9.75044C13.6989 9.75044 13.8897 9.67142 14.0303 9.53077C14.171 9.39012 14.25 9.19935 14.25 9.00044V6.31075L16.9013 8.96294C17.2507 9.31018 17.5278 9.72333 17.7164 10.1784C17.905 10.6335 18.0014 11.1216 18 11.6142V15.0942C17.293 15.2767 16.6769 15.7109 16.2671 16.3152C15.8572 16.9195 15.6819 17.6526 15.7739 18.3769C15.8659 19.1013 16.219 19.7672 16.7668 20.2499C17.3147 20.7326 18.0198 20.9989 18.75 20.9989C19.4802 20.9989 20.1853 20.7326 20.7332 20.2499C21.2811 19.7672 21.6341 19.1013 21.7261 18.3769C21.8181 17.6526 21.6428 16.9195 21.233 16.3152C20.8232 15.7109 20.207 15.2767 19.5 15.0942ZM18.75 19.5004C18.4533 19.5004 18.1633 19.4125 17.9167 19.2476C17.67 19.0828 17.4777 18.8486 17.3642 18.5745C17.2507 18.3004 17.221 17.9988 17.2788 17.7078C17.3367 17.4168 17.4796 17.1496 17.6893 16.9398C17.8991 16.73 18.1664 16.5871 18.4574 16.5293C18.7483 16.4714 19.0499 16.5011 19.324 16.6146C19.5981 16.7282 19.8324 16.9204 19.9972 17.1671C20.162 17.4138 20.25 17.7038 20.25 18.0004C20.25 18.3983 20.092 18.7798 19.8107 19.0611C19.5294 19.3424 19.1478 19.5004 18.75 19.5004Z" />
								</svg>
							</a>
							<a class="bt-icon-btn bt-product-quick-view-btn" href="#" data-id="<?php echo $product_id; ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
									<path d="M23.1853 11.6962C23.1525 11.6222 22.3584 9.86062 20.5931 8.09531C18.2409 5.74312 15.27 4.5 12 4.5C8.72999 4.5 5.75905 5.74312 3.40687 8.09531C1.64155 9.86062 0.843741 11.625 0.814679 11.6962C0.772035 11.7922 0.75 11.896 0.75 12.0009C0.75 12.1059 0.772035 12.2097 0.814679 12.3056C0.847491 12.3797 1.64155 14.1403 3.40687 15.9056C5.75905 18.2569 8.72999 19.5 12 19.5C15.27 19.5 18.2409 18.2569 20.5931 15.9056C22.3584 14.1403 23.1525 12.3797 23.1853 12.3056C23.2279 12.2097 23.25 12.1059 23.25 12.0009C23.25 11.896 23.2279 11.7922 23.1853 11.6962ZM12 18C9.11437 18 6.59343 16.9509 4.50655 14.8828C3.65028 14.0313 2.92179 13.0603 2.34374 12C2.92164 10.9396 3.65014 9.9686 4.50655 9.11719C6.59343 7.04906 9.11437 6 12 6C14.8856 6 17.4066 7.04906 19.4934 9.11719C20.3514 9.9684 21.0814 10.9394 21.6609 12C20.985 13.2619 18.0403 18 12 18ZM12 7.5C11.11 7.5 10.2399 7.76392 9.49992 8.25839C8.7599 8.75285 8.18313 9.45566 7.84253 10.2779C7.50194 11.1002 7.41282 12.005 7.58646 12.8779C7.76009 13.7508 8.18867 14.5526 8.81801 15.182C9.44735 15.8113 10.2492 16.2399 11.1221 16.4135C11.995 16.5872 12.8998 16.4981 13.7221 16.1575C14.5443 15.8169 15.2471 15.2401 15.7416 14.5001C16.2361 13.76 16.5 12.89 16.5 12C16.4987 10.8069 16.0242 9.66303 15.1806 8.81939C14.337 7.97575 13.1931 7.50124 12 7.5ZM12 15C11.4066 15 10.8266 14.8241 10.3333 14.4944C9.83993 14.1648 9.45541 13.6962 9.22835 13.1481C9.00129 12.5999 8.94188 11.9967 9.05763 11.4147C9.17339 10.8328 9.45911 10.2982 9.87867 9.87868C10.2982 9.45912 10.8328 9.1734 11.4147 9.05764C11.9967 8.94189 12.5999 9.0013 13.148 9.22836C13.6962 9.45542 14.1648 9.83994 14.4944 10.3333C14.824 10.8266 15 11.4067 15 12C15 12.7956 14.6839 13.5587 14.1213 14.1213C13.5587 14.6839 12.7956 15 12 15Z" />
								</svg>
							</a>
						</div>
						<div class="bt-product-item--content">
							<div class="bt-product-item--name">
								<a href="<?php echo esc_url($product_link); ?>"><?php echo esc_html($product_name); ?></a>
								<div class="bt-product-item--price">
									<?php
									if ($product->is_type('variable')) {
										if ($settings['layout'] === 'ajax') {
											if (!empty($variations)) {
												$first_variation = $variations[0];
												$regular_price = $first_variation['display_regular_price'];
												$sale_price = $first_variation['display_price'];

												if ($sale_price < $regular_price) {
													echo '<del>' . wc_price($regular_price) . '</del> ' . wc_price($sale_price);
												} else {
													echo wc_price($regular_price);
												}
											}
										} else {
											$unique_colors = array();
											foreach ($variations as $key => $variation) {
												if (!empty($variation['attributes']['attribute_pa_color'])) {
													$color = $variation['attributes']['attribute_pa_color'];
													if (!isset($unique_colors[$color]) && !empty($variation['image_id'])) {
														$unique_colors[$color] = true;
														$active_class = ($key === 0) ? ' active' : '';
														// Get price info for this variation
														$regular_price = $variation['display_regular_price'];
														$sale_price = $variation['display_price'];

														echo '<div class="bt-price ' . esc_attr($color) . $active_class . '">';
														// Display price
														echo '<div class="bt-variation-price">';
														if ($sale_price < $regular_price) {
															echo '<del>' . wc_price($regular_price) . '</del> ' . wc_price($sale_price);
														} else {
															echo wc_price($regular_price);
														}
														echo '</div>';

														echo '</div>';
													}
												}
											}
										}
									} else {
										do_action('woozio_woocommerce_template_single_price');
									}
									?>
								</div>
							</div>
							<?php if ($product->is_type('variable')) { ?>
								<div class="bt-product-item--variations<?php echo (!empty($unique_colors) && count($unique_colors) > 3) ? ' bt-variations-more' : ''; ?>">
									<?php
									if ($settings['layout'] === 'ajax') {
										do_action('woozio_woocommerce_template_single_add_to_cart');
									} else {
										$unique_colors = array();
										echo '<div class="bt-attributes-wrap bt-attributes-default">';
										// Get unique colors from variations with slug and ID
										echo '<div class="bt-attributes--value bt-value-color">';
										$is_first = true;
										foreach ($variations as $variation) {
											if (!empty($variation['attributes']['attribute_pa_color'])) {
												$color_slug = $variation['attributes']['attribute_pa_color'];
												$color_term = get_term_by('slug', $color_slug, 'pa_color');

												if ($color_term && !isset($unique_colors[$color_slug])) {
													$unique_colors[$color_slug] = array(
														'id' => $color_term->term_id,
														'slug' => $color_slug
													);
													$color = get_field('color', 'pa_color_' . $color_term->term_id);
													if (!$color) {
														$color = $color_slug;
													}
													$active_class = $is_first ? ' active' : '';
													echo '<div class="bt-item-color' . $active_class . '" data-value="' . esc_attr($color_slug) . '">';
													echo "<div class='bt-color'>";
													echo '<span style="background-color: ' . esc_attr($color) . ';"></span>';
													echo '</div>';
													echo '<label>' . esc_html($color_term->name) . '</label>';
													echo '</div>';
													$is_first = false;
												}
											}
										}
										echo '</div>';
										echo '</div>';
									}
									?>
								</div>
							<?php } ?>
						</div>

					</div>
			<?php endwhile;
				wp_reset_postdata();
			endif;
			?>
		</div>
<?php
	}

	protected function content_template() {}
}
