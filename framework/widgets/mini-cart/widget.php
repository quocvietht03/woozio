<?php

namespace WoozioElementorWidgets\Widgets\MiniCart;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

class Widget_MiniCart extends Widget_Base
{

	public function get_name()
	{
		return 'bt-mini-cart';
	}

	public function get_title()
	{
		return __('Mini Cart', 'woozio');
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
			'cart_mini_icon',
			[
				'label' => esc_html__('Icon Cart', 'woozio'),
				'type' => Controls_Manager::MEDIA,
				'media_types' => ['svg'],
			]
		);
		$this->add_control(
			'enable_sidebar_cart',
			[
				'label' => esc_html__('Enable Sidebar Cart', 'woozio'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'woozio'),
				'label_off' => esc_html__('No', 'woozio'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->end_controls_section();
	}


	protected function register_style_content_section_controls()
	{

		$this->start_controls_section(
			'section_style_content_cart',
			[
				'label' => esc_html__('Content Cart', 'woozio'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_cart',
			[
				'label' => __('Icon', 'woozio'),
				'type' => Controls_Manager::HEADING,
			]
		);
		$this->add_responsive_control(
			'icon_cart_size',
			[
				'label' => __('Icon size', 'woozio'),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 35,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bt-elwg-mini-cart--default .bt-mini-cart a svg ' => 'width: {{SIZE}}px;height:{{SIZE}}px;',
				],
			]
		);
		$this->add_control(
			'icon_cart_color',
			[
				'label' => __('Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-elwg-mini-cart--default .bt-mini-cart a svg path' => 'fill: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'icon_cart_background',
			[
				'label' => __('Background', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-elwg-mini-cart--default .bt-mini-cart a' => 'background: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'number_cart',
			[
				'label' => __('Number Cart', 'woozio'),
				'type' => Controls_Manager::HEADING,
			]
		);
		$this->add_control(
			'number_cart_color',
			[
				'label' => __('Color', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-elwg-mini-cart--default .bt-mini-cart span' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'number_cart_background',
			[
				'label' => __('Background', 'woozio'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-elwg-mini-cart--default .bt-mini-cart span' => 'background: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'number_cart_typography',
				'label' => __('Typography', 'woozio'),
				'default' => '',
				'selector' => '{{WRAPPER}} .bt-elwg-mini-cart--default .bt-mini-cart span',
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
		$icon_cart = $settings['cart_mini_icon']['url'];
?>
		<div class="bt-elwg-mini-cart--default">
			<div class="bt-mini-cart">
				<a class="bt-toggle-btn <?php echo ('yes' === $settings['enable_sidebar_cart'] && !is_cart()) ? 'js-cart-sidebar' : ''; ?>" href="<?php echo esc_url(wc_get_cart_url()) ?>">
					<?php if (!empty($icon_cart) && 'svg' === pathinfo($icon_cart, PATHINFO_EXTENSION)) {
						echo file_get_contents($icon_cart);
					} else { ?>
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
							<path d="M9.75 20.25C9.75 20.5467 9.66203 20.8367 9.4972 21.0834C9.33238 21.33 9.09811 21.5223 8.82403 21.6358C8.54994 21.7494 8.24834 21.7791 7.95736 21.7212C7.66639 21.6633 7.39912 21.5204 7.18934 21.3107C6.97956 21.1009 6.8367 20.8336 6.77882 20.5426C6.72094 20.2517 6.75065 19.9501 6.86418 19.676C6.97771 19.4019 7.16997 19.1676 7.41665 19.0028C7.66332 18.838 7.95333 18.75 8.25 18.75C8.64782 18.75 9.02936 18.908 9.31066 19.1893C9.59196 19.4706 9.75 19.8522 9.75 20.25ZM18 18.75C17.7033 18.75 17.4133 18.838 17.1666 19.0028C16.92 19.1676 16.7277 19.4019 16.6142 19.676C16.5006 19.9501 16.4709 20.2517 16.5288 20.5426C16.5867 20.8336 16.7296 21.1009 16.9393 21.3107C17.1491 21.5204 17.4164 21.6633 17.7074 21.7212C17.9983 21.7791 18.2999 21.7494 18.574 21.6358C18.8481 21.5223 19.0824 21.33 19.2472 21.0834C19.412 20.8367 19.5 20.5467 19.5 20.25C19.5 19.8522 19.342 19.4706 19.0607 19.1893C18.7794 18.908 18.3978 18.75 18 18.75ZM22.4728 6.95062L20.0691 15.6019C19.9369 16.0745 19.6542 16.4911 19.2639 16.7885C18.8736 17.0859 18.397 17.2479 17.9062 17.25H8.64C8.14784 17.2498 7.66926 17.0886 7.27725 16.791C6.88523 16.4935 6.6013 16.0758 6.46875 15.6019L3.18 3.75H1.5C1.30109 3.75 1.11032 3.67098 0.96967 3.53033C0.829018 3.38968 0.75 3.19891 0.75 3C0.75 2.80109 0.829018 2.61032 0.96967 2.46967C1.11032 2.32902 1.30109 2.25 1.5 2.25H3.75C3.91397 2.24997 4.07343 2.30367 4.20398 2.40289C4.33452 2.50211 4.42895 2.64138 4.47281 2.79938L5.36156 6H21.75C21.8656 5.99998 21.9797 6.02669 22.0833 6.07805C22.1869 6.1294 22.2772 6.20401 22.3472 6.29605C22.4171 6.38809 22.4649 6.49506 22.4867 6.60861C22.5085 6.72216 22.5037 6.83922 22.4728 6.95062ZM20.7628 7.5H5.77875L7.91719 15.2006C7.96105 15.3586 8.05548 15.4979 8.18602 15.5971C8.31657 15.6963 8.47603 15.75 8.64 15.75H17.9062C18.0702 15.75 18.2297 15.6963 18.3602 15.5971C18.4908 15.4979 18.5852 15.3586 18.6291 15.2006L20.7628 7.5Z" fill="currentColor" />
						</svg>
					<?php } ?>
					<span class="cart_total"><?php echo WC()->cart->get_cart_contents_count(); ?></span></a>
			</div>
			<?php if ('yes' === $settings['enable_sidebar_cart'] && !is_cart()) : ?>
				<div class="bt-mini-cart-sidebar">
					<div class="bt-mini-cart-sidebar-overlay"></div>
					<div class="bt-mini-cart-sidebar-content">
						<div class="bt-mini-cart-sidebar-header">
							<h4><?php echo esc_html__('Shopping Cart', 'woozio'); ?></h4>
							<button class="bt-mini-cart-close">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
									<path d="M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
									<path d="M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
								</svg>
							</button>
						</div>
						<div class="bt-mini-cart-sidebar-body">
							<div class="widget_shopping_cart_content">
								<?php woocommerce_mini_cart(); ?>
							</div>
							<?php
							$free_shipping_threshold = woozio_get_free_shipping_minimum_amount();
							$cart_total = WC()->cart->get_cart_contents_total();
							$currency_symbol = get_woocommerce_currency_symbol();
							if ($cart_total < $free_shipping_threshold) {
								$amount_left = $free_shipping_threshold - $cart_total;

								$percentage = ($cart_total / $free_shipping_threshold) * 100;
								$message = sprintf(
									__('<p class="bt-buy-more">Buy <span>%1$s%2$.2f</span> more to get <span>FREESHIP</span></p>', 'woozio'),
									$currency_symbol,
									$amount_left
								);
							} else {
								$percentage = 100;
								$message = __('<p class="bt-congratulation"> Congratulations! You have free shipping!</p>', 'woozio');
							}
							if ($free_shipping_threshold > 0) {
							?>
								<div class="bt-progress-content <?php echo (WC()->cart->is_empty()) ? 'bt-hide' : ''; ?>">
									<?php echo '<div id="bt-free-shipping-message">' . $message . '</div>'; ?>
									<div class="bt-progress-container-cart">
										<div class="bt-progress-bar" data-width="<?php echo esc_attr($percentage) ?>">
											<div class="bt-icon-shipping">
												<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
													<g clip-path="url(#clip0_2134_37062)">
														<path d="M14.375 6.25H17.7016C17.8261 6.24994 17.9478 6.28709 18.0511 6.35669C18.1544 6.42629 18.2345 6.52517 18.2812 6.64063L19.375 9.375" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
														<path d="M1.875 11.25H14.375" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
														<path d="M15 16.875C16.0355 16.875 16.875 16.0355 16.875 15C16.875 13.9645 16.0355 13.125 15 13.125C13.9645 13.125 13.125 13.9645 13.125 15C13.125 16.0355 13.9645 16.875 15 16.875Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
														<path d="M6.25 16.875C7.28553 16.875 8.125 16.0355 8.125 15C8.125 13.9645 7.28553 13.125 6.25 13.125C5.21447 13.125 4.375 13.9645 4.375 15C4.375 16.0355 5.21447 16.875 6.25 16.875Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
														<path d="M13.125 15H8.125" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
														<path d="M14.375 9.375H19.375V14.375C19.375 14.5408 19.3092 14.6997 19.1919 14.8169C19.0747 14.9342 18.9158 15 18.75 15H16.875" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
														<path d="M4.375 15H2.5C2.33424 15 2.17527 14.9342 2.05806 14.8169C1.94085 14.6997 1.875 14.5408 1.875 14.375V5.625C1.875 5.45924 1.94085 5.30027 2.05806 5.18306C2.17527 5.06585 2.33424 5 2.5 5H14.375V13.232" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
													</g>
													<defs>
														<clipPath id="clip0_2134_37062">
															<rect width="20" height="20" fill="white" />
														</clipPath>
													</defs>
												</svg>
											</div>
										</div>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
<?php
	}

	protected function content_template() {}
}
