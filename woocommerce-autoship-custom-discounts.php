<?php
/*
Plugin Name: WC Auto-Ship Custom Discounts
Plugin URI: http://patternsinthecloud.com
Description: Apply rule-based discounts to products on Auto-Ship.
Version: 1.0.2
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
	 * @param double $discount
	 * @param WC_Autoship_Customer $customer
	 * @param WC_Autoship_Schedule $schedule
	 */
	function wc_autoship_custom_discounts_order_discount( $discount, $customer, $schedule ) {
		require_once( 'classes/wc-autoship-custom-discounts.php' );
		$discount = WC_Autoship_Custom_Discounts::calculate_discount( $customer, $schedule );
		return $discount;
	}
// 	add_filter( 'wc_autoship_discount', 'wc_autoship_custom_discounts_order_discount', 10, 3 );
	
	/**
	 * Caculate autoship line discount
	 * @param float $discount
	 * @param int $item_id
	 * @param float $autoship_price
	 * @return float;
	 */
	function wc_autoship_custom_discounts_line_discount( $discount, $item_id, $autoship_price ) {
		$item = new WC_Autoship_Schedule_item( $item_id );
		require_once( 'classes/wc-autoship-custom-discounts.php' );
		$discount = WC_Autoship_Custom_Discounts::calculate_line_discount( $item, $autoship_price );
		return $discount;
	}
	add_filter( 'wc_autoship_line_discount', 'wc_autoship_custom_discounts_line_discount', 10, 3 );
}
