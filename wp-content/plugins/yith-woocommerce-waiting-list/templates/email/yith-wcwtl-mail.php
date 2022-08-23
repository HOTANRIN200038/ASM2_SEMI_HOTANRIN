<?php
/**
 * YITH WooCommerce Waiting List Mail Template
 *
 * @author YITH
 * @package YITH WooCommerce Waiting List
 * @version 1.0.0
 */

if ( ! defined( 'YITH_WCWTL' ) ) {
	exit; // Exit if accessed directly.
}

do_action( 'woocommerce_email_header', $email_heading, $email );
?>

<p>
	<?php echo esc_html_x( 'Hi There,', 'Email salutation', 'yith-woocommerce-waiting-list' ); ?>
</p>

<?php echo wp_kses_post( wpautop( wptexturize( $email_content ) ) ); ?>

<?php do_action( 'woocommerce_email_footer', $email ); ?>
