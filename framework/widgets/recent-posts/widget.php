<?php

namespace WoozioElementorWidgets\Widgets\RecentPosts;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

class Widget_RecentPosts extends Widget_Base
{

	public function get_name()
	{
		return 'bt-recent-posts';
	}

	public function get_title()
	{
		return __('Recent Posts', 'woozio');
	}

	public function get_icon()
	{
		return 'eicon-posts-ticker';
	}

	public function get_categories()
	{
		return ['woozio'];
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
			'number_posts',
			[
				'label' => __('Number of Posts', 'woozio'),
				'type' => Controls_Manager::NUMBER,
				'default' => 5,
				'min' => 1,
				'max' => 20,
			]
		);

		$this->add_control(
			'show_thumbnail',
			[
				'label' => __('Show Thumbnail', 'woozio'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'woozio'),
				'label_off' => __('Hide', 'woozio'),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_date',
			[
				'label' => __('Show Date', 'woozio'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'woozio'),
				'label_off' => __('Hide', 'woozio'),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_category',
			[
				'label' => __('Show Category', 'woozio'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'woozio'),
				'label_off' => __('Hide', 'woozio'),
				'default' => 'yes',
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_section_controls()
	{
		$this->start_controls_section(
			'section_style_item',
			[
				'label' => esc_html__('Item', 'woozio'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'item_padding',
			[
				'label' => __('Item Padding', 'woozio'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .bt-post' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_thumbnail',
			[
				'label' => esc_html__('Thumbnail', 'woozio'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_thumbnail' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'thumbnail_size',
			[
				'label' => __('Size', 'woozio'),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 80,
				],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bt-post--thumbnail' => 'width: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bt-post--thumbnail .bt-cover-image' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'thumbnail_border_radius',
			[
				'label' => __('Border Radius', 'woozio'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .bt-post--thumbnail .bt-cover-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__('Content', 'woozio'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'date_style',
			[
				'label' => __('Date', 'woozio'),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'show_date' => 'yes',
				],
			]
		);

		$this->add_control(
			'date_color',
			[
				'label' => __('Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-post--publish' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_date' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'date_typography',
				'label' => __('Typography', 'woozio'),
				'selector' => '{{WRAPPER}} .bt-post--publish',
				'condition' => [
					'show_date' => 'yes',
				],
			]
		);

		$this->add_control(
			'category_style',
			[
				'label' => __('Category', 'woozio'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_category' => 'yes',
				],
			]
		);

		$this->add_control(
			'category_color',
			[
				'label' => __('Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-post--category a' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_category' => 'yes',
				],
			]
		);

		$this->add_control(
			'category_color_hover',
			[
				'label' => __('Color Hover', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-post--category a:hover' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_category' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'category_typography',
				'label' => __('Typography', 'woozio'),
				'selector' => '{{WRAPPER}} .bt-post--category a',
				'condition' => [
					'show_category' => 'yes',
				],
			]
		);

		$this->add_control(
			'title_style',
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
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-post--title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_color_hover',
			[
				'label' => __('Color Hover', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-post--title a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __('Typography', 'woozio'),
				'selector' => '{{WRAPPER}} .bt-post--title a',
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

		$recent_posts = wp_get_recent_posts(array(
			'numberposts' => $settings['number_posts'],
			'post_status' => 'publish'
		));

?>
		<div class="bt-elwg-recent-posts widget widget-block bt-block-recent-posts">
			<?php foreach ($recent_posts as $post_item) {
				$category = get_the_terms($post_item['ID'], 'category');
			?>
				<div class="bt-post">
					<?php if ($settings['show_thumbnail'] == 'yes') { ?>
						<a href="<?php echo get_permalink($post_item['ID']) ?>" class="bt-post--thumbnail">
							<div class="bt-cover-image">
								<?php echo get_the_post_thumbnail($post_item['ID'], 'thumbnail'); ?>
							</div>
						</a>
					<?php } ?>
					<div class="bt-post--infor">
						<?php if ($settings['show_date'] == 'yes' || $settings['show_category'] == 'yes') { ?>
							<div class="bt-post--meta">
								<?php if ($settings['show_date'] == 'yes') { ?>
									<div class="bt-post--publish">
										<?php echo get_the_date(get_option('date_format'), $post_item['ID']); ?>
									</div>
								<?php } ?>
								<?php if ($settings['show_category'] == 'yes' && !empty($category) && is_array($category)) {
									$first_category = reset($category); ?>
									<div class="bt-post--category">
										<a href="<?php echo esc_url(get_category_link($first_category->term_id)); ?>">
											<?php echo esc_html($first_category->name); ?>
										</a>
									</div>
								<?php } ?>
							</div>
						<?php } ?>
						<h3 class="bt-post--title">
							<a href="<?php echo get_permalink($post_item['ID']) ?>">
								<?php echo esc_html($post_item['post_title']); ?>
							</a>
						</h3>
					</div>
				</div>
			<?php } ?>
		</div>
<?php
	}

	protected function content_template() {}
}

