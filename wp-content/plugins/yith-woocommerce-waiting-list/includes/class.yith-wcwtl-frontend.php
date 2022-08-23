<?php
/**
 * Frontend class
 *
 * @author  YITH
 * @package YITH WooCommerce Waiting List
 * @version 1.1.1
 */

if ( ! defined( 'YITH_WCWTL' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WCWTL_Frontend' ) ) {
	/**
	 * Frontend class.
	 * The class manage all the Frontend behaviors.
	 *
	 * @since 1.0.0
	 */
	class YITH_WCWTL_Frontend {

		/**
		 * Single instance of the class
		 *
		 * @since 1.0.0
		 * @var YITH_WCWTL_Frontend
		 */
		protected static $instance;

		/**
		 * Plugin version
		 *
		 * @since 1.0.0
		 * @var string
		 */
		public $version = YITH_WCWTL_VERSION;

		/**
		 * Frontend script was enqueued
		 *
		 * @since 1.0.0
		 * @var boolean
		 */
		public $scripts_enqueued = false;

		/**
		 * Current object product
		 *
		 * @since 1.0.0
		 * @var object
		 */
		protected $current_product = false;

		/**
		 * Returns single instance of the class
		 *
		 * @since 1.0.0
		 * @return YITH_WCWTL_Frontend
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

			// Add form.
			add_action( 'woocommerce_before_single_product', array( $this, 'add_form' ) );
			// Add form on quick view.
			add_action( 'jck_qv_summary', array( $this, 'add_form' ) );
			add_action( 'yith_wcqv_before_product_summary', array( $this, 'add_form' ) );
			// Add form on get variation AJAX call.
			add_action( 'init', array( $this, 'add_form_ajax' ) );

			add_action( 'template_redirect', array( $this, 'yith_waiting_submit' ), 100 );

			// Enqueue frontend js.
			add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );

			// Shortcode for print form.
			add_shortcode( 'ywcwtl_form', array( $this, 'shortcode_form' ) );
		}

		/**
		 * Register scripts frontend
		 *
		 * @access public
		 * @since  1.0.0
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function register_scripts() {
			$min = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
			wp_register_script( 'yith-wcwtl-frontend', YITH_WCWTL_ASSETS_URL . '/js/frontend' . $min . '.js', array( 'jquery' ), YITH_WCWTL_VERSION, true );
			wp_register_style( 'yith-wcwtl-frontend', YITH_WCWTL_ASSETS_URL . '/css/ywcwtl.css', array(), YITH_WCWTL_VERSION, 'all' );

			$this->enqueue_scripts();
		}

		/**
		 * Enqueue scripts and style
		 *
		 * @since  1.0.8
		 * @access public
		 * @author Francesco Licandro
		 */
		public function enqueue_scripts() {
			if ( ! $this->scripts_enqueued ) {
				wp_enqueue_script( 'yith-wcwtl-frontend' );
				wp_enqueue_style( 'yith-wcwtl-frontend' );

				// All scripts enqueued.
				$this->scripts_enqueued = true;
			}
		}

		/**
		 * Check if the product can have the waitlist form
		 *
		 * @since  1.1.3
		 * @author Francesco Licandro
		 * @param WC_Product $product The product to check.
		 * @return boolean
		 */
		public function can_have_waitlist( $product ) {

			$return = ! ( ! $product->is_type( array( 'simple', 'variable', 'variation', 'ticket-event' ) ) || $product->is_in_stock() );
			// Can third part filter this result.
			return apply_filters( 'yith_wcwtl_can_product_have_waitlist', $return, $product );
		}

		/**
		 * Add form to stock html
		 *
		 * @access public
		 * @since  1.0.0
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function add_form() {
			global $post;

			if ( 'product' === get_post_type( $post->ID ) && is_product() || $this->is_quick_view() ) {

				$this->current_product = wc_get_product( $post->ID );

				if ( ! $this->current_product ) {
					return;
				}

				// Check for WooCommerce 3.0.0.
				if ( function_exists( 'wc_get_stock_html' ) ) {
					add_filter( 'woocommerce_get_stock_html', array( $this, 'output_form_3_0' ), 20, 2 );
				} else {
					if ( $this->current_product->is_type( 'variable' ) ) {
						add_action( 'woocommerce_stock_html', array( $this, 'output_form' ), 20, 3 );
					} else {
						add_action( 'woocommerce_stock_html', array( $this, 'output_form' ), 20, 2 );
					}
				}
			}
		}

		/**
		 * Check if is quick view action
		 *
		 * @since  1.1.5
		 * @author Francesco Licandro
		 * @return boolean
		 */
		public function is_quick_view() {
			return isset( $_REQUEST['action'] ) && 'jckqv' === sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		}

		/**
		 * Add form to stock html
		 *
		 * @access public
		 * @since  1.0.0
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 * @param string             $html Current html.
		 * @param int                $availability The availability.
		 * @param WC_Product|boolean $product The product instance, false otherwise.
		 * @return string
		 */
		public function output_form( $html, $availability, $product = false ) {

			if ( ! $product ) {
				$product = $this->current_product;
			}

			$product_id = $product ? $product->get_id() : 0;

			ob_start();
			echo do_shortcode( '[ywcwtl_form product_id="' . $product_id . '"]' );

			// Then add form to current html.
			$html .= ob_get_clean();

			return $html;
		}

		/**
		 * Output form for WooCommerce 3.0.0
		 *
		 * @since  1.1.0
		 * @author Francesco Licandro
		 * @param string     $html The current output html.
		 * @param WC_Product $product The product instance.
		 * @return string
		 */
		public function output_form_3_0( $html, $product ) {
			return $this->output_form( $html, false, $product );
		}

		/**
		 * Output the form according to product type and user
		 *
		 * @access public
		 * @since  1.0.0
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 * @param  WC_Product $product The product instance.
		 * @return string
		 */
		public function the_form( $product ) {

			$html     = '';
			$user     = wp_get_current_user();
			$waitlist = yith_waitlist_get( $product );
			$url      = $product->get_permalink();

			// Check first id product is excluded.
			if ( is_callable( array( $product, 'get_id' ) ) ) {
				$product_id = $product->get_id();
			} else {
				$product_id = isset( $product->variation_id ) ? $product->variation_id : $product->get_id();
			}

			// set query.
			$url = add_query_arg( YITH_WCWTL_META, $product_id, $url );
			$url = add_query_arg( YITH_WCWTL_META . '-action', 'register', $url );

			// Add message.
			$html .= '<div id="yith-wcwtl-output"><p class="yith-wcwtl-msg">' . get_option( 'yith-wcwtl-form-message' ) . '</p>';

			// Get buttons label from options.
			$label_button_add   = get_option( 'yith-wcwtl-button-add-label' );
			$label_button_leave = get_option( 'yith-wcwtl-button-leave-label' );

			if ( ! $product->is_type( 'variation' ) && ! $user->exists() ) {

				$html .= '<form method="post" action="' . esc_url( $url ) . '">';
				$html .= '<label for="yith-wcwtl-email">' . __( 'Email Address', 'yith-woocommerce-waiting-list' ) . '<input type="email" name="yith-wcwtl-email" id="yith-wcwtl-email" /></label>';
				$html .= '<input type="submit" value="' . esc_html( $label_button_add ) . '" class="button alt" />';
				$html .= '</form>';

			} elseif ( $product->is_type( 'variation' ) && ! $user->exists() ) {

				$html .= '<input type="email" name="yith-wcwtl-email" id="yith-wcwtl-email" class="wcwtl-variation" />';
				$html .= '<a href="' . esc_url( $url ) . '" class="button alt">' . esc_html( $label_button_add ) . '</a>';
			} elseif ( yith_waitlist_user_is_register( $user->user_email, $waitlist ) ) {
				$url   = add_query_arg( YITH_WCWTL_META . '-action', 'leave', $url );
				$html .= '<a href="' . esc_url( $url ) . '" class="button button-leave alt">' . esc_html( $label_button_leave ) . '</a>';
			} else {
				$html .= '<a href="' . esc_url( $url ) . '" class="button alt">' . esc_html( $label_button_add ) . '</a>';
			}

			$html .= '</div>';

			return apply_filters( 'yith_wcwtl_form_html', $html, $product );
		}

		/**
		 * Add user to waitlist
		 *
		 * @access public
		 * @since  1.0.0
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function yith_waiting_submit() {
            // phpcs:disable WordPress.Security.NonceVerification.Recommended
			$user = wp_get_current_user();

			if ( ! ( isset( $_REQUEST[ YITH_WCWTL_META ] ) && is_numeric( $_REQUEST[ YITH_WCWTL_META ] ) && isset( $_REQUEST[ YITH_WCWTL_META . '-action' ] ) ) ) {
				return;
			}

			$action     = sanitize_text_field( wp_unslash( $_REQUEST[ YITH_WCWTL_META . '-action' ] ) );
			$user_email = ( isset( $_REQUEST['yith-wcwtl-email'] ) ) ? sanitize_email( wp_unslash( $_REQUEST['yith-wcwtl-email'] ) ) : $user->user_email;
			$product_id = absint( $_REQUEST[ YITH_WCWTL_META ] );
			$product    = wc_get_product( $product_id );
			// Lets filter redirection url.
			$dest = remove_query_arg( array( YITH_WCWTL_META, YITH_WCWTL_META . '-action', 'yith-wcwtl-email' ) );
			$dest = apply_filters( 'yith_wcwtl_destination_url_after_submit', $dest, $product );

			if ( ! $user->exists() && empty( $_REQUEST['yith-wcwtl-email'] ) ) {
				wc_add_notice( __( 'You must provide a valid email address to join the waiting list of this product', 'yith-woocommerce-waiting-list' ), 'error' );
				wp_safe_redirect( esc_url( $dest ) );
				exit();
			}
            // phpcs:enable WordPress.Security.NonceVerification.Recommended

			// Set standard msg and type.
			$msg      = get_option( 'yith-wcwtl-button-success-msg' );
			$msg_type = 'success';

			// Start user session and set cookies.
			if ( ! isset( $_COOKIE['woocommerce_items_in_cart'] ) ) {
				do_action( 'woocommerce_set_cart_cookies', true );
			}

			if ( 'register' === $action ) {
				// Register user.
				$res = yith_waitlist_register_user( $user_email, $product_id );
				if ( ! $res ) {
					$msg      = __( 'You have already registered for this waiting list', 'yith-woocommerce-waiting-list' );
					$msg_type = 'error';
				}
			} elseif ( 'leave' === $action ) {
				// Unregister user.
				yith_waitlist_unregister_user( $user_email, $product_id );
				$msg = __( 'You have been removed from the waiting list for this product', 'yith-woocommerce-waiting-list' );
			} else {
				$msg      = __( 'An error has occurred. Please try again.', 'yith-woocommerce-waiting-list' );
				$msg_type = 'error';
			}

			// Redirect to product page.
			wc_add_notice( $msg, $msg_type );
			wp_safe_redirect( esc_url( $dest ) );
			exit;
		}

		/**
		 * Shortcode for print waiting list form
		 *
		 * @since  1.0.8
		 * @author Francesco Licandro
		 * @param array $atts Shortcode attributes.
		 * @return string
		 */
		public function shortcode_form( $atts ) {
			extract( // phpcs:ignore WordPress.PHP.DontExtract
				shortcode_atts(
					array(
						'product_id' => '',
					),
					$atts
				)
			);

			if ( $product_id ) {
				$product = wc_get_product( $product_id );
			} else {
				// Get global.
				global $product;
			}

			// Exit if product is null or if can't have waitlist.
			if ( is_null( $product ) || ! $product || ! $this->can_have_waitlist( $product ) ) {
				return '';
			}

			// First enqueue scripts.
			$this->enqueue_scripts();

			ob_start();
			echo $this->the_form( $product ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

			return ob_get_clean();
		}

		/**
		 * Add form on WC AJAX get variations method
		 *
		 * @since  1.3.1
		 * @author Francesco Licandro
		 */
		public function add_form_ajax() {
			if ( ! isset( $_REQUEST['wc-ajax'] ) || 'get_variation' !== sanitize_text_field( wp_unslash( $_REQUEST['wc-ajax'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				return;
			}

			$variable_product = ! empty( $_POST['product_id'] ) ? wc_get_product( absint( $_POST['product_id'] ) ) : false; // phpcs:ignore
			if ( ! $variable_product ) {
				return;
			}

			$data_store   = WC_Data_Store::load( 'product' );
			$variation_id = $data_store->find_matching_product_variation( $variable_product, wp_unslash( $_POST ) ); // phpcs:ignore

			$this->current_product = wc_get_product( $variation_id );

			add_action( 'woocommerce_stock_html', array( $this, 'output_form' ), 20, 2 );
		}
	}
}
/**
 * Unique access to instance of YITH_WCWT_Frontend class
 *
 * @since 1.0.0
 * @return YITH_WCWTL_Frontend
 */
function YITH_WCWTL_Frontend() {// phpcs:ignore WordPress.NamingConventions
	return YITH_WCWTL_Frontend::get_instance();
}
