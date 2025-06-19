<?php

/**
 * Variable product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/variable.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.6.0
 */

defined('ABSPATH') || exit;

global $product;

$attribute_keys  = array_keys($attributes);
$variations_json = wp_json_encode($available_variations);
$variations_attr = function_exists('wc_esc_json') ? wc_esc_json($variations_json) : _wp_specialchars($variations_json, ENT_QUOTES, 'UTF-8', true);

do_action('woocommerce_before_add_to_cart_form'); ?>
<form class="variations_form cart" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo esc_attr(absint($product->get_id())); ?>" <?php echo 'data-product_variations="' . $variations_attr . '"'; ?>>
	<?php do_action('woocommerce_before_variations_form'); ?>

	<?php if (empty($available_variations) && false !== $available_variations) : ?>
		<p class="stock out-of-stock"><?php echo esc_html(apply_filters('woocommerce_out_of_stock_message', __('This product is currently out of stock and unavailable.', 'woozio'))); ?></p>
	<?php else : ?>
		<table class="variations" cellspacing="0" role="presentation">
			<tbody>
				<?php foreach ($attributes as $attribute_name => $options) : ?>
					<tr>
						<th class="label"><label for="<?php echo esc_attr(sanitize_title($attribute_name)); ?>"><?php echo wc_attribute_label($attribute_name); // WPCS: XSS ok. 
																												?></label></th>
						<td class="value">
							<?php
							wc_dropdown_variation_attribute_options(
								array(
									'options'   => $options,
									'attribute' => $attribute_name,
									'product'   => $product,
								)
							);
							/**
							 * Filters the reset variation button.
							 *
							 * @since 2.5.0
							 *
							 * @param string  $button The reset variation button HTML.
							 */
							echo end($attribute_keys) === $attribute_name ? wp_kses_post(apply_filters('woocommerce_reset_variations_link', '<a class="reset_variations" href="#" aria-label="' . esc_attr__('Clear options', 'woozio') . '">' . esc_html__('Clear', 'woozio') . '</a>')) : '';
							?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<div class="bt-attributes-wrap">
			<?php
			foreach ($attributes as $attribute_name => $options) :
				$data_attribute = strtolower($attribute_name);
			?>
				<div class="bt-attributes--item" data-attribute-name="<?php echo esc_attr($data_attribute); ?>">
					<div class="bt-attributes--name">
						<div class="bt-name"><?php echo wc_attribute_label($attribute_name) . ':'; ?></div>
						<div class="bt-result"></div>
					</div>
					<?php if ($attribute_name == 'pa_color') { ?>
						<div class="bt-attributes--value bt-value-color">
							<?php foreach ($options as $option) :
								$term_id = get_term_by('slug', $option, $attribute_name)->term_id;
								$color = get_field('color', 'pa_color_' . $term_id);
								if (!$color) {
									$color = $option;
								}
							?>
								<div class="bt-js-item bt-item-color" data-value="<?php echo esc_attr($option); ?>">
									<div class="bt-color">
										<span style="background-color: <?php echo esc_attr($color); ?>;">
											<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
												<path d="M16 3C13.4288 3 10.9154 3.76244 8.77759 5.19089C6.63975 6.61935 4.97351 8.64968 3.98957 11.0251C3.00563 13.4006 2.74819 16.0144 3.2498 18.5362C3.75141 21.0579 4.98953 23.3743 6.80762 25.1924C8.6257 27.0105 10.9421 28.2486 13.4638 28.7502C15.9856 29.2518 18.5994 28.9944 20.9749 28.0104C23.3503 27.0265 25.3807 25.3603 26.8091 23.2224C28.2376 21.0846 29 18.5712 29 16C28.9964 12.5533 27.6256 9.24882 25.1884 6.81163C22.7512 4.37445 19.4467 3.00364 16 3ZM21.7075 13.7075L14.7075 20.7075C14.6146 20.8005 14.5043 20.8742 14.3829 20.9246C14.2615 20.9749 14.1314 21.0008 14 21.0008C13.8686 21.0008 13.7385 20.9749 13.6171 20.9246C13.4957 20.8742 13.3854 20.8005 13.2925 20.7075L10.2925 17.7075C10.1049 17.5199 9.99945 17.2654 9.99945 17C9.99945 16.7346 10.1049 16.4801 10.2925 16.2925C10.4801 16.1049 10.7346 15.9994 11 15.9994C11.2654 15.9994 11.5199 16.1049 11.7075 16.2925L14 18.5862L20.2925 12.2925C20.3854 12.1996 20.4957 12.1259 20.6171 12.0756C20.7385 12.0253 20.8686 11.9994 21 11.9994C21.1314 11.9994 21.2615 12.0253 21.3829 12.0756C21.5043 12.1259 21.6146 12.1996 21.7075 12.2925C21.8004 12.3854 21.8741 12.4957 21.9244 12.6171C21.9747 12.7385 22.0006 12.8686 22.0006 13C22.0006 13.1314 21.9747 13.2615 21.9244 13.3829C21.8741 13.5043 21.8004 13.6146 21.7075 13.7075Z" fill="white" />
											</svg>
										</span>
									</div>
									<label><?php echo esc_html(get_term_by('slug', $option, $attribute_name)->name); ?></label>
								</div>
							<?php endforeach; ?>
						</div>
					<?php } else { ?>
						<div class="bt-attributes--value">
							<?php foreach ($options as $option) : ?>
								<?php
								$term = get_term_by('slug', $option, $attribute_name);
								$display_name = $term ? $term->name : $option;
								?>
								<span class="bt-js-item bt-item-value" data-value="<?php echo esc_attr($option); ?>"><?php echo esc_html($display_name); ?></span>
							<?php endforeach; ?>
						</div>
					<?php } ?>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="reset_variations_alert screen-reader-text" role="alert" aria-live="polite" aria-relevant="all"></div>
		<?php do_action('woocommerce_after_variations_table'); ?>

		<?php
		/**
		 * Hook: woocommerce_before_single_variation.
		 */
		do_action('woocommerce_before_single_variation');

		/**
		 * Hook: woocommerce_single_variation. Used to output the cart button and placeholder for variation data.
		 *
		 * @since 2.4.0
		 * @hooked woocommerce_single_variation - 10 Empty div for variation data.
		 * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
		 */
		do_action('woocommerce_single_variation');

		/**
		 * Hook: woocommerce_after_single_variation.
		 */
		do_action('woocommerce_after_single_variation');
		?>
	<?php endif; ?>

	<?php do_action('woocommerce_after_variations_form'); ?>
</form>

<?php
do_action('woocommerce_after_add_to_cart_form');
