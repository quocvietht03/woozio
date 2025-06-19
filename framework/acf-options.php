<?php

/* Add Theme Options Page */
function woozio_add_theme_options_page() {
    if( function_exists('acf_add_options_page') ) {
        $option_page = acf_add_options_page(array(
            'page_title'    => esc_html__('Theme Options', 'woozio'),
            'menu_title'    => esc_html__('Theme Options', 'woozio'),
            'menu_slug'     => 'theme-options-page',
            'capability'    => 'edit_posts',
            'redirect'      => false
      ));
    }
}
add_action('acf/init', 'woozio_add_theme_options_page');

function woozio_acf_json_save_point( $path ) {
	// update path
	$path = get_template_directory() . '/framework/acf-options';

	// return
	return $path;
}
add_filter( 'acf/settings/save_json', 'woozio_acf_json_save_point' );

function woozio_acf_json_load_point( $paths ) {
	// rewooziove original path (optional)
	unset( $paths[0] );
	// append path
	$paths[] = get_template_directory() . '/framework/acf-options';

	// return
	return $paths;
}
add_filter( 'acf/settings/load_json', 'woozio_acf_json_load_point' );
