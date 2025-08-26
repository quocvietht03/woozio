<?php

namespace WoozioElementorWidgets\Widgets\InstagramPosts;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Widget_InstagramPosts extends Widget_Base
{

	public function get_name()
	{
		return 'bt-instagram-posts';
	}

	public function get_title()
	{
		return __('Instagram Posts', 'woozio');
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

	protected function register_content_section_controls()
	{
		$this->start_controls_section(
			'section_content',
			[
				'label' => __('Content', 'woozio'),
			]
		);


		$this->add_control(
			'gallery',
			[
				'label' => esc_html__('Add Images', 'woozio'),
				'type' => Controls_Manager::GALLERY,
				'show_label' => false,
				'default' => [],
			]
		);
		$this->add_control(
			'open_type',
			[
				'label' => __('On Click', 'woozio'),
				'type' => Controls_Manager::SELECT,
				'default' => 'popup',
				'options' => [
					'popup' => __('Popup', 'woozio'),
					'link' => __('Link', 'woozio'),
				],
				'description' => __('Choose what happens when an Instagram image is clicked: open in a popup or go to the Instagram link.', 'woozio'),
			]
		);
		$this->add_control(
			'link',
			[
				'label' => __('Link', 'woozio'),
				'type' => Controls_Manager::URL,
				'placeholder' => __('https://your-link.com', 'woozio'),
				'default' => [
					'url' => 'https://www.instagram.com/',
					'is_external' => false,
					'nofollow' => false,
				],
				'condition' => [
					'open_type' => 'link',
				],
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_slider',
			[
				'label' => esc_html__('Slider', 'woozio'),
			]
		);

		$this->add_control(
			'enable_slider',
			[
				'label' => __('Enable Slider', 'woozio'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'woozio'),
				'label_off' => __('No', 'woozio'),
				'default' => 'no',
			]
		);

		$this->add_control(
			'slider_autoplay',
			[
				'label' => __('Slider Autoplay', 'woozio'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'woozio'),
				'label_off' => __('No', 'woozio'),
				'default' => 'no',
				'condition' => [
					'enable_slider' => 'yes',
				],
			]
		);

		$this->add_control(
			'slider_loop',
			[
				'label' => __('Infinite Loop', 'woozio'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'woozio'),
				'label_off' => __('No', 'woozio'),
				'default' => 'yes',
				'description' => __('Enable continuous loop mode', 'woozio'),
				'condition' => [
					'enable_slider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'slider_item',
			[
				'label' => __('Slides Per View', 'woozio'),
				'type' => Controls_Manager::NUMBER,
				'default' => 5,
				'tablet_default' => 3,
				'mobile_default' => 1,
				'min' => 1,
				'max' => 10,
				'condition' => [
					'enable_slider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'slider_spacebetween',
			[
				'label' => __('Space Between', 'woozio'),
				'type' => Controls_Manager::NUMBER,
				'default' => 20,
				'tablet_default' => 20,
				'mobile_default' => 10,
				'min' => 0,
				'max' => 100,
				'step' => 1,
				'description' => __('Space between slides in pixels', 'woozio'),
				'condition' => [
					'enable_slider' => 'yes',
				],
			]
		);

		$this->add_control(
			'slider_speed',
			[
				'label' => __('Slider Speed', 'woozio'),
				'type' => Controls_Manager::NUMBER,
				'default' => 1000,
				'min' => 100,
				'step' => 100,
				'condition' => [
					'enable_slider' => 'yes',
				],
			]
		);

		$this->add_control(
			'slider_arrows',
			[
				'label' => __('Show Arrows', 'woozio'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'woozio'),
				'label_off' => __('No', 'woozio'),
				'condition' => [
					'enable_slider' => 'yes',
				],
			]
		);

		$this->add_control(
			'slider_arrows_hidden_mobile',
			[
				'label' => __('Hidden Arrow Mobile', 'woozio'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'woozio'),
				'label_off' => __('No', 'woozio'),
				'default' => 'no',
				'condition' => [
					'slider_arrows' => 'yes',
					'enable_slider' => 'yes',
				],
			]
		);

		$this->add_control(
			'slider_dots',
			[
				'label' => __('Show Dots', 'woozio'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'woozio'),
				'label_off' => __('No', 'woozio'),
				'default' => 'no',
				'condition' => [
					'enable_slider' => 'yes',
				],
			]
		);

		$this->add_control(
			'slider_dots_only_mobile',
			[
				'label' => __('Mobile-Only Pagination', 'woozio'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'woozio'),
				'label_off' => __('No', 'woozio'),
				'default' => 'no',
				'condition' => [
					'slider_dots' => 'yes',
					'enable_slider' => 'yes',
				],
			]
		);
		$this->add_control(
			'slider_offset_sides',
			[
				'label' => __('Offset Sides', 'woozio'),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => __('None', 'woozio'),
					'both' => __('Both', 'woozio'),
					'left' => __('Left', 'woozio'),
					'right' => __('Right', 'woozio'),
				],
				'condition' => [
					'enable_slider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'slider_offset_width',
			[
				'label' => __('Offset Width', 'woozio'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default' => [
					'size' => 0,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bt-elwg-instagram-posts' => '--slider-offset-width: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'ui',
				'condition' => [
					'enable_slider' => 'yes',
					'slider_offset_sides!' => 'none',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_layout',
			[
				'label' => __('Layout', 'woozio'),
				'condition' => [
					'enable_slider!' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label' => __('Columns', 'woozio'),
				'type' => Controls_Manager::SELECT,
				'default' => '6',
				'options' => [
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
				'desktop_default' => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'selectors' => [
					'{{WRAPPER}} .bt-ins-posts--grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				],
			]
		);

		$this->add_responsive_control(
			'gap',
			[
				'label' => __('Gap', 'woozio'),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 16,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bt-ins-posts--grid' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_content_section_controls()
	{
		$this->start_controls_section(
			'section_style',
			[
				'label' => __('Style', 'woozio'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label' => __('Border Radius', 'woozio'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .bt-ins-posts--image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __('Icon Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-icon-view svg path' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_bg_color',
			[
				'label' => __('Icon Background', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-icon-view' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_hover_color',
			[
				'label' => __('Icon Hover Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-icon-view:hover svg path' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_hover_bg_color',
			[
				'label' => __('Icon Hover Background', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-icon-view:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_arrows',
			[
				'label' => esc_html__('Navigation Arrows', 'woozio'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'slider_arrows' => 'yes',
					'enable_slider' => 'yes',
				],
			]
		);

		$this->add_control(
			'arrows_size',
			[
				'label' => __('Size', 'woozio'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 24,
				],
				'selectors' => [
					'{{WRAPPER}} .bt-nav svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs('arrows_colors_tabs');

		// Normal state
		$this->start_controls_tab(
			'arrows_colors_normal',
			[
				'label' => __('Normal', 'woozio'),
			]
		);
		$this->add_control(
			'arrows_color',
			[
				'label' => __('Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .bt-nav svg path' => 'fill: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'arrows_bg_color',
			[
				'label' => __('Background Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0,0.5)',
				'selectors' => [
					'{{WRAPPER}} .bt-nav' => 'background-color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();

		// Hover state
		$this->start_controls_tab(
			'arrows_colors_hover',
			[
				'label' => __('Hover', 'woozio'),
			]
		);
		$this->add_control(
			'arrows_color_hover',
			[
				'label' => __('Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .bt-nav:hover svg path' => 'fill: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'arrows_bg_color_hover',
			[
				'label' => __('Background Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0,0.7)',
				'selectors' => [
					'{{WRAPPER}} .bt-nav:hover' => 'background-color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'arrows_border_radius',
			[
				'label' => __('Border Radius', 'woozio'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .bt-nav' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_dots',
			[
				'label' => esc_html__('Navigation Dots', 'woozio'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'slider_dots' => 'yes',
					'enable_slider' => 'yes',
				],
			]
		);

		$this->add_control(
			'dots_spacing',
			[
				'label' => __('Spacing Dots', 'woozio'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 30,
					],
				],
				'default' => [
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'margin: 0 {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'dots_size',
			[
				'label' => __('Size', 'woozio'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs('dots_colors_tabs');

		// Normal state
		$this->start_controls_tab(
			'dots_colors_normal',
			[
				'label' => __('Normal', 'woozio'),
			]
		);

		$this->add_control(
			'dots_color',
			[
				'label' => __('Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'default' => '#0C2C48',
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		// Hover state
		$this->start_controls_tab(
			'dots_colors_hover',
			[
				'label' => __('Hover', 'woozio'),
			]
		);

		$this->add_control(
			'dots_color_hover',
			[
				'label' => __('Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet:hover' => 'background-color: {{VALUE}};opacity: 1;',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'dots_spacing_slider',
			[
				'label' => __('Spacing', 'woozio'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .swiper' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_controls()
	{
		$this->register_content_section_controls();
		$this->register_style_content_section_controls();
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();

		if (empty($settings['gallery'])) {
			return;
		}

		$classes = ['bt-elwg-instagram-posts'];
		if ($settings['enable_slider'] === 'yes') {
			$classes[] = 'bt-elwg-instagram-posts--slider';
			if ($settings['slider_arrows_hidden_mobile'] === 'yes') {
				$classes[] = 'bt-hidden-arrow-mobile';
			}
			if ($settings['slider_dots_only_mobile'] === 'yes') {
				$classes[] = 'bt-only-dot-mobile';
			}
		}

		$slider_settings = [];
		if ($settings['enable_slider'] === 'yes') {
			$slider_settings = [
				'autoplay' => $settings['slider_autoplay'] === 'yes',
				'loop' => $settings['slider_loop'] === 'yes',
				'speed' => (int)$settings['slider_speed'],
				'slidesPerView' => !empty($settings['slider_item_mobile']) ? (int)$settings['slider_item_mobile'] : 1,
				'spaceBetween' => !empty($settings['slider_spacebetween_mobile']) ? (int)$settings['slider_spacebetween_mobile'] : 20,
				'breakpoints' => []
			];

			// Add responsive breakpoints
			$breakpoints = \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints();
			foreach ($breakpoints as $key => $breakpoint) {
				// Get the next higher breakpoint key
				$next_key = $key;
				$breakpoint_keys = array_keys($breakpoints);
				$current_index = array_search($key, $breakpoint_keys);

				if ($current_index !== false) {
					$preferred_next = match ($key) {
						'mobile' => 'mobile_extra',
						'mobile_extra' => 'tablet',
						'tablet' => 'tablet_extra',
						'tablet_extra' => 'laptop',
						'laptop' => 'desktop',
						default => $key
					};

					// If preferred next breakpoint exists, use it
					if (isset($breakpoints[$preferred_next])) {
						$next_key = $preferred_next;
					}
					// Otherwise find next available breakpoint
					else {
						$found_next = false;
						for ($i = $current_index + 1; $i < count($breakpoint_keys); $i++) {
							if (isset($breakpoints[$breakpoint_keys[$i]]) && $breakpoint_keys[$i] !== 'widescreen') {
								$next_key = $breakpoint_keys[$i];
								$found_next = true;
								break;
							}
						}
						if (!$found_next) {
							$next_key = 'desktop';
						}
					}
				}

				$slider_settings['breakpoints'][$breakpoint->get_value()] = ($next_key == 'desktop') ? [
					'slidesPerView' => !empty($settings['slider_item']) ? (int)$settings['slider_item'] : 5,
					'spaceBetween' => !empty($settings['slider_spacebetween']) ? (int)$settings['slider_spacebetween'] : 20
				] : [
					'slidesPerView' => !empty($settings["slider_item_{$next_key}"]) ? (int)$settings["slider_item_{$next_key}"] : (int)$settings['slider_item'],
					'spaceBetween' => !empty($settings["slider_spacebetween_{$next_key}"]) ? (int)$settings["slider_spacebetween_{$next_key}"] : (int)$settings['slider_spacebetween']
				];
			}
		}
?>
		<div class="<?php echo esc_attr(implode(' ', $classes)); ?> bt-slider-offset-sides-<?php echo esc_attr($settings['slider_offset_sides']); ?>" <?php echo $settings['enable_slider'] === 'yes' ? 'data-slider-settings="' . esc_attr(json_encode($slider_settings)) . '"' : ''; ?>>
			<?php if ($settings['enable_slider'] === 'yes') : ?>
				<div class="swiper">
					<div class="swiper-wrapper">
						<?php foreach ($settings['gallery'] as $item) : ?>
							<div class="swiper-slide">
								<div class="bt-ins-posts--image">
									<div class="bt-cover-image">
										<?php echo '<img src="' . esc_url($item['url']) . '" alt="' . get_the_title($item['id']) . '">'; ?>
									</div>
									<a href="<?php echo $settings['open_type'] === 'popup' ? esc_url($item['url']) : esc_url($settings['link']['url']); ?>" <?php echo $settings['open_type'] === 'popup' ? 'class="bt-icon-view elementor-clickable" data-elementor-lightbox-slideshow="bt-gallery-ins"' : 'class="bt-icon-view" target="_blank"'; ?>>
										<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
											<path d="M30.9137 15.595C30.87 15.4963 29.8112 13.1475 27.4575 10.7937C24.3212 7.6575 20.36 6 16 6C11.64 6 7.67874 7.6575 4.54249 10.7937C2.18874 13.1475 1.12499 15.5 1.08624 15.595C1.02938 15.7229 1 15.8613 1 16.0012C1 16.1412 1.02938 16.2796 1.08624 16.4075C1.12999 16.5062 2.18874 18.8538 4.54249 21.2075C7.67874 24.3425 11.64 26 16 26C20.36 26 24.3212 24.3425 27.4575 21.2075C29.8112 18.8538 30.87 16.5062 30.9137 16.4075C30.9706 16.2796 31 16.1412 31 16.0012C31 15.8613 30.9706 15.7229 30.9137 15.595ZM16 24C12.1525 24 8.79124 22.6012 6.00874 19.8438C4.86704 18.7084 3.89572 17.4137 3.12499 16C3.89551 14.5862 4.86686 13.2915 6.00874 12.1562C8.79124 9.39875 12.1525 8 16 8C19.8475 8 23.2087 9.39875 25.9912 12.1562C27.1352 13.2912 28.1086 14.5859 28.8812 16C27.98 17.6825 24.0537 24 16 24ZM16 10C14.8133 10 13.6533 10.3519 12.6666 11.0112C11.6799 11.6705 10.9108 12.6075 10.4567 13.7039C10.0026 14.8003 9.88376 16.0067 10.1153 17.1705C10.3468 18.3344 10.9182 19.4035 11.7573 20.2426C12.5965 21.0818 13.6656 21.6532 14.8294 21.8847C15.9933 22.1162 17.1997 21.9974 18.2961 21.5433C19.3924 21.0892 20.3295 20.3201 20.9888 19.3334C21.6481 18.3467 22 17.1867 22 16C21.9983 14.4092 21.3657 12.884 20.2408 11.7592C19.1159 10.6343 17.5908 10.0017 16 10ZM16 20C15.2089 20 14.4355 19.7654 13.7777 19.3259C13.1199 18.8864 12.6072 18.2616 12.3045 17.5307C12.0017 16.7998 11.9225 15.9956 12.0768 15.2196C12.2312 14.4437 12.6121 13.731 13.1716 13.1716C13.731 12.6122 14.4437 12.2312 15.2196 12.0769C15.9956 11.9225 16.7998 12.0017 17.5307 12.3045C18.2616 12.6072 18.8863 13.1199 19.3259 13.7777C19.7654 14.4355 20 15.2089 20 16C20 17.0609 19.5786 18.0783 18.8284 18.8284C18.0783 19.5786 17.0609 20 16 20Z" fill="#181818" />
										</svg>
									</a>
								</div>
							</div>
						<?php endforeach; ?>
					</div>

					<?php if ($settings['slider_arrows'] === 'yes') : ?>
						<div class="bt-swiper-navigation">
							<div class="bt-nav bt-button-prev">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
									<path d="M15.5307 18.9698C15.6004 19.0395 15.6557 19.1222 15.6934 19.2132C15.7311 19.3043 15.7505 19.4019 15.7505 19.5004C15.7505 19.599 15.7311 19.6965 15.6934 19.7876C15.6557 19.8786 15.6004 19.9614 15.5307 20.031C15.461 20.1007 15.3783 20.156 15.2873 20.1937C15.1962 20.2314 15.0986 20.2508 15.0001 20.2508C14.9016 20.2508 14.804 20.2314 14.7129 20.1937C14.6219 20.156 14.5392 20.1007 14.4695 20.031L6.96948 12.531C6.89974 12.4614 6.84443 12.3787 6.80668 12.2876C6.76894 12.1966 6.74951 12.099 6.74951 12.0004C6.74951 11.9019 6.76894 11.8043 6.80668 11.7132C6.84443 11.6222 6.89974 11.5394 6.96948 11.4698L14.4695 3.96979C14.6102 3.82906 14.8011 3.75 15.0001 3.75C15.1991 3.75 15.39 3.82906 15.5307 3.96979C15.6715 4.11052 15.7505 4.30139 15.7505 4.50042C15.7505 4.69944 15.6715 4.89031 15.5307 5.03104L8.56041 12.0004L15.5307 18.9698Z" fill="currentColor" />
								</svg>
							</div>
							<div class="bt-nav bt-button-next">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
									<path d="M17.0306 12.531L9.53055 20.031C9.46087 20.1007 9.37815 20.156 9.2871 20.1937C9.19606 20.2314 9.09847 20.2508 8.99993 20.2508C8.90138 20.2508 8.8038 20.2314 8.71276 20.1937C8.62171 20.156 8.53899 20.1007 8.4693 20.031C8.39962 19.9614 8.34435 19.8786 8.30663 19.7876C8.26892 19.6965 8.24951 19.599 8.24951 19.5004C8.24951 19.4019 8.26892 19.3043 8.30663 19.2132C8.34435 19.1222 8.39962 19.0395 8.4693 18.9698L15.4396 12.0004L8.4693 5.03104C8.32857 4.89031 8.24951 4.69944 8.24951 4.50042C8.24951 4.30139 8.32857 4.11052 8.4693 3.96979C8.61003 3.82906 8.80091 3.75 8.99993 3.75C9.19895 3.75 9.38982 3.82906 9.53055 3.96979L17.0306 11.4698C17.1003 11.5394 17.1556 11.6222 17.1933 11.7132C17.2311 11.8043 17.2505 11.9019 17.2505 12.0004C17.2505 12.099 17.2311 12.1966 17.1933 12.2876C17.1556 12.3787 17.1003 12.4614 17.0306 12.531Z" fill="currentColor" />
								</svg>
							</div>
						</div>
					<?php endif; ?>

					<?php if ($settings['slider_dots'] === 'yes') : ?>
						<div class="bt-swiper-pagination swiper-pagination"></div>
					<?php endif; ?>
				</div>
			<?php else : ?>
				<div class="bt-ins-posts--grid">
					<?php foreach ($settings['gallery'] as $item) : ?>
						<div class="bt-ins-posts--image">
							<div class="bt-cover-image">
								<?php echo '<img src="' . esc_url($item['url']) . '" alt="' . get_the_title($item['id']) . '">'; ?>
							</div>
							<a href="<?php echo $settings['open_type'] === 'popup' ? esc_url($item['url']) : esc_url($settings['link']['url']); ?>" <?php echo $settings['open_type'] === 'popup' ? 'class="bt-icon-view elementor-clickable" data-elementor-lightbox-slideshow="bt-gallery-ins"' : 'class="bt-icon-view" target="_blank"'; ?>>
								<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
									<path d="M30.9137 15.595C30.87 15.4963 29.8112 13.1475 27.4575 10.7937C24.3212 7.6575 20.36 6 16 6C11.64 6 7.67874 7.6575 4.54249 10.7937C2.18874 13.1475 1.12499 15.5 1.08624 15.595C1.02938 15.7229 1 15.8613 1 16.0012C1 16.1412 1.02938 16.2796 1.08624 16.4075C1.12999 16.5062 2.18874 18.8538 4.54249 21.2075C7.67874 24.3425 11.64 26 16 26C20.36 26 24.3212 24.3425 27.4575 21.2075C29.8112 18.8538 30.87 16.5062 30.9137 16.4075C30.9706 16.2796 31 16.1412 31 16.0012C31 15.8613 30.9706 15.7229 30.9137 15.595ZM16 24C12.1525 24 8.79124 22.6012 6.00874 19.8438C4.86704 18.7084 3.89572 17.4137 3.12499 16C3.89551 14.5862 4.86686 13.2915 6.00874 12.1562C8.79124 9.39875 12.1525 8 16 8C19.8475 8 23.2087 9.39875 25.9912 12.1562C27.1352 13.2912 28.1086 14.5859 28.8812 16C27.98 17.6825 24.0537 24 16 24ZM16 10C14.8133 10 13.6533 10.3519 12.6666 11.0112C11.6799 11.6705 10.9108 12.6075 10.4567 13.7039C10.0026 14.8003 9.88376 16.0067 10.1153 17.1705C10.3468 18.3344 10.9182 19.4035 11.7573 20.2426C12.5965 21.0818 13.6656 21.6532 14.8294 21.8847C15.9933 22.1162 17.1997 21.9974 18.2961 21.5433C19.3924 21.0892 20.3295 20.3201 20.9888 19.3334C21.6481 18.3467 22 17.1867 22 16C21.9983 14.4092 21.3657 12.884 20.2408 11.7592C19.1159 10.6343 17.5908 10.0017 16 10ZM16 20C15.2089 20 14.4355 19.7654 13.7777 19.3259C13.1199 18.8864 12.6072 18.2616 12.3045 17.5307C12.0017 16.7998 11.9225 15.9956 12.0768 15.2196C12.2312 14.4437 12.6121 13.731 13.1716 13.1716C13.731 12.6122 14.4437 12.2312 15.2196 12.0769C15.9956 11.9225 16.7998 12.0017 17.5307 12.3045C18.2616 12.6072 18.8863 13.1199 19.3259 13.7777C19.7654 14.4355 20 15.2089 20 16C20 17.0609 19.5786 18.0783 18.8284 18.8284C18.0783 19.5786 17.0609 20 16 20Z" fill="#181818" />
								</svg>
							</a>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
<?php
	}

	protected function content_template() {}
}
