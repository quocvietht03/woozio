<?php

namespace WoozioElementorWidgets\Widgets\ProductLoopItem;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

class Widget_ProductLoopItem extends Widget_Base
{

	public function get_name()
	{
		return 'bt-product-loop-item';
	}

	public function get_title()
	{
		return __('Product Loop Item', 'woozio');
	}

	public function get_icon()
	{
		return 'bt-bears-icon eicon-post';
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
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_responsive_control(
			'image_ratio',
			[
				'label' => __('Image Ratio', 'woozio'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0.3,
						'max' => 2,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-product-gallery__image' => 'padding-bottom: calc( {{SIZE}} * 100% ) !important;',
				],
			]
		);

		$this->add_responsive_control(
			'content_text_align',
			[
				'label' => esc_html__('Alignment', 'woozio'),
				'type'  => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__('Left', 'woozio'),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__('Center', 'woozio'),
						'icon'  => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__('Right', 'woozio'),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default' => 'left',
				'toggle' => true,
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_section_controls() 
	{
		// Title Style
		$this->start_controls_section(
			'section_title_style',
			[
				'label' => __('Title', 'woozio'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __('Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-loop-product__infor .woocommerce-loop-product__title' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'title_color_hover',
			[
				'label' => __('Hover Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-loop-product__infor .woocommerce-loop-product__title:hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .woocommerce-loop-product__infor .woocommerce-loop-product__title',
			]
		);

		$this->end_controls_section();
		
		// Price Style
		$this->start_controls_section(
			'section_price_style',
			[
				'label' => __('Price', 'woozio'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'price_color',
			[
				'label' => __('Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-elwg-product-loop-item .woocommerce-loop-product__infor .price' => 'color: {{VALUE}};',
					'{{WRAPPER}} .bt-elwg-product-loop-item .woocommerce-loop-product__infor .price ins' => 'color: {{VALUE}};',
					'{{WRAPPER}} .bt-elwg-product-loop-item .woocommerce-loop-product__infor .price .woocommerce-Price-amount' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'price_typography',
				'selector' => '{{WRAPPER}} .bt-elwg-product-loop-item .woocommerce-loop-product__infor .price .woocommerce-Price-amount',
			]
		);
		$this->add_control(
			'price_sale_color',
			[
				'label' => __('Sale Price Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-elwg-product-loop-item .woocommerce-loop-product__infor .price del' => 'color: {{VALUE}};',
					'{{WRAPPER}} .bt-elwg-product-loop-item .woocommerce-loop-product__infor .price del .woocommerce-Price-amount' => 'color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'price_sale_typography',
				'label' => __('Sale Price Typography', 'woozio'),
				'selector' => '{{WRAPPER}} .bt-elwg-product-loop-item .woocommerce-loop-product__infor .price del .woocommerce-Price-amount',
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
		if (!class_exists('WooCommerce')) {
			return;
		}
		
		$settings = $this->get_settings_for_display();
		global $product;

		if (empty($product) || ! $product->is_visible()) {
			return;
		}
?>
		<div class="bt-elwg-product-loop-item <?php echo esc_attr($settings['content_text_align']); ?>">
			<?php wc_get_template_part('content', 'product'); ?>
		</div>
<?php
	}

	protected function content_template() {}
}
