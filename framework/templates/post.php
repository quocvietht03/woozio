<article <?php post_class('bt-post'); ?>>
	<div class="bt-post--infor">
		<?php
		echo woozio_post_category_render();
		if (is_single()) {
			echo woozio_single_post_title_render();
		} else {
			echo woozio_post_title_render();
		}
		echo woozio_post_meta_single_render();
		?>
	</div>
	<?php
	$layout = isset($args['layout']) ? $args['layout'] : 'layout-default';
	if ($layout == 'layout-default') {
		echo woozio_post_featured_render();
	}
	echo woozio_post_content_render();
	?>
</article>