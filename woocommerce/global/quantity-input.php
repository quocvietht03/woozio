<?php

/**
 * Product quantity inputs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/quantity-input.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.4.0
 *
 * @var bool   $readonly If the input should be set to readonly mode.
 * @var string $type     The input type attribute.
 */

defined('ABSPATH') || exit;

/* translators: %s: Quantity. */
if ($type === 'hidden') {
	return;
}

$label = ! empty($args['product_name']) ? sprintf(esc_html__('%s quantity', 'woozio'), wp_strip_all_tags($args['product_name'])) : esc_html__('Quantity', 'woozio');

echo '<span class="title-quantity">' . esc_html__('Quantity:', 'woozio') . '</span>';

?>

<div class="quantity">
	<?php
	/**
	 * Hook to output something before the quantity input field.
	 *
	 * @since 7.2.0
	 */
	do_action('woocommerce_before_quantity_input_field');
	?>
	<label class="screen-reader-text" for="<?php echo esc_attr($input_id); ?>"><?php echo esc_html($label); ?></label>
	<span class="qty-minus">
		<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
			<path d="M432 256c0 17.7-14.3 32-32 32L48 288c-17.7 0-32-14.3-32-32s14.3-32 32-32l352 0c17.7 0 32 14.3 32 32z" />
		</svg>
	</span>
	<input
		type="<?php echo esc_attr($type); ?>"
		<?php if ($readonly) echo 'readonly="readonly"'; ?>
		id="<?php echo esc_attr($input_id); ?>"
		class="<?php echo esc_attr(join(' ', (array) $classes)); ?>"
		name="<?php echo esc_attr($input_name); ?>"
		value="<?php echo esc_attr($input_value); ?>"
		aria-label="<?php esc_attr_e('Product quantity', 'woozio'); ?>"
		size="4"
		min="<?php echo esc_attr($min_value); ?>"
		max="<?php echo esc_attr(0 < $max_value ? $max_value : ''); ?>"
		<?php if (! $readonly) : ?>
		step="<?php echo esc_attr($step); ?>"
		placeholder="<?php echo esc_attr($placeholder); ?>"
		inputmode="<?php echo esc_attr($inputmode); ?>"
		autocomplete="<?php echo esc_attr(isset($autocomplete) ? $autocomplete : 'on'); ?>"
		<?php endif; ?> />
	<span class="qty-plus">
		<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
			<path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z" />
		</svg>
	</span>
	<?php
	/**
	 * Hook to output something after quantity input field
	 *
	 * @since 3.6.0
	 */
	do_action('woocommerce_after_quantity_input_field');
	?>
</div>
<?php
