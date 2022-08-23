<?php
/**
 * Functions
 *
 * @author  YITH
 * @package YITH WooCommerce Colors and Labels Variations
 * @version 1.1.0
 */

defined( 'YITH_WCCL' ) || exit; // Exit if accessed directly.

if ( ! function_exists( 'ywccl_get_term_meta' ) ) {
	/**
	 * Get term meta. If WooCommerce version is >= 2.6 use get_term_meta else use get_woocommerce_term_meta
	 *
	 * @author Francesco Licandro*
	 * @param int    $term_id Term ID.
	 * @param string $key     Optional. The meta key to retrieve. By default,
	 *                        returns data for all keys. Default empty.
	 * @param bool   $single  Optional. Whether to return a single value.
	 *                        This parameter has no effect if $key is not specified.
	 *                        Default false.
	 * @param string $taxonomy Optional. The taxonomy slug.
	 * @return mixed
	 * @depreacted
	 */
	function ywccl_get_term_meta( $term_id, $key, $single = true, $taxonomy = '' ) {
		$value = get_term_meta( $term_id, $key, $single );

		// Compatibility with old format. To be removed on next version.
		if ( apply_filters( 'yith_wccl_get_term_meta', true, $term_id ) && ( false === $value || '' === $value ) && ! empty( $taxonomy ) ) {
			$value = get_term_meta( $term_id, $taxonomy . $key, $single );
			// If meta is not empty, save it with the new key.
			if ( false !== $value && '' !== $value ) {
				ywccl_update_term_meta( $term_id, $key, $value );
			}
		}

		return $value;
	}
}

if ( ! function_exists( 'ywccl_update_term_meta' ) ) {
	/**
	 * Get term meta. If WooCommerce version is >= 2.6 use update_term_meta else use update_woocommerce_term_meta
	 *
	 * @author Francesco Licandro
	 * @param int    $term_id    Term ID.
	 * @param string $meta_key   Metadata key.
	 * @param mixed  $meta_value Metadata value. Must be serializable if non-scalar.
	 * @param mixed  $prev_value Optional. Previous value to check before updating.
	 *                           If specified, only update existing metadata entries with
	 *                           this value. Otherwise, update all entries. Default empty.
	 * @return mixed
	 * @depreacted
	 */
	function ywccl_update_term_meta( $term_id, $meta_key, $meta_value, $prev_value = '' ) {
		if ( '' === $meta_value || false === $meta_value ) {
			return delete_term_meta( $term_id, $meta_key );
		}

		return update_term_meta( $term_id, $meta_key, $meta_value, $prev_value );
	}
}

if ( ! function_exists( 'ywccl_check_wc_version' ) ) {
	/**
	 * Check installed WooCommerce version
	 *
	 * @since  1.3.0
	 * @author Francesco Licandro
	 * @param string $version The version to check.
	 * @param string $operator The operator to use on check function.
	 * @return boolean
	 * @deprecated
	 */
	function ywccl_check_wc_version( $version, $operator ) {
		return version_compare( WC()->version, $version, $operator );
	}
}

if ( ! function_exists( 'ywccl_get_custom_tax_types' ) ) {
	/**
	 * Return custom product's attributes type
	 *
	 * @since  1.2.0
	 * @author Francesco Licandro
	 * @return array
	 */
	function ywccl_get_custom_tax_types() {
		return apply_filters(
			'yith_wccl_get_custom_tax_types',
			array(
				'colorpicker' => __( 'Colorpicker', 'yith-color-and-label-variations-for-woocommerce' ),
				'image'       => __( 'Image', 'yith-color-and-label-variations-for-woocommerce' ),
				'label'       => __( 'Label', 'yith-color-and-label-variations-for-woocommerce' ),
			)
		);
	}
}
