<?php
/**
 * Meta class
 *
 * @author  YITH
 * @package YITH WooCommerce Waiting List
 * @version 1.0.0
 */

if ( ! defined( 'YITH_WCWTL' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WCWTL_Meta' ) ) {
	/**
	 * Product metabox class.
	 * The class manage the products metabox for waitlist.
	 *
	 * @since 1.0.0
	 */
	class YITH_WCWTL_Meta {

		/**
		 * Single instance of the class
		 *
		 * @since 1.0.0
		 * @var YITH_WCWTL_Meta
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
		 * Returns single instance of the class
		 *
		 * @since 1.0.0
		 * @return YITH_WCWTL_Meta
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
		 * @since  1.0.0
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function __construct() {

			// Enqueue script.
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );

			// Ajax send mail.
			add_action( 'wp_ajax_yith_waitlist_send_mail', array( $this, 'yith_waitlist_send_mail_ajax' ) );
			add_action( 'wp_ajax_nopriv_yith_waitlist_send_mail', array( $this, 'yith_waitlist_send_mail_ajax' ) );
		}

		/**
		 * Enqueue scripts
		 *
		 * @access public
		 * @since  1.0.0
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function enqueue_scripts() {
			$min = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
			wp_enqueue_script( 'yith-waitlist-metabox', YITH_WCWTL_ASSETS_URL . '/js/metabox' . $min . '.js', array( 'jquery' ), YITH_WCWTL_VERSION, true );

			wp_localize_script(
				'yith-waitlist-metabox',
				'yith_wcwtl_meta',
				array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
				)
			);
		}

		/**
		 * Check product and call add_meta function
		 *
		 * @access public
		 * @since  1.0.0
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function add_meta_box() {

			global $post;

			if ( get_post_type( $post ) !== 'product' ) {
				return;
			}

			$title = __( 'Waiting list', 'yith-woocommerce-waiting-list' );
			// Get product.
			$product_id = $post->ID;
			$product    = wc_get_product( $product_id );

			if ( $product->is_type( array( 'simple', 'ticket-event' ) ) && ! $product->is_in_stock() ) {
				// Add metabox.
				$this->add_meta( $product_id, $title );
			} elseif ( $product->is_type( 'variable' ) ) {
				// Get variation.
				$variations = $product->get_available_variations();

				foreach ( $variations as $variation ) {

					if ( isset( $variation['is_in_stock'] ) && $variation['is_in_stock'] ) {
						continue;
					}
					// translators: %s: Stand for a product variation ID.
					$title = sprintf( __( 'Waiting list for the variation: #%s', 'yith-woocommerce-waiting-list' ), $variation['variation_id'] );
					$this->add_meta( $variation['variation_id'], $title );
				}
			}
		}

		/**
		 * Add waitlist metabox on edit product page
		 *
		 * @access public
		 * @since  1.0.0
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 * @param string $id    Product or Variation id.
		 * @param string $title The meta title.
		 */
		public function add_meta( $id, $title ) {

			$title = apply_filters( 'yith_wcwtl_metabox_waitlist_title', $title );

			add_meta_box(
				YITH_WCWTL_META . $id,
				$title,
				array( $this, 'build_meta_box' ),
				'product',
				'side',
				'default',
				$id
			);
		}

		/**
		 * Callback function to output metabox in product edit page
		 *
		 * @access public
		 * @since  1.0.0
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 * @param WC_Product $product The product instance.
		 * @param array      $args Callback args.
		 */
		public function build_meta_box( $product, $args ) {
			// get product id.
			$id = $args['args'];
			// get users.
			$users = yith_waitlist_get_registered_users( $id );

			if ( ! empty( $users ) ) {
				echo '<p class="users-on-waitlist">';

				// translators: %s: Stad for the number of users in waitlist for a product.
				echo sprintf( _n( 'There is %s user in the waiting list for this product', 'There are %s users in the waiting list for this product', count( $users ), 'yith-woocommerce-waiting-list' ), count( $users ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '</p>';
			} else {
				esc_html_e( 'There are no users in this waiting list', 'yith-woocommerce-waiting-list' );
			}

			do_action( 'yith-wcwtl-before-send-button', $users, $id ); // phpcs:ignore WordPress.NamingConventions

			if ( ! empty( $users ) ) {
				$this->button_to_send_mail( $id );
			}

			echo '<p class="response-message"></p>';
		}

		/**
		 * Add button for send mail in metabox on product edit page
		 *
		 * @access public
		 * @since  1.0.0
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 * @param  integer|string $id The product ID.
		 * @return void
		 */
		public function button_to_send_mail( $id ) {
			?>
			<input type="button" class="button yith-waitlist-send-mail" data-product_id="<?php echo esc_attr( $id ); ?>"
				value="<?php echo esc_attr( apply_filters( 'yith_wcwtl_button_send_mail_label', __( 'Send the email to the users', 'yith-woocommerce-waiting-list' ) ) ); ?>"/>
			<?php
		}

		/**
		 * Ajax action for send mail to waitlist users
		 *
		 * @access public
		 * @since  1.0.0
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function yith_waitlist_send_mail_ajax() {

			if ( ! isset( $_REQUEST['product'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				die();
			}

			$product_id = absint( $_REQUEST['product'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$product    = wc_get_product( $product_id );

			// Get waiting list users for product.
			$users = yith_waitlist_get_registered_users( $product );

			if ( ! empty( $users ) ) {
				// Send mail.
				do_action( 'send_yith_waitlist_mail_instock', $users, $product_id );
			}

			$response = apply_filters( 'yith_wcwtl_mail_instock_send_response', false );

			// Check response.
			if ( $response ) {
				$msg  = apply_filters( 'yith_wcwtl_send_mail_success', __( 'Email sent correctly.', 'yith-woocommerce-waiting-list' ) );
				$send = true;
				// Empty waitlist.
				yith_waitlist_empty( $product );
			} else {
				$msg  = apply_filters( 'yith_wcwtl_send_mail_error', __( 'An error has occurred, please try again.', 'yith-woocommerce-waiting-list' ) );
				$send = false;
			}

			// Pass param to js.
			echo wp_json_encode(
				array(
					'msg'  => $msg,
					'send' => $send,
				)
			);

			die();
		}
	}
}

/**
 * Unique access to instance of YITH_WCWTL_Meta class
 *
 * @since 1.0.0
 * @return YITH_WCWTL_Meta
 */
function YITH_WCWTL_Meta() {// phpcs:ignore WordPress.NamingConventions
	return YITH_WCWTL_Meta::get_instance();
}
