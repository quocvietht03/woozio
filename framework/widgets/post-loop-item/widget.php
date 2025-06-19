<?php
namespace WoozioElementorWidgets\Widgets\PostLoopItem;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

class Widget_PostLoopItem extends Widget_Base {


	public function get_name() {
		return 'bt-post-loop-item';
	}

	public function get_title() {
		return __( 'Post Loop Item', 'woozio' );
	}

	public function get_icon() {
		return 'eicon-posts-ticker';
	}

	public function get_categories() {
		return [ 'woozio' ];
	}

	protected function register_layout_section_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'woozio' ),
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail',
				'label' => __( 'Image Size', 'woozio' ),
				'show_label' => true,
				'default' => 'medium',
				'exclude' => [ 'custom' ],
			]
		);

		$this->add_responsive_control(
			'image_ratio',[
				'label' => __( 'Image Ratio', 'woozio' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0.75,
				],
				'range' => [
					'px' => [
						'min' => 0.3,
						'max' => 2,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bt-post--featured .bt-cover-image' => 'padding-bottom: calc( {{SIZE}} * 100% );',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_section_controls() {

		$this->start_controls_section(
			'section_style_image',
			[
				'label' => esc_html__( 'Image', 'woozio' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'img_border_radius',
			[
				'label' => __( 'Border Radius', 'woozio' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bt-post--featured .bt-cover-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'thumbnail_effects_tabs' );

		$this->start_controls_tab( 'thumbnail_tab_normal',
			[
				'label' => __( 'Normal', 'woozio' ),
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),[
				'name' => 'thumbnail_filters',
				'selector' => '{{WRAPPER}} .bt-post--featured img',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'thumbnail_tab_hover',[
				'label' => __( 'Hover', 'woozio' ),
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),[
				'name'     => 'thumbnail_hover_filters',
				'selector' => '{{WRAPPER}} .bt-post:hover .bt-post--featured img',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_content',[
				'label' => esc_html__( 'Content', 'woozio' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		// Content Box
		$this->add_control(
			'content_box_heading',
			[
				'label' => esc_html__('Box', 'woozio'),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label' => esc_html__('Padding', 'woozio'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'default' => [
					'top' => '',
					'right' => '',
					'bottom' => '', 
					'left' => '',
					'unit' => 'px',
					'isLinked' => true,
				],
				'selectors' => [
					'{{WRAPPER}} .bt-post--inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'content_background_color',
			[
				'label' => esc_html__('Background Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-post--inner' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'content_border',
				'label' => esc_html__('Border', 'woozio'),
				'selector' => '{{WRAPPER}} .bt-post--inner',
			]
		);

		$this->add_responsive_control(
			'content_border_radius',
			[
				'label' => esc_html__('Border Radius', 'woozio'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .bt-post--inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_box_shadow',
				'label' => esc_html__('Box Shadow', 'woozio'),
				'selector' => '{{WRAPPER}} .bt-post--inner',
			]
		);
		// Post Date
		$this->add_control(
			'post_date_heading',
			[
				'label' => esc_html__('Date', 'woozio'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'date_typography',
				'label' => esc_html__('Typography', 'woozio'),
				'selector' => '{{WRAPPER}} .bt-post--publish',
			]
		);

		$this->add_control(
			'date_color',
			[
				'label' => esc_html__('Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-post--publish' => 'color: {{VALUE}};',
				],
			]
		);

		// Post Category
		$this->add_control(
			'post_category_heading',
			[
				'label' => esc_html__('Category', 'woozio'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'category_typography',
				'label' => esc_html__('Typography', 'woozio'),
				'selector' => '{{WRAPPER}} .bt-post--category, {{WRAPPER}} .bt-post--category a',
			]
		);

		$this->start_controls_tabs('category_color_tabs');

		$this->start_controls_tab(
			'category_color_normal',
			[
				'label' => esc_html__('Normal', 'woozio'),
			]
		);

		$this->add_control(
			'category_color',
			[
				'label' => esc_html__('Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-post--category a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'category_color_hover',
			[
				'label' => esc_html__('Hover', 'woozio'),
			]
		);

		$this->add_control(
			'category_hover_color',
			[
				'label' => esc_html__('Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-post--category a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// Post Title
		$this->add_control(
			'post_title_heading',
			[
				'label' => esc_html__('Title', 'woozio'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => esc_html__('Typography', 'woozio'),
				'selector' => '{{WRAPPER}} .bt-post--title a',
			]
		);

		$this->start_controls_tabs('title_color_tabs');

		$this->start_controls_tab(
			'title_color_normal',
			[
				'label' => esc_html__('Normal', 'woozio'),
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__('Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-post--title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'title_color_hover',
			[
				'label' => esc_html__('Hover', 'woozio'),
			]
		);

		$this->add_control(
			'title_hover_color',
			[
				'label' => esc_html__('Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-post--title a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_hover_decoration',
			[
				'label' => esc_html__('Text Decoration', 'woozio'),
				'type' => Controls_Manager::SELECT,
				'default' => 'underline',
				'options' => [
					'none' => esc_html__('None', 'woozio'),
					'underline' => esc_html__('Underline', 'woozio'),
					'overline' => esc_html__('Overline', 'woozio'),
					'line-through' => esc_html__('Line Through', 'woozio'),
				],
				'selectors' => [
					'{{WRAPPER}} .bt-post--title a:hover' => 'text-decoration: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// Read More Button
		$this->add_control(
			'read_more_heading',
			[
				'label' => esc_html__('Read More Button', 'woozio'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'read_more_typography',
				'label' => esc_html__('Typography', 'woozio'),
				'selector' => '{{WRAPPER}} .bt-post--button',
			]
		);

		$this->start_controls_tabs('read_more_color_tabs');

		$this->start_controls_tab(
			'read_more_color_normal',
			[
				'label' => esc_html__('Normal', 'woozio'),
			]
		);

		$this->add_control(
			'read_more_color',
			[
				'label' => esc_html__('Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-post--button a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'read_more_color_hover',
			[
				'label' => esc_html__('Hover', 'woozio'),
			]
		);

		$this->add_control(
			'read_more_hover_color',
			[
				'label' => esc_html__('Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-post--button a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	protected function register_controls() {
		$this->register_layout_section_controls();
		$this->register_style_section_controls();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
			<div class="bt-elwg-post-loop-item--default">
				<?php get_template_part( 'framework/templates/post', 'style', array('image-size' => $settings['thumbnail_size'])); ?>
	    	</div>
		<?php
	}

	protected function content_template() {

	}
}
