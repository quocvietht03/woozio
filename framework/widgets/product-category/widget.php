<?php

namespace WoozioElementorWidgets\Widgets\ProductCategory;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

class Widget_ProductCategory extends Widget_Base
{

	public function get_name()
	{
		return 'bt-product-category';
	}

	public function get_title()
	{
		return __('Product Category', 'woozio');
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
			'layout',
			[
				'label' => __('Layout', 'woozio'),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => __('Default', 'woozio'),
					'style1' => __('Style 1', 'woozio'),
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
				'condition' => [
					'layout' => 'default',
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
				'condition' => [
					'layout' => 'default',
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
					'{{WRAPPER}} .bt-product-category--name' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .bt-product-category--name:hover' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .bt-product-category--name' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .bt-product-category--name:hover' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'name_cat_typography',
				'label' => __('Typography', 'woozio'),
				'default' => '',
				'selector' => '{{WRAPPER}} .bt-product-category--name',
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
			<div class="bt-elwg-product-category--<?php echo esc_attr($settings['layout']); ?>">
				<div class="bt-product-category<?php echo esc_attr($count_cat < 4 ? ' bt-item-padding' : ''); ?>">
					<?php
					foreach ($categories as $category) {
						get_template_part('framework/templates/product-cat', 'style', array(
							'image-size' => $settings['thumbnail_size'],
							'layout' => 'default',
							'category' => $category
						));
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
