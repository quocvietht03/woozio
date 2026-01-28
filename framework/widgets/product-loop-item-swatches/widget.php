<?php

namespace WoozioElementorWidgets\Widgets\ProductLoopItemSwatches;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

class Widget_ProductLoopItemSwatches extends Widget_Base
{

	public function get_name()
	{
		return 'bt-product-loop-item-swatches';
	}

	public function get_title()
	{
		return __('Product Loop Item Swatches', 'woozio');
	}

	public function get_icon()
	{
		return 'bt-bears-icon eicon-post';
	}

	public function get_categories()
	{
		return ['woozio'];
	}
	public function get_script_depends()
    {
        return ['elementor-widgets'];
    }
	/**
	 * Get all product attributes for select options
	 * 
	 * @return array Array of attributes in format [slug => label]
	 */
	protected function get_custom_location_attributes()
	{
		$attributes = array();
		
		if (!function_exists('wc_get_attribute_taxonomy_ids')) {
			return $attributes;
		}
		
		$attribute_ids = wc_get_attribute_taxonomy_ids();
		
		if (empty($attribute_ids)) {
			return $attributes;
		}
		
		foreach ($attribute_ids as $attribute_name => $attribute_id) {
			$attribute = wc_get_attribute($attribute_id);
			if ($attribute && isset($attribute->name)) {
				$attributes[$attribute->slug] = $attribute->name;
			}
		}
		
		return $attributes;
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

		$this->add_control(
			'layout_style',
			[
				'label' => __('Layout Style', 'woozio'),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => __('Default', 'woozio'),
					'style-1' => __('Style 1', 'woozio'),
					'style-2' => __('Style 2', 'woozio'),
					'style-3' => __('Style 3', 'woozio'),
					'style-4' => __('Style 4', 'woozio'),
					'style-5' => __('Style 5', 'woozio'),
				],
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
				'label' => esc_html__('Alignment Content', 'woozio'),
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
				'condition' => [
					'layout_style' => ['default'],
				],
				
			]
		);
		$this->add_responsive_control(
			'content_text_align_style_1',
			[
				'label' => esc_html__('Alignment', 'woozio'),
				'type'  => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__('Left', 'woozio'),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__('Center', 'woozio'),
						'icon'  => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => esc_html__('Right', 'woozio'),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default' => 'flex-start',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-loop-product__thumbnail .bt-custom-location-attributes.bt-attributes-wrap' => 'align-items: {{VALUE}};',
				],
				'condition' => [
					'layout_style' => ['style-1', 'style-4'],
				],
			]
		);

		$this->add_control(
			'custom_location_attributes',
			[
				'label' => __('Custom Location Attributes', 'woozio'),
				'type' => Controls_Manager::SELECT2,
				'default' => '',
				'options' => $this->get_custom_location_attributes(),
				'multiple' => true,
				'description' => __('Select up to 2 product attributes', 'woozio'),
				'label_block' => true,
				'select2options' => [
					'maximumSelectionLength' => 2,
				],
				'condition' => [
					'layout_style' => ['default', 'style-1', 'style-4', 'style-5'],
				],
			]
		);

		$this->add_control(
			'disable_hover_effect',
			[
				'label' => __('Disable Hover Effect', 'woozio'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'woozio'),
				'label_off' => __('No', 'woozio'),
				'return_value' => 'yes',
				'default' => 'no',
				'description' => __('Disable hover effects on attribute swatches', 'woozio'),

			]
		);

		$this->add_control(
			'disable_sale_marquee_countdown',
			[
				'label' => __('Disable Sale Marquee & Countdown Timer', 'woozio'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'woozio'),
				'label_off' => __('No', 'woozio'),
				'return_value' => 'yes',
				'default' => 'no',
				'description' => __('Hide sale marquee and countdown timer elements', 'woozio'),
				'condition' => [
					'layout_style' => ['default', 'style-2'],
				],
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
					'{{WRAPPER}} .bt-elwg-product-loop-item-swatches .woocommerce-loop-product__infor .price' => 'color: {{VALUE}};',
					'{{WRAPPER}} .bt-elwg-product-loop-item-swatches .woocommerce-loop-product__infor .price ins' => 'color: {{VALUE}};',
					'{{WRAPPER}} .bt-elwg-product-loop-item-swatches .woocommerce-loop-product__infor .price .woocommerce-Price-amount' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'price_typography',
				'selector' => '{{WRAPPER}} .bt-elwg-product-loop-item-swatches .woocommerce-loop-product__infor .price .woocommerce-Price-amount',
			]
		);
		$this->add_control(
			'price_sale_color',
			[
				'label' => __('Sale Price Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-elwg-product-loop-item-swatches .woocommerce-loop-product__infor .price del' => 'color: {{VALUE}};',
					'{{WRAPPER}} .bt-elwg-product-loop-item-swatches .woocommerce-loop-product__infor .price del .woocommerce-Price-amount' => 'color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'price_sale_typography',
				'label' => __('Sale Price Typography', 'woozio'),
				'selector' => '{{WRAPPER}} .bt-elwg-product-loop-item-swatches .woocommerce-loop-product__infor .price del .woocommerce-Price-amount',
			]
		);

		$this->end_controls_section();

		// Image Attribute Style
		$this->start_controls_section(
			'section_image_attribute_style',
			[
				'label' => __('Image Attribute', 'woozio'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'image_attribute_width',
			[
				'label' => __('Width', 'woozio'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em', 'rem'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bt-attributes--item .bt-attributes--value.bt-value-image .bt-item-image .bt-image > span' => 'width: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'image_attribute_height',
			[
				'label' => __('Height', 'woozio'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em', 'rem'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bt-attributes--item .bt-attributes--value.bt-value-image .bt-item-image .bt-image > span' => 'height: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'image_attribute_border_radius',
			[
				'label' => __('Border Radius', 'woozio'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em', 'rem'],
				'selectors' => [
					'{{WRAPPER}} .bt-attributes--item .bt-attributes--value.bt-value-image .bt-item-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					'{{WRAPPER}} .bt-attributes--item .bt-attributes--value.bt-value-image .bt-item-image .bt-image > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

		$this->end_controls_section();

		// Color Attribute Style
		$this->start_controls_section(
			'section_color_attribute_style',
			[
				'label' => __('Color Attribute', 'woozio'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'color_attribute_width',
			[
				'label' => __('Width', 'woozio'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em', 'rem'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bt-attributes-wrap .bt-attributes--item .bt-attributes--value.bt-value-color .bt-item-color .bt-color > span' => 'width: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'color_attribute_height',
			[
				'label' => __('Height', 'woozio'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em', 'rem'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bt-attributes-wrap .bt-attributes--item .bt-attributes--value.bt-value-color .bt-item-color .bt-color > span' => 'height: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'color_attribute_border_radius',
			[
				'label' => __('Border Radius', 'woozio'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em', 'rem'],
				'selectors' => [
					'{{WRAPPER}} .bt-attributes--item .bt-attributes--value.bt-value-color .bt-item-color' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					'{{WRAPPER}} .bt-attributes--item .bt-attributes--value.bt-value-color .bt-item-color:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					'{{WRAPPER}} .bt-attributes--item .bt-attributes--value.bt-value-color .bt-item-color .bt-color > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
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
		if (!class_exists('WooCommerce')) {
			return;
		}
		
		$settings = $this->get_settings_for_display();
		global $product;

		if (empty($product) || ! $product->is_visible()) {
			return;
		}

		$layout_style = isset($settings['layout_style']) ? $settings['layout_style'] : 'default';
		$text_align = isset($settings['content_text_align']) ? $settings['content_text_align'] : 'left';
		$custom_location_attributes = isset($settings['custom_location_attributes']) ? $settings['custom_location_attributes'] : '';
		$disable_hover_effect = isset($settings['disable_hover_effect']) ? $settings['disable_hover_effect'] : 'no';
		$disable_sale_marquee_countdown = isset($settings['disable_sale_marquee_countdown']) ? $settings['disable_sale_marquee_countdown'] : 'no';
		
		// Ensure custom_location_attributes is an array
		if (!is_array($custom_location_attributes)) {
			$custom_location_attributes = !empty($custom_location_attributes) ? array($custom_location_attributes) : array();
		}
		
		// Limit to maximum 2 attributes
		if (count($custom_location_attributes) > 2) {
			$custom_location_attributes = array_slice($custom_location_attributes, 0, 2);
		}
		
		// Add layout class to wrapper
		$wrapper_classes = array(
			'bt-elwg-product-loop-item-swatches',
			$text_align,
			'layout-' . $layout_style
		);
		
		// Add common class for layouts with dual variation sync
		if (in_array($layout_style, ['style-1', 'style-2', 'style-3', 'style-4'])) {
			$wrapper_classes[] = 'bt-has-dual-variation';
		}
		
		// Add class to disable hover effect
		if ($disable_hover_effect === 'yes') {
			$wrapper_classes[] = 'bt-disable-hover-effect';
		}
		
		$wrapper_class = implode(' ', array_filter($wrapper_classes));
		
		// Generate unique ID for this widget instance
		$widget_id = 'bt-widget-swatches-' . $this->get_id();
		
		// Prepare template args
		$template_args = array('layout' => $layout_style);
		if (!empty($custom_location_attributes) && is_array($custom_location_attributes)) {
			$template_args['custom_location_attributes'] = $custom_location_attributes;
		}
		
		// Generate CSS for hiding attributes in add-to-cart section (scoped to this widget only)
		$custom_css = '';
		if (!empty($custom_location_attributes) && is_array($custom_location_attributes) && $product && $product->is_type('variable')) {
			$product_attributes = $product->get_attributes();
			foreach ($custom_location_attributes as $attr_slug) {
				$data_attr_slug = sanitize_title($attr_slug);
				// Try to get the proper attribute name for data-attribute-name
				if (isset($product_attributes[$attr_slug])) {
					$attribute_obj = $product_attributes[$attr_slug];
					$data_attr_slug = sanitize_title($attribute_obj->get_name());
				} elseif (isset($product_attributes['pa_' . $attr_slug])) {
					$attribute_obj = $product_attributes['pa_' . $attr_slug];
					$data_attr_slug = sanitize_title($attribute_obj->get_name());
				}
				
				// Scope CSS to this widget instance only
				$custom_css .= '#' . esc_attr($widget_id) . ' .bt-has-custom-attributes .bt-product-add-to-cart-variable .bt-attributes-wrap .bt-attributes--item[data-attribute-name="' . esc_attr($data_attr_slug) . '"] {';
				$custom_css .= 'display: none !important;';
				$custom_css .= '}';
			}
		}
		
		// Generate CSS for hiding sale marquee and countdown timer if option is enabled
		if ($disable_sale_marquee_countdown === 'yes') {
			$custom_css .= '#' . esc_attr($widget_id) . ' .bt-product-sale-marquee,';
			$custom_css .= '#' . esc_attr($widget_id) . ' .bt-product-countdown-timer {';
			$custom_css .= 'display: none !important;';
			$custom_css .= '}';
		}
?>
		<div id="<?php echo esc_attr($widget_id); ?>" class="<?php echo esc_attr($wrapper_class); ?>">
			<?php if (!empty($custom_css)) : ?>
			<style>
				<?php echo wp_strip_all_tags($custom_css); ?>
			</style>
			<?php endif; ?>
			<?php 
			// Load the swatches template with layout and custom_location_attributes variables
			get_template_part('woocommerce/content', 'product-swatches', $template_args);
			?>
		</div>
<?php
	}

	protected function content_template() {}
}
