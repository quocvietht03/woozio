<?php

/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.7.0
 */

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

if ('yes' !== get_option('woocommerce_enable_myaccount_registration')) : ?>
	<div class="bt-form-login">
	<?php endif; ?>
	<?php
	do_action('woocommerce_before_customer_login_form'); ?>

	<?php if ('yes' === get_option('woocommerce_enable_myaccount_registration')) : ?>

		<div class="u-columns col2-set" id="customer_login">

			<div class="u-column1 col-1">
			<?php endif ?>
			<?php
			if (isset($_GET['register']) && $_GET['register']) {
			?>

				<h2><?php esc_html_e('Register', 'woozio'); ?></h2>

				<form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action('woocommerce_register_form_tag'); ?>>

					<?php do_action('woocommerce_register_form_start'); ?>

					<?php if ('no' === get_option('woocommerce_registration_generate_username')) : ?>

						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<label for="reg_username"><?php esc_html_e('Username', 'woozio'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e('Required', 'woozio'); ?></span></label>
							<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" placeholder="<?php echo esc_attr__('Username', 'woozio'); ?>" name="username" id="reg_username" autocomplete="username" value="<?php echo (! empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine 
																																																																																											?>
						</p>

					<?php endif; ?>

					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label for="reg_email"><?php esc_html_e('Email address', 'woozio'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e('Required', 'woozio'); ?></span></label>
						<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" placeholder="<?php echo esc_attr__('Email address', 'woozio'); ?>" name="email" id="reg_email" autocomplete="email" value="<?php echo (! empty($_POST['email'])) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine 
																																																																																								?>
					</p>

					<?php if ('no' === get_option('woocommerce_registration_generate_password')) : ?>

						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<label for="reg_password"><?php esc_html_e('Password', 'woozio'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e('Required', 'woozio'); ?></span></label>
							<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" placeholder="<?php echo esc_attr__('Password', 'woozio'); ?>" name="password" id="reg_password" autocomplete="new-password" required aria-required="true" />
						</p>

					<?php else : ?>

						<p><?php esc_html_e('A link to set a new password will be sent to your email address.', 'woozio'); ?></p>

					<?php endif; ?>

					<?php do_action('woocommerce_register_form'); ?>

					<p class="woocommerce-form-row form-row">
						<?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
						<button type="submit" class="woocommerce-Button woocommerce-button button<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?> woocommerce-form-register__submit" name="register" value="<?php esc_attr_e('Register', 'woozio'); ?>"><?php esc_html_e('Register', 'woozio'); ?></button>
					</p>

					<?php do_action('woocommerce_register_form_end'); ?>

				</form>


			<?php } else { ?>
				<h2><?php esc_html_e('Login', 'woozio'); ?></h2>

				<form class="woocommerce-form woocommerce-form-login login" method="post" novalidate>

					<?php do_action('woocommerce_login_form_start'); ?>

					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label for="username"><?php esc_html_e('Username or email address', 'woozio'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e('Required', 'woozio'); ?></span></label>
						<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" placeholder="<?php echo esc_attr__('Username or email address', 'woozio'); ?>" name="username" id="username" autocomplete="username" value="<?php echo (! empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine 
																																																																																													?>
					</p>
					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label for="password"><?php esc_html_e('Password', 'woozio'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e('Required', 'woozio'); ?></span></label>
						<input class="woocommerce-Input woocommerce-Input--text input-text" placeholder="<?php echo esc_attr__('Password', 'woozio'); ?>" type="password" name="password" id="password" autocomplete="current-password" required aria-required="true" />
					</p>

					<?php do_action('woocommerce_login_form'); ?>

					<p class="form-row form-row-remember-lost">
						<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
							<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e('Remember me', 'woozio'); ?></span>
						</label>
						<span class="woocommerce-LostPassword lost_password">
							<a href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php esc_html_e('Lost your password?', 'woozio'); ?></a>
						</span>
					</p>

					<?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
					<button type="submit" class="woocommerce-button button woocommerce-form-login__submit<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>" name="login" value="<?php esc_attr_e('Log in', 'woozio'); ?>"><?php esc_html_e('Log in', 'woozio'); ?></button>
					<?php do_action('woocommerce_login_form_end'); ?>

				</form>
			<?php } ?>
			<?php if ('yes' === get_option('woocommerce_enable_myaccount_registration')) : ?>

			</div>
			<div class="u-column2 col-2">
				<?php
				if (isset($_GET['register']) && $_GET['register']) {
				?>
					<h2><?php echo esc_html__('Already have an account?', 'woozio') ?></h2>
					<p><?php echo esc_html__('Welcome back. Sign in to access your personalized experience, saved preferences, and more. We are thrilled to have you with us again!', 'woozio') ?></p>

					<a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="bt-button-hover bt-button"><?php echo esc_html__('Login', 'woozio') ?></a>
				<?php
				} else {
				?>
					<h2><?php echo esc_html__('New Customer', 'woozio') ?></h2>
					<p><?php echo esc_html__('Be part of our growing family of new customers! Join us today and unlock a world of exclusive benefits, offers, and personalized experiences.', 'woozio') ?></p>

					<a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>/?register=true" class="bt-button-hover bt-button"><?php echo esc_html__('Register', 'woozio') ?></a>
				<?php
				}
				?>
			</div>

		</div>
	<?php endif ?>
	<?php do_action('woocommerce_after_customer_login_form');
	if ('yes' !== get_option('woocommerce_enable_myaccount_registration')) : ?>
	</div>
<?php endif; ?>