<?php
/**
 * Import pack data package demo
 *
 */
$plugin_includes = array(
  array(
    'name'     => __( 'Elementor Website Builder', 'woozio' ),
    'slug'     => 'elementor',
  ),
  array(
    'name'     => __( 'Elementor Pro', 'woozio' ),
    'slug'     => 'elementor-pro',
    'source'   => IMPORT_REMOTE_SERVER_PLUGIN_DOWNLOAD . 'elementor-pro.zip',
  ),
  array(
    'name'     => __( 'Smart Slider 3 Pro', 'woozio' ),
    'slug'     => 'nextend-smart-slider3-pro',
    'source'   => IMPORT_REMOTE_SERVER_PLUGIN_DOWNLOAD . 'nextend-smart-slider3-pro.zip',
  ),
  array(
    'name'     => __( 'Advanced Custom Fields PRO', 'woozio' ),
    'slug'     => 'advanced-custom-fields-pro',
    'source'   => IMPORT_REMOTE_SERVER_PLUGIN_DOWNLOAD . 'advanced-custom-fields-pro.zip',
  ),
  array(
    'name'     => __( 'Gravity Forms', 'woozio' ),
    'slug'     => 'gravityforms',
    'source'   => IMPORT_REMOTE_SERVER_PLUGIN_DOWNLOAD . 'gravityforms.zip',
  ),
  array(
    'name'     => __( 'Newsletter', 'woozio' ),
    'slug'     => 'newsletter',
  ),
  array(
    'name'     => __( 'WooCommerce', 'woozio' ),
    'slug'     => 'woocommerce',
  ),

);

return apply_filters( 'woozio/import_pack/package_demo', [
    [
        'package_name' => 'woozio-main',
        'preview' => get_template_directory_uri() . '/screenshot.jpg',
        'url_demo' => 'https://woozio.beplusthemes.com/',
        'title' => __( 'Woozio Demo', 'woozio' ),
        'description' => __( 'Woozio main demo.', 'woozio' ),
        'plugins' => $plugin_includes,
    ],
] );
