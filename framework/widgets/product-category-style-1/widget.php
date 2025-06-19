<?php

namespace WoozioElementorWidgets\Widgets\ProductCategoryStyle1;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

class Widget_ProductCategoryStyle1 extends Widget_Base
{

	public function get_name()
	{
		return 'bt-product-category-style-1';
	}

	public function get_title()
	{
		return __('Product Category Style 1', 'woozio');
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
		return ['elementor-widgets'];
	}

	public function get_supported_taxonomies()
	{
		$supported_taxonomies = [];

		$categories = get_terms(array(
			'taxonomy' => 'product_cat',
			'hide_empty' => false,
		));
		if (!empty($categories)  && !is_wp_error($categories)) {
			foreach ($categories as $category) {
				$supported_taxonomies[$category->term_id] = $category->name;
			}
		}

		return $supported_taxonomies;
	}

	protected function register_layout_section_controls()
	{
		$this->start_controls_section(
			'section_layout',
			[
				'label' => __('Layout', 'woozio'),
			]
		);
		$this->add_control(
			'image_style',
			[
				'label' => __('Image Style', 'woozio'),
				'type' => Controls_Manager::SELECT,
				'default' => 'normal',
				'options' => [
					'normal' => __('Normal Image', 'woozio'),
					'transparent' => __('Transparent Image', 'woozio'),
				],
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
					'{{WRAPPER}} .bt-product-category--thumb .bt-cover-image' => 'padding-bottom: calc( {{SIZE}} * 100% );',
				],
			]
		);
		$this->add_control(
			'category_number',
			[
				'label' => __('Category Number', 'woozio'),
				'type' => Controls_Manager::NUMBER,
				'default' => 8,
			]
		);
		$this->add_responsive_control(
			'columns',
			[
				'label' => __('Columns', 'woozio'),
				'type' => Controls_Manager::SELECT,
				'default' => '3',
				'options' => [
					'1' => __('1', 'woozio'),
					'2' => __('2', 'woozio'),
					'3' => __('3', 'woozio'),
					'4' => __('4', 'woozio'),
					'5' => __('5', 'woozio'),
					'6' => __('6', 'woozio'),
				],
				'prefix_class' => 'elementor-grid%s-',
				'selectors' => [
					'{{WRAPPER}} .bt-product-category' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				],
			]
		);
		$this->add_responsive_control(
			'gap',
			[
				'label' => __('Gap', 'woozio'),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 20,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bt-product-category' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
	}

	protected function register_query_section_controls()
	{
		$this->start_controls_section(
			'section_query',
			[
				'label' => __('Query', 'woozio'),
			]
		);

		$this->start_controls_tabs('tabs_query');

		$this->start_controls_tab(
			'tab_query_include',
			[
				'label' => __('Include', 'woozio'),
			]
		);

		$this->add_control(
			'category',
			[
				'label' => __('Category', 'woozio'),
				'type' => Controls_Manager::SELECT2,
				'options' => $this->get_supported_taxonomies(),
				'label_block' => true,
				'multiple' => true,
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_query_exclude',
			[
				'label' => __('Exclude', 'woozio'),
			]
		);

		$this->add_control(
			'category_exclude',
			[
				'label' => __('Category', 'woozio'),
				'type' => Controls_Manager::SELECT2,
				'options' => $this->get_supported_taxonomies(),
				'label_block' => true,
				'multiple' => true,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

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
					'{{WRAPPER}} .bt-product-category--thumb .bt-cover-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .bt-product-category--thumb img',
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
				'selector' => '{{WRAPPER}} .bt-product-category--item:hover .bt-product-category--thumb img',
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
			'heading_title_style',
			[
				'label' => __('Title', 'woozio'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __('Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-product-category--name' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .bt-product-category--name',
			]
		);

		$this->add_control(
			'heading_count_style',
			[
				'label' => __('Count', 'woozio'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'count_color',
			[
				'label' => __('Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-product-category--count' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'count_typography',
				'selector' => '{{WRAPPER}} .bt-product-category--count',
			]
		);

		$this->end_controls_section();
	}
	protected function register_controls()
	{
		$this->register_layout_section_controls();
		$this->register_query_section_controls();
		$this->register_style_section_controls();
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();

		$args = array(
			'taxonomy' => 'product_cat',
			'hide_empty' => true,
			'number' => $settings['category_number'],
			'exclude' => !empty($settings['category_exclude']) ? $settings['category_exclude'] : array(),
			'include' => !empty($settings['category']) ? $settings['category'] : array()
		);

		$categories = get_terms($args);

		if (!empty($categories) && !is_wp_error($categories)) {
			$count_cat = count($categories);
?>
			<div class="bt-elwg-product-category--style-1">
				<div class="bt-product-category">
					<?php
					foreach ($categories as $category) {
						$category_url = get_term_link($category->term_id);
					?>
						<div class="bt-product-category--item">
							<a class="bt-product-category--inner" href="<?php echo esc_url($category_url); ?>">
								<div class="bt-product-category--thumb">
									<?php
									if ($settings['image_style'] === 'transparent') {
										
										$transparent_image = get_field('thumbnail_transparent', 'product_cat_' . $category->term_id);
										$thumbnail_id = !empty($transparent_image) ? $transparent_image['id'] : null;

										if ($thumbnail_id) {
											echo '<div class="bt-cover-image bt-transparent">';
											echo wp_get_attachment_image($thumbnail_id, $settings['thumbnail_size'], false);
											echo '</div>';
										}else{
											echo '<div class="bt-cover-image"></div>';
										}
										
									} else {
										echo '<div class="bt-cover-image">';
										$thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
										if ($thumbnail_id) {
											echo wp_get_attachment_image($thumbnail_id, $settings['thumbnail_size'], false);
										}
										echo '</div>';
									}
									?>
								</div>
								<h3 class="bt-product-category--name">
									<span><?php echo esc_html($category->name); ?></span>
								</h3>
								<div class="bt-product-category--count">
									<?php
									$product_count = $category->count;
									echo esc_html($product_count) . ' ' . ($product_count > 1 ? __('items', 'woozio') : __('item', 'woozio'));
									?>
								</div>
							</a>
						</div>
					<?php
					}
					?>
				</div>
			</div>

<?php
		} else {
			get_template_part('framework/templates/post', 'none');
		}
	}

	protected function content_template() {}
}
