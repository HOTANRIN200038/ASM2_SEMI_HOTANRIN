<?php
/**
 * Admin class
 *
 * @author  YITH
 * @package YITH WooCommerce Waiting List
 * @version 1.0.0
 */

if ( ! defined( 'YITH_WCWTL' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WCWTL_Admin' ) ) {
	/**
	 * Admin class.
	 * The class manage all the admin behaviors.
	 *
	 * @since 1.0.0
	 */
	class YITH_WCWTL_Admin {

		/**
		 * Single instance of the class
		 *
		 * @since 1.0.0
		 * @var YITH_WCWTL_Admin
		 */
		protected static $instance;

		/**
		 * Plugin options
		 *
		 * @since  1.0.0
		 * @var array
		 * @access public
		 */
		public $options = array();

		/**
		 * Plugin version
		 *
		 * @since 1.0.0
		 * @var string
		 */
		public $version = YITH_WCWTL_VERSION;

		/**
		 * Panel Object
		 *
		 * @var $panel
		 */
		protected $panel;

		/**
		 * Premium tab template file name
		 *
		 * @var $premium string
		 */
		protected $premium = 'premium.php';

		/**
		 * Premium version landing link
		 *
		 * @var string
		 */
		protected $premium_landing = 'https://yithemes.com/themes/plugins/yith-woocommerce-waiting-list/';

		/**
		 * Waiting List panel page
		 *
		 * @var string
		 */
		protected $panel_page = 'yith_wcwtl_panel';

		/**
		 * Various links
		 *
		 * @since  1.0.0
		 * @var string
		 * @access public
		 */
		public $doc_url = 'https://yithemes.com/docs-plugins/yith-woocommerce-waiting-list/';

		/**
		 * Returns single instance of the class
		 *
		 * @since 1.0.0
		 * @return YITH_WCWTL_Admin
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @access public
		 * @since  1.0.0
		 */
		public function __construct() {

			add_action( 'admin_menu', array( $this, 'register_panel' ), 5 );

			// Add action links.
			add_filter( 'plugin_action_links_' . plugin_basename( YITH_WCWTL_DIR . '/' . basename( YITH_WCWTL_FILE ) ), array( $this, 'action_links' ) );
			add_filter( 'yith_show_plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 5 );

			if ( ! ( defined( 'YITH_WCWTL_PREMIUM' ) && YITH_WCWTL_PREMIUM ) ) {
				add_action( 'yith_waiting_list_premium', array( $this, 'premium_tab' ) );
			}

			add_action( 'yith_wcwtl_email_instock_settings', array( $this, 'email_instock_settings' ) );

			// Admin assets.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_style' ), 10 );

			// YITH WCWTL Loaded.
			do_action( 'yith_wcwtl_loaded' );
		}

		/**
		 * Admin style for plugin panel
		 *
		 * @since 1.5.0
		 * @author Francesco Licandro
		 * @return void
		 */
		public function admin_style() {
			wp_register_style( 'ywcwtl-admin', YITH_WCWTL_ASSETS_URL . '/css/admin.css', array(), YITH_WCWTL_VERSION, 'all' );
			if ( isset( $_GET['page'] ) && sanitize_text_field( wp_unslash( $_GET['page'] ) ) === $this->panel_page ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				wp_enqueue_style( 'ywcwtl-admin' );
			}
		}

		/**
		 * Action Links
		 * add the action links to plugin admin page
		 *
		 * @since         1.0
		 * @author        Andrea Grillo <andrea.grillo@yithemes.com>
		 * @param   array $links | links plugin array.
		 * @return  array
		 * @use      plugin_action_links_{$plugin_file_name}
		 */
		public function action_links( $links ) {
			$links = yith_add_action_links( $links, $this->panel_page, false );

			return $links;
		}

		/**
		 * Add a panel under YITH Plugins tab
		 *
		 * @since    1.0
		 * @author   Andrea Grillo <andrea.grillo@yithemes.com>
		 * @use      /Yit_Plugin_Panel class
		 * @return   void
		 * @see      plugin-fw/lib/yit-plugin-panel.php
		 */
		public function register_panel() {

			if ( ! empty( $this->panel ) ) {
				return;
			}

			$admin_tabs = array(
				'general'       => __( 'Settings', 'yith-woocommerce-waiting-list' ),
				'email-instock' => __( 'In Stock Email Settings', 'yith-woocommerce-waiting-list' ),
			);

			if ( ! ( defined( 'YITH_WCWTL_PREMIUM' ) && YITH_WCWTL_PREMIUM ) ) {
				$admin_tabs['premium'] = __( 'Premium Version', 'yith-woocommerce-waiting-list' );
			}

			$args = array(
				'create_menu_page' => apply_filters( 'yith-wcwtl-register-panel-create-menu-page', true ), // phpcs:ignore WordPress.NamingConventions
				'parent_slug'      => '',
				'page_title'       => 'YITH WooCommerce Waiting List',
				'menu_title'       => 'Waiting List',
				'capability'       => apply_filters( 'yith-wcwtl-register-panel-capabilities', 'manage_options' ), // phpcs:ignore WordPress.NamingConventions
				'parent'           => '',
				'parent_page'      => apply_filters( 'yith-wcwtl-register-panel-parent-page', 'yith_plugin_panel' ), // phpcs:ignore WordPress.NamingConventions
				'page'             => $this->panel_page,
				'admin-tabs'       => apply_filters( 'yith-wcwtl-admin-tabs', $admin_tabs ), // phpcs:ignore WordPress.NamingConventions
				'options-path'     => YITH_WCWTL_DIR . '/plugin-options',
				'class'            => yith_set_wrapper_class(),
				'plugin_slug'      => YITH_WCWTL_SLUG,
			);

			/* === Fixed: not updated theme  === */
			if ( ! class_exists( 'YIT_Plugin_Panel_WooCommerce' ) ) {
				require_once YITH_WCWTL_DIR . '/plugin-fw/lib/yit-plugin-panel-wc.php';
			}

			$this->panel = new YIT_Plugin_Panel_WooCommerce( $args );
		}

		/**
		 * Premium Tab Template
		 * Load the premium tab template on admin page
		 *
		 * @since    1.0
		 * @author   Andrea Grillo <andrea.grillo@yithemes.com>
		 * @return   void
		 */
		public function premium_tab() {
			$premium_tab_template = YITH_WCWTL_TEMPLATE_PATH . '/admin/' . $this->premium;
			if ( file_exists( $premium_tab_template ) ) {
				include_once $premium_tab_template;
			}

		}

		/**
		 * Plugin_row_meta
		 * add the action links to plugin admin page
		 *
		 * @since    1.0
		 * @author   Andrea Grillo <andrea.grillo@yithemes.com>
		 * @use      plugin_row_meta
		 * @param array    $new_row_meta_args An array of plugin row meta.
		 * @param string[] $plugin_meta       An array of the plugin's metadata,
		 *                                    including the version, author,
		 *                                    author URI, and plugin URI.
		 * @param string   $plugin_file       Path to the plugin file relative to the plugins directory.
		 * @param array    $plugin_data       An array of plugin data.
		 * @param string   $status            Status of the plugin. Defaults are 'All', 'Active',
		 *                                    'Inactive', 'Recently Activated', 'Upgrade', 'Must-Use',
		 *                                    'Drop-ins', 'Search', 'Paused'.
		 *
		 * @return array
		 */
		public function plugin_row_meta( $new_row_meta_args, $plugin_meta, $plugin_file, $plugin_data, $status ) {

			if ( defined( 'YITH_WCWTL_INIT' ) && YITH_WCWTL_INIT === $plugin_file ) {
				$new_row_meta_args['slug'] = YITH_WCWTL_SLUG;

				if ( defined( 'YITH_WCWTL_PREMIUM' ) ) {
					$new_row_meta_args['is_premium'] = true;
				}
			}

			return $new_row_meta_args;
		}

		/**
		 * Get the premium landing uri
		 *
		 * @since   1.0.0
		 * @author  Andrea Grillo <andrea.grillo@yithemes.com>
		 * @return  string The premium landing link
		 */
		public function get_premium_landing_uri() {
			return apply_filters( 'yith_plugin_fw_premium_landing_uri', $this->premium_landing, YITH_WCWTL_SLUG );
		}

		/**
		 * Duplicate email options in plugin settings
		 *
		 * @access public
		 * @since  1.0.0
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function email_instock_settings() {

			if ( file_exists( YITH_WCWTL_DIR . '/templates/admin/email-settings-tab.php' ) ) {
				global $current_section;
				$current_section = 'yith_wcwtl_mail_instock';

				$mailer = WC()->mailer();
				$class  = $mailer->emails['YITH_WCWTL_Mail_Instock'];

				WC_Admin_Settings::get_settings_pages();

				if ( ! empty( $_POST ) ) { // phpcs:ignore
					$class->process_admin_options();
				}

				include_once YITH_WCWTL_DIR . '/templates/admin/email-settings-tab.php';
			}
		}

	}
}
/**
 * Unique access to instance of YITH_WCWTL_Admin class
 *
 * @since 1.0.0
 * @return YITH_WCWTL_Admin
 */
function YITH_WCWTL_Admin() { // phpcs:ignore WordPress.NamingConventions
	return YITH_WCWTL_Admin::get_instance();
}
