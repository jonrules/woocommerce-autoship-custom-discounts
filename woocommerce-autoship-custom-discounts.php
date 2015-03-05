<?php
/*
Plugin Name: WC Auto-Ship Custom Discounts
Plugin URI: http://patternsinthecloud.com
Description: Apply rule-based discounts to products on Auto-Ship.
Version: 1.0
Author: Patterns in the Cloud
Author URI: http://patternsinthecloud.com
License: Single-site
*/

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
	
	/**
	 * Activate hook
	 */
	function wc_autoship_custom_discounts_activate() {
		
	}
	register_activation_hook( __FILE__, 'wc_autoship_custom_discounts_activate' );
	
	/**
	 * Deactivate hook
	 */
	function wc_autoship_custom_discounts_deactivate() {
		
	}
	register_deactivation_hook( __FILE__, 'wc_autoship_custom_discounts_deactivate' );
	
	/**
	 * Uninstall hook
	 */
	function wc_autoship_custom_discounts_uninstall() {
		
	}
	register_uninstall_hook( __FILE__, 'wc_autoship_custom_discounts_uninstall' );
	
	/**
	 * 
	 * @param double $price
	 * @param WC_Product $product
	 * @param WC_Autoship_Schedule_Item $item
	 * @param WC_Autoship_Schedule $schedule
	 * @param WC_Autoship_Customer $customer
	 * @return double
	 */
	function wc_autoship_custom_discounts_product_price( $price, $product, $item, $schedule, $customer ) {
		require_once( 'classes/wc-autoship-custom-discounts.php' );
		$discount = WC_Autoship_Custom_Discounts::calculate_discount( $product, $item, $schedule, $customer );
		if ( $discount > 0.0 && $discount <= 1.0 ) {
			return $price * ( 1 - $discount );
		}
		return $price;
	}
	add_filter( 'wc_autoship_product_price', 'wc_autoship_custom_discounts_product_price', 10, 5 );
	
	
	
}
