<?php
/**
 * Import pack hooks
 *
 * @package Import Pack
 */

add_action( 'admin_init', 'woozio_import_pack_defineds' );
add_action( 'admin_menu', 'woozio_register_import_menu' );
