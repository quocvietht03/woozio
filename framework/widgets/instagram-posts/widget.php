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

		$this->end_controls_section();
		$this->start_controls_section(
			'section_layout',
			[
				'label' => __('Layout', 'woozio'),
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
					'{{WRAPPER}} .bt-icon-view' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .bt-icon-view:hover' => 'color: {{VALUE}};',
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
	}

	protected function register_controls()
	{
		$this->register_content_section_controls();
		$this->register_style_content_section_controls();
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();

?>
		<div class="bt-elwg-instagram-posts">
			<?php
			if (!empty($settings['gallery'])) {
			?>
				<div class="bt-ins-posts--grid">
					<?php foreach ($settings['gallery'] as $item) { ?>
						<div class="bt-ins-posts--image ">
							<div class="bt-cover-image">
								<?php echo '<img src="' . esc_url($item['url']) . '" alt="' . get_the_title($item['id']) . '">'; ?>
							</div>
							<a href="<?php echo $settings['open_type'] === 'popup' ? esc_url($item['url']) : esc_url($settings['link']['url']); ?>" <?php echo $settings['open_type'] === 'popup' ? 'class="bt-icon-view elementor-clickable" data-elementor-lightbox-slideshow="bt-gallery-ins"' : 'class="bt-icon-view" target="_blank"'; ?> >
								<svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
									<path d="M23.7869 11.6962C23.7541 11.6222 22.96 9.86062 21.1947 8.09531C18.8425 5.74312 15.8716 4.5 12.6016 4.5C9.33155 4.5 6.36062 5.74312 4.00843 8.09531C2.24312 9.86062 1.4453 11.625 1.41624 11.6962C1.3736 11.7922 1.35156 11.896 1.35156 12.0009C1.35156 12.1059 1.3736 12.2097 1.41624 12.3056C1.44905 12.3797 2.24312 14.1403 4.00843 15.9056C6.36062 18.2569 9.33155 19.5 12.6016 19.5C15.8716 19.5 18.8425 18.2569 21.1947 15.9056C22.96 14.1403 23.7541 12.3797 23.7869 12.3056C23.8295 12.2097 23.8515 12.1059 23.8515 12.0009C23.8515 11.896 23.8295 11.7922 23.7869 11.6962ZM12.6016 18C9.71593 18 7.19499 16.9509 5.10812 14.8828C4.25185 14.0313 3.52335 13.0603 2.9453 12C3.5232 10.9396 4.25171 9.9686 5.10812 9.11719C7.19499 7.04906 9.71593 6 12.6016 6C15.4872 6 18.0081 7.04906 20.095 9.11719C20.9529 9.9684 21.683 10.9394 22.2625 12C21.5866 13.2619 18.6419 18 12.6016 18ZM12.6016 7.5C11.7115 7.5 10.8415 7.76392 10.1015 8.25839C9.36147 8.75285 8.78469 9.45566 8.4441 10.2779C8.1035 11.1002 8.01439 12.005 8.18802 12.8779C8.36165 13.7508 8.79024 14.5526 9.41957 15.182C10.0489 15.8113 10.8507 16.2399 11.7236 16.4135C12.5966 16.5872 13.5014 16.4981 14.3236 16.1575C15.1459 15.8169 15.8487 15.2401 16.3432 14.5001C16.8376 13.76 17.1016 12.89 17.1016 12C17.1003 10.8069 16.6258 9.66303 15.7822 8.81939C14.9385 7.97575 13.7946 7.50124 12.6016 7.5ZM12.6016 15C12.0082 15 11.4282 14.8241 10.9348 14.4944C10.4415 14.1648 10.057 13.6962 9.82991 13.1481C9.60285 12.5999 9.54344 11.9967 9.6592 11.4147C9.77495 10.8328 10.0607 10.2982 10.4802 9.87868C10.8998 9.45912 11.4343 9.1734 12.0163 9.05764C12.5982 8.94189 13.2014 9.0013 13.7496 9.22836C14.2978 9.45542 14.7663 9.83994 15.096 10.3333C15.4256 10.8266 15.6016 11.4067 15.6016 12C15.6016 12.7956 15.2855 13.5587 14.7229 14.1213C14.1603 14.6839 13.3972 15 12.6016 15Z" fill="currentColor" />
								</svg>
							</a>
						</div>
					<?php } ?>
				</div>
			<?php
			}
			?>
		</div>
<?php
	}

	protected function content_template() {}
}
