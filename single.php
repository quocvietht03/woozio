<?php
/*
 * Single Post
 */

get_header();
$layout = 'layout-default';
$banner = '';

if (function_exists('get_field')) {
	$banner = get_field('banner_post', get_the_ID()) ?: '';
	$layout = get_field('layout_post', get_the_ID()) ?: 'layout-default';
}

?>

<main id="bt_main" class="bt-site-main">
	<?php 
	if ( strpos( $_SERVER['REQUEST_URI'], 'elementor' ) !== false ) {
		while (have_posts()) : the_post();
			the_content();
		endwhile;
	} else {
		?>
		<?php if ($layout == 'layout-01') { ?>
			<div class="bt-main-image-full">
				<?php
				if (!empty($banner)) {
				?>
					<div class="bt-post--featured">
						<div class="bt-cover-image">
							<img src='<?php echo esc_url($banner['url']) ?>' />
						</div>
					</div>
				<?php
				} else {
					echo woozio_post_featured_render('full');
				}
				?>
			</div>
			<div class="bt-container-single">
				<?php
				while (have_posts()) : the_post();
				?>
					<div class="bt-main-post">
						<?php get_template_part('framework/templates/post', null, array('layout' => $layout)); ?>
					</div>
					<div class="bt-main-actions">
						<?php
						echo woozio_tags_render();
						echo woozio_share_render();
						?>
					</div>
				<?php
					woozio_post_nav();

					// If comments are open or we have at least one comment, load up the comment template.
					if (comments_open() || get_comments_number()) comments_template();
				endwhile;

				?>

			</div>
		<?php } else { ?>
			<div class="bt-single-post-breadcrumb">
				<div class="bt-container">
					<div class="bt-row-breadcrumb-single-post">
						<?php
						$home_text = 'Home';
						$delimiter = '/';
						echo '<div class="bt-breadcrumb">';
						echo woozio_page_breadcrumb($home_text, $delimiter);
						echo '</div>';
						?>
					</div>
				</div>
			</div>
			<div class="bt-main-content-ss bt-main-content-sidebar">
				<div class="bt-container">
					<div class="bt-main-post-row">
						<div class="bt-main-post-col">
							<?php
							while (have_posts()) : the_post();
							?>
								<div class="bt-main-post bt-post-sidebar">
									
									<?php get_template_part('framework/templates/post', null, array('layout' => $layout)); ?>
								</div>
								<div class="bt-main-actions">
									<?php
									echo woozio_tags_render();
									echo woozio_share_render();
									?>
								</div>
							<?php
								woozio_post_nav();

								// If comments are open or we have at least one comment, load up the comment template.
								if (comments_open() || get_comments_number()) comments_template();
							endwhile;

							?>
						</div>
						<div class="bt-sidebar-col">
							<div class="bt-sidebar">
								<?php if (is_active_sidebar('main-sidebar')) echo get_sidebar('main-sidebar'); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
		<?php echo woozio_related_posts(); ?>
		<?php
	}
	?>
</main><!-- #main -->

<?php get_footer(); ?>