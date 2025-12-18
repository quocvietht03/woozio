<?php
/**
 * Import pack class install helpers
 *
 * @package Import Pack
 * @author Beplus
 */

if ( ! class_exists( 'WP_Upgrader' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
}

class Import_Pack_Installer_Helper {

  public static function download_and_install_a_package( $package, $destination, $hook_extra = array(), $folder = false ) {

    if ( ! class_exists( 'WP_Upgrader' ) ) {
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
    }

    global $wp_filesystem;
    // Initialize the WP filesystem, no more using 'file-put-contents' function
    if (empty($wp_filesystem)) {
        require_once (ABSPATH . '/wp-admin/includes/file.php');
        WP_Filesystem();
    }

	@set_time_limit( 60 * 10 );
	/**
	 * @var WP_Upgrader $upgrader
	 */
	$upgrader = new WP_Upgrader( new Import_Pack_Auto_Install_Upgrader_Skin() );
	$upgrader->generic_strings();
    $download = $upgrader->download_package( $package );

		if ( is_wp_error( $download ) ) {
			return array(
				'success' => false,
				'data'    => array(
					'message' => $download->get_error_message()
				)
			);
    }

		//Unzips the file into a temporary directory
    $working_dir = $upgrader->unpack_package( $download, true );

		if ( is_wp_error( $working_dir ) ) {
			return array(
				'success' => false,
				'data'    => array(
					'message' => $working_dir->get_error_message()
				)
			);
    }

		if ( $folder ) {
			/**
			 * @var WP_Filesystem_Base $wp_filesystem
			 */
      global $wp_filesystem;

			$upgrade_folder = $wp_filesystem->wp_content_dir() . 'upgrade/';
			$source         = $upgrade_folder . $folder;
			if ( $wp_filesystem->move( $working_dir, $source, true ) ) {
				$working_dir = $source;
			};
    }

		if ( is_wp_error( $working_dir ) ) {
			return array(
				'success' => false,
				'data'    => array(
					'message' => $working_dir->get_error_message()
				)
			);
    }

		$result = $upgrader->install_package( array(
			'source'                      => $working_dir,
			'destination'                 => $destination,
			'clear_destination'           => true,
			'abort_if_destination_exists' => false,
			'clear_working'               => true,
			'hook_extra'                  => $hook_extra
    ) );

		return array(
			'success' => true,
			'data'    => array(
				'message' => $result
			)
		);

	}
}

class Import_Pack_Plugin_Installer_Helper extends Import_Pack_Installer_Helper {

    /**
	 * Install multiple plugin in sa me time.
	 *
	 * @param array $plugins
	 * @param bool $force - in case the plugin is installed already, re-install it
	 *
	 * @return array
	 */
	public static function bulk_install( $plugins, $force = false ) {
		$status = array();
		foreach ( $plugins as $plugin ) {
			$status[ $plugin['slug'] ]['install'] = self::install( $plugin, $force );
		}
		return $status;
    }

	/**
	 * Install plugin
	 *
	 * @param array $plugin
	 * @param bool $force - in case the plugin is installed already, re-install it
	 *
	 * @return array
	 */
	public static function install( $plugin, $force = false ) {

		return self::is_installed( $plugin ) && ! $force
			? array(
				'success' => true,
				'data'    => array(
					'message' => esc_html__( 'Plugin is already installed.', 'woozio' )
				)
			)
			: self::process_package( $plugin, 'install' );
    }

	public static function process_package( $plugin, $action ) {
        @set_time_limit( 60 * 5 );

		$download = self::get_link($plugin);
		if ( is_wp_error( $download ) ) {
			return array(
				'success' => false,
				'data'    => array(
					'message' => $download->get_error_message()
				)
			);
    }

		return self::download_and_install_a_package( $download, WP_PLUGIN_DIR, array(
			'type'   => 'plugin',
			'action' => $action
		) );
  }

	public static function activate( $plugin ) {

		if ( ! self::is_installed( $plugin ) ) {
			return array(
				'success' => false,
				'data'    => array(
					'message' => sprintf( esc_html__( 'It was not possible to activate %s. Because it isn\'t installed.', 'woozio' ), $plugin['name'] )
				)
			);
    }

		if ( self::is_active( $plugin ) ) {
			return array(
				'success' => true,
			);
    }

		$activate = activate_plugin( self::get_name( $plugin['slug'] ), $redirect = '' , $network_wide = false, $silent = true );

		if ( is_wp_error( $activate ) ) {
			return array(
				'success' => false,
				'data'    => array(
					'message' => $activate->get_error_message()
				)
			);
		}

		return array(
			'success' => true
		);
  }

	public static function bulk_activate( $plugins ) {

		if ( ! current_user_can( 'activate_plugins' ) ) {
			return array(
				'message' => sprintf( esc_html__( 'Current user can\'t activate plugins.', 'woozio' ), WP_PLUGIN_DIR ),
			);
		}
		$status = array();
		wp_clean_plugins_cache( false );

		foreach ( $plugins as $plugin ) {
			$status[ $plugin['slug'] ]['activate'] = self::activate( $plugin );
		}
		return $status;
    }

	private static function api( $action, $args = null ) {

		if ( is_array( $args ) ) {
			$args = (object) $args;
		}
		if ( ! isset( $args->per_page ) ) {
			$args->per_page = 24;
        }

		$args = apply_filters( 'plugins_api_args', $args, $action );
		$res  = apply_filters( 'plugins_api', false, $action, $args );
		if ( false === $res ) {
			$url = 'http://api.wordpress.org/plugins/info/1.0/';
			if ( wp_http_supports( array( 'ssl' ) ) ) {
				$url = set_url_scheme( $url, 'https' );
			}
			$request = wp_remote_post( $url, array(
				'timeout' => 15,
				'body'    => array(
					'action'  => $action,
					'request' => serialize( $args )
				)
			) );
			if ( is_wp_error( $request ) ) {
				$res = new WP_Error( 'plugins_api_failed', esc_html__( 'An unexpected error occurred. Something may be wrong with WordPress.org or this server&#8217;s configuration. If you continue to have problems, please try the <a href="http://wordpress.org/support/">support forums</a>.', 'woozio' ), $request->get_error_message() );
			} else {
				$res = maybe_unserialize( wp_remote_retrieve_body( $request ) );
				if ( ! is_object( $res ) && ! is_array( $res ) ) {
					$res = new WP_Error( 'plugins_api_failed', esc_html__( 'An unexpected error occurred. Something may be wrong with WordPress.org or this server&#8217;s configuration. If you continue to have problems, please try the <a href="http://wordpress.org/support/">support forums</a>.', 'woozio' ), wp_remote_retrieve_body( $request ) );
				}
			}
		} elseif ( ! is_wp_error( $res ) ) {
			$res->external = true;
        }

		return apply_filters( 'plugins_api_result', $res, $action, $args );
	}
	/**
	 * Return plugin path name with plugin file
     *
	 * @param $slug
	 * @return null|string
	 */
	public static function get_name( $slug ) {

		$data = get_plugins( "/$slug" );
		if ( empty( $data ) ) {
			return null;
		}

		$file = array_keys( $data );
		$file = array_filter($file, function($f) {
			$explode_data = explode('/', $f);
			return count($explode_data) == 1;
		});

		return $slug . '/' . $file[0];
  }

	/**
	 * Return plugin path name with plugin file
     *
	 * @param $slug
	 * @return null|string
	 */
	public static function get_path( $slug ) {

		$data = get_plugins( "/$slug" );
		if ( empty( $data ) ) {
			return null;
		}

		$file = array_keys( $data );
		$file = array_filter($file, function($f) {
			$explode_data = explode('/', $f);
			return count($explode_data) == 1;
		});

		return $slug . '/' . $file[0];
  }

	/**
	 * Return the plugin data in case the plugin is active,
	 * in other case returns null
	 *
	 * @param $slug
	 *
	 * @return array|null
	 */
	public static function get_data( $slug ) {
		$data = get_plugins( "/$slug" );
		return empty($data) ? null : array_shift($data);
    }

	/**
	 * Check if the plugin is installed
	 *
	 * @param array $plugin - Plugin slug name
	 *
	 * @return bool
	 */
	public static function is_installed( $plugin ) {
		return ! ( self::get_data( $plugin['slug'] ) === null );
  }

	/**
	 * Checks is the plugin is active
	 *
	 * @param array $plugin
	 *
	 * @return bool
	 */
	public static function is_active( $plugin ) {

		if ( ! function_exists( 'is_plugin_active' ) ) {
			get_template_part( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		if( function_exists('import_pack_check_plugin_is_active') ) {
			return ! self::is_installed( $plugin ) ? false : import_pack_check_plugin_is_active( self::get_name( $plugin['slug'] ) );
		} else {
			return true;
		}
    }

	public static function get_link( $plugin ) {
		if ( isset( $plugin['source'] ) ) {
			return $plugin['source'];
		}
		$call_api = self::api( 'plugin_information', array( 'slug' => $plugin['slug'] ) );
		if ( is_wp_error( $call_api ) ) {
			return $call_api;
		}
		return $call_api->download_link;
	}
}

class Import_Pack_Auto_Install_Upgrader_Skin extends WP_Upgrader_Skin {
	public function feedback($string, ...$args) {
		return;
	}
}

if(! function_exists('Import_Pack_check_plugin_is_active')) {
    /**
     * @since 1.0.0
     * is_plugin_active
     */
    function Import_Pack_check_plugin_is_active( $plugin ) {
        return is_plugin_active( $plugin );
    }
}
