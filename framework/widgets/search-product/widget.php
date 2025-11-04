<?php

namespace WoozioElementorWidgets\Widgets\SearchProduct;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

class Widget_SearchProduct extends Widget_Base
{

	public function get_name()
	{
		return 'bt-search-product';
	}

	public function get_title()
	{
		return __('Search Product', 'woozio');
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

	protected function register_query_section_controls()
	{
		$this->start_controls_section(
			'section_layout',
			[
				'label' => __('Layout', 'woozio'),
			]
		);
		$this->add_control(
			'layout_type',
			[
				'label' => __('Layout Type', 'woozio'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'layout-01' => __('Layout 01', 'woozio'),
					'layout-02' => __('Layout 02', 'woozio'),
					'layout-03' => __('Layout 03', 'woozio'),
				],
				'default' => 'layout-01',
			]
		);
		$this->add_control(
			'enable_category',
			[
				'label' => __('Enable Category', 'woozio'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'woozio'),
				'label_off' => __('No', 'woozio'),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'layout_type!' => 'layout-02',
				],
			]
		);

		$this->end_controls_section();
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
			'tab_query_exnlude',
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
		$this->add_control(
			'placeholder_text',
			[
				'label' => __('Placeholder Text', 'woozio'),
				'type' => Controls_Manager::TEXTAREA,
				'default' => __('What are you looking for today?', 'woozio'),
				'placeholder' => __('Enter placeholder text', 'woozio'),
			]
		);
		$this->add_control(
			'enable_autocomplete',
			[
				'label' => __('Enable Autocomplete', 'woozio'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'woozio'),
				'label_off' => __('No', 'woozio'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->end_controls_section();
	}
	protected function register_style_section_controls()
	{
		$this->start_controls_section(
			'section_style',
			[
				'label' => __('Style', 'woozio'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'input_color',
			[
				'label' => __('Input Text Color', 'woozio'), 
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-search--form input[type="search"]' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_placeholder_color',
			[
				'label' => __('Placeholder Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-search--form input[type="search"]::placeholder' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'search_icon_color',
			[
				'label' => __('Search Icon Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-search--form .bt-search-submit svg path' => 'stroke: {{VALUE}};',
				],
				'condition' => [
					'layout_type' => ['layout-02', 'layout-03'],
				],
			]
		);

		$this->add_control(
			'form_background',
			[
				'label' => __('Form Background', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-search--form' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'layout_type' => ['layout-02', 'layout-03'],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'form_border',
				'label' => __('Form Border', 'woozio'),
				'selector' => '{{WRAPPER}} .bt-search--form',
				'condition' => [
					'layout_type' => ['layout-02', 'layout-03'],
				],
			]
		);
		$this->add_control(
			'form_border_radius',
			[
				'label' => __('Border Radius', 'woozio'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .bt-search--form' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'layout_type' => ['layout-02', 'layout-03'],
				],
			]
		);
		$this->add_control(
			'input_padding',
			[
				'label' => __('Input Padding', 'woozio'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .bt-search--form input[type="search"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'layout_type' => ['layout-02', 'layout-03'],
				],
			]
		);

		$this->add_control(
			'form_padding',
			[
				'label' => __('Padding', 'woozio'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .bt-search--form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'layout_type' => ['layout-02', 'layout-03'],
				],
			]
		);
       
		$this->end_controls_section();
	}

	protected function register_controls()
	{
		$this->register_query_section_controls();
		$this->register_style_section_controls();
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();

		// Get current category from URL
		$current_cat = isset($_GET['product_cat']) ? sanitize_text_field($_GET['product_cat']) : '';

?>
		<div class="bt-elwg-search-product <?php echo esc_attr($settings['layout_type']); ?>">
			<div class="bt-search">
				<form method="get" class="bt-search--form" action="<?php echo esc_url(home_url('/shop')); ?>">
					<?php if ($settings['enable_category'] === 'yes') : ?>
						<div class="bt-search--category">
							<div class="bt-category-dropdown">

								<div class="bt-selected-category">
									<span><?php echo !empty($current_cat) ? esc_html(get_term_by('slug', $current_cat, 'product_cat')->name) : esc_html__('All', 'woozio'); ?></span>
									<svg xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="currentColor">
										<path d="M14.0306 6.53122L9.0306 11.5312C8.96092 11.6011 8.87813 11.6566 8.78696 11.6945C8.6958 11.7323 8.59806 11.7518 8.49935 11.7518C8.40064 11.7518 8.3029 11.7323 8.21173 11.6945C8.12057 11.6566 8.03778 11.6011 7.9681 11.5312L2.9681 6.53122C2.8272 6.39033 2.74805 6.19923 2.74805 5.99997C2.74805 5.80072 2.8272 5.60962 2.9681 5.46872C3.10899 5.32783 3.30009 5.24867 3.49935 5.24867C3.69861 5.24867 3.8897 5.32783 4.0306 5.46872L8.49997 9.9381L12.9693 5.4681C13.1102 5.3272 13.3013 5.24805 13.5006 5.24805C13.6999 5.24805 13.891 5.3272 14.0318 5.4681C14.1727 5.60899 14.2519 5.80009 14.2519 5.99935C14.2519 6.19861 14.1727 6.3897 14.0318 6.5306L14.0306 6.53122Z" />
									</svg>
								</div>

								<ul class="bt-category-list" style="display: none;">
									<?php

									// All Categories item
									$all_categories_class = empty($current_cat) ? 'active' : '';
									echo '<li class="bt-category-item ' . $all_categories_class . '" data-cat-slug="">';
									echo '<a href="#">' . esc_html__('All', 'woozio') . '</a>';
									echo '</li>';

									// Get categories
									$args = array(
										'hide_empty' => true,
										'exclude' => !empty($settings['category_exclude']) ? $settings['category_exclude'] : array(),
										'include' => !empty($settings['category']) ? $settings['category'] : array()
									);
									$categories = get_terms('product_cat', $args);

									if (!empty($categories) && !is_wp_error($categories)) {
										foreach ($categories as $category) {
											$active_class = ($current_cat === $category->slug) ? 'active' : '';
											echo '<li class="bt-category-item ' . $active_class . '" data-cat-slug="' . esc_attr($category->slug) . '">';
											echo '<a href="#">' . esc_html($category->name) . '</a>';
											echo '</li>';
										}
									}
									?>
								</ul>
								<script>
								</script>
							</div>
						</div>
					<?php endif; ?>
					<input type="hidden" name="product_cat" value="" />
					<input type="search" class="bt-search-field <?php echo !empty($settings['enable_autocomplete']) ? ' bt-live-search' : ''; ?>" placeholder="<?php echo esc_attr($settings['placeholder_text']); ?>" value="<?php echo isset($_GET['search_keyword']) ? esc_attr($_GET['search_keyword']) : ''; ?>" name="search_keyword" />
					<button type="submit" class="bt-search-submit">
						<?php esc_html_e('Search', 'woozio'); ?>
						<svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
							<g clip-path="url(#clip0_12147_43)">
								<path d="M10.5 18C14.6421 18 18 14.6421 18 10.5C18 6.35786 14.6421 3 10.5 3C6.35786 3 3 6.35786 3 10.5C3 14.6421 6.35786 18 10.5 18Z" stroke="#181818" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
								<path d="M15.8027 15.8037L20.9993 21.0003" stroke="#181818" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
							</g>
							<defs>
								<clipPath id="clip0_12147_43">
									<rect width="24" height="24" fill="white" />
								</clipPath>
							</defs>
						</svg>
					</button>
					<div class="bt-live-search-results">
						<span class="bt-loading-wave"></span>
						<div class="bt-load-data"></div>
					</div>
				</form>
			</div>
		</div>
<?php
		wp_reset_postdata();
	}

	protected function content_template() {}
}
