<?php
/**
 * Import static func
 *
 * @package Import Pack
 */

if( ! function_exists( 'woozio_import_pack_scripts' ) ) {
    /**
     * Import pack load scripts
     *
     */
    function woozio_import_pack_scripts() {

        wp_enqueue_style( 'import-pack-css', get_template_directory_uri() . '/install/import-pack/dist/import-pack.css', false, wp_get_theme()->get( 'Version' ) );
        wp_enqueue_script( 'import-pack-js', get_template_directory_uri() . '/install/import-pack/dist/import-pack.js', ['jquery'], wp_get_theme()->get( 'Version' ), true );

        # get current user id
        $user_id = get_current_user_id();
        
        wp_localize_script( 'import-pack-js', 'import_pack_php_data', apply_filters( 'woozio/import_pack/localize_script_data', [
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            /**
             * nonce for ajax
             * security
             */
            'template_nonce'    => wp_create_nonce( 'woozio_template_nonce' ),
            'callback_nonce'    => wp_create_nonce( 'woozio_callback_nonce' ),
            'download_package_nonce'  => wp_create_nonce( 'woozio_download_package_nonce' ),
            'extract_package_nonce'  => wp_create_nonce( 'woozio_extract_package_nonce' ),
            'restore_data_nonce'  => wp_create_nonce( 'woozio_restore_data_nonce' ),
            'backup_site_nonce'  => wp_create_nonce( 'woozio_backup_site_nonce' ),

            // language
            'language' => array( ),

            /** nonce for ajax
             * security
             */
            'import_nonce' => [
                'backup_database' => wp_create_nonce( 'BBACKUP_Backup_Database_' . $user_id ),
                'create_file_config' => wp_create_nonce( 'BBACKUP_Create_File_Config_' . $user_id ),
                'backup_folder_upload' => wp_create_nonce( 'BBACKUP_Backup_Folder_Upload_' . $user_id ),
                'restore_data' => wp_create_nonce( 'BBACKUP_Restore_Data_' . $user_id ),
                'restore_data' => wp_create_nonce( 'BBACKUP_Restore_Data_' . $user_id ),
            ]
        ] ) );
    }

    add_action( 'admin_enqueue_scripts', 'woozio_import_pack_scripts' );
}
