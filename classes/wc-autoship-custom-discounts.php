<?php

require_once( 'wc-autoship-custom-discounts-category-trigger.php' );

class WC_Autoship_Custom_Discounts {
	private static $_discount_category_triggers;
	
	private static $_discount_user_roles;
	
	/**
	 * 
	 * @return WC_Autoship_Custom_Discounts_Category_Trigger[]
	 */
	public static function get_discount_category_triggers() {
		require_once( 'wc-autoship-custom-discounts-category-trigger.php' );
		if ( empty( self::$_discount_category_triggers ) ) {
			self::$_discount_category_triggers = array(
				new WC_Autoship_Custom_Discounts_Category_Trigger( 'qty-trigger', 'qty-discount', 2, 0.15 ),
			);
		}
		return apply_filters( 'wc-autoship-custom-discounts-category-triggers', self::$_discount_category_triggers );
	}
	
	/**
	 * 
	 * @return WC_Autoship_Custom_Discounts_User_Role[]
	 */
	public static function get_discount_user_roles() {
		require_once( 'wc-autoship-custom-discounts-user-role.php' );
		if ( empty( self::$_discount_user_roles ) ) {
			self::$_discount_user_roles = array(
				new WC_Autoship_Custom_Discounts_User_Role( 'athletes_performance', 0.15 ),
			);
		}
		return apply_filters( 'wc-autoship-custom-discounts-user-roles', self::$_discount_user_roles );
	}
	
	/**
	 * 
	 * @param WC_Product $product
	 * @param WC_Autoship_Schedule_Item $item
	 * @param WC_Autoship_Schedule $schedule
	 * @param WC_Autoship_Customer $customer
	 */
	public static function calculate_product_discount_rate( $product, $item, $schedule, $customer ) {
		$discount_rate = 0.0;
		$category_triggers = self::get_discount_category_triggers();
		$items = $schedule->get_items();
		
		// Category triggers
		foreach ( $category_triggers as $trigger ) { /* @var $trigger WC_Autoship_Custom_Discounts_Category_Trigger */
			if ( ! self::product_is_in_category( $product->id, array( $trigger->get_discount_category() ) ) ) {
				continue;
			}
			
			$trigger_count = 0;
			$trigger_category = array( $trigger->get_trigger_category() );
			foreach ( $items as $line_item ) { /* @var $line_item WC_Autoship_Schedule_Item */
				$in_trigger_category = self::product_is_in_category( $line_item->get_product_id(), $trigger_category );
				if ( $in_trigger_category ) {
					$trigger_count += $line_item->get( 'qty' );
				}
				if ( $trigger_count >= $trigger->get_quantity() ) {
					// Trigger is active
					$discount_rate = max( $trigger->get_discount_rate(), $discount_rate );
					break;
				}
			}
		}
		
		return $discount_rate;
	}
	
	/**
	 * @param WC_Autoship_Customer $customer
	 */
	public static function calculate_user_role_discount_rate( $customer ) {
		$discount_rate = 0.0;
		$discount_rate_user_roles = self::get_discount_user_roles();
		
		foreach ( $discount_rate_user_roles as $role ) { /* @var $role WC_Autoship_Custom_Discounts_User_Role */
			if ( self::user_has_role( $customer->get_id(), $role ) ) {
				$discount_rate = max( $role->get_discount_rate(), $discount_rate );
			}
		}
		
		return $discount_rate;
	}
	
	/**
	 * 
	 * @param WC_Autoship_Customer $customer
	 * @param WC_Autoship_Schedule $schedule
	 */
	public static function calculate_discount( $customer, $schedule ) {
		// Product discount
		$item_discount_total = 0.0;
		$item_subtotal = 0.0;
		$items = $schedule->get_items();
		foreach ( $items as $item ) { /* @var $item WC_Autoship_Schedule_Item */
			$product = $item->get_product();
			$product_price = $product->get_price();
			$item_subtotal += $item->get( 'qty' ) * $product_price;
			$product_discount_rate = self::calculate_product_discount_rate( $product, $item, $schedule, $customer );
			if ( $product_discount_rate > 0.0 && $product_discount_rate <= 1.0 ) {
				$product_discount_price = $product_discount_rate * $product_price * $item->get( 'qty' );
				$item_discount_total += $product_discount_price;
			}
		}
		
		// User role discount
		$user_role_discount_total = 0;
		$user_role_discount_rate = self::calculate_user_role_discount_rate( $customer );
		if ( $user_role_discount_rate > 0.0 && $user_role_discount_rate <= 1.0 ) {
			$user_role_discount_total = $user_role_discount_rate * $item_subtotal;
		}
		
		$discount = max( $item_discount_total, $user_role_discount_total );
		return $discount;
	}
	
	/**
	 * Product is in category
	 * @param int $product_id
	 * @param array $category_slugs
	 * @return boolean
	 */
	public static function product_is_in_category( $product_id, $category_slugs ) {
		$terms = wp_get_post_terms( $product_id, 'product_cat' );
		foreach ( $terms as $term ) {
			if ( in_array( $term->slug, $category_slugs ) ) {
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Check if user has role
	 * @param int $user_id
	 * @param WC_Autoship_Custom_Discounts_User_Role $role
	 * @return boolean
	 */
	public static function user_has_role( $user_id, $role ) {
		$user = get_userdata( $user_id );
		if ( empty( $user ) ) {
			return false;
		}
		return ( in_array( $role->get_slug(), (array) $user->roles ) );
	}
}