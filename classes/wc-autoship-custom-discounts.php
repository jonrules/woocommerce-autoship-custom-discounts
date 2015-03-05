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
	public static function calculate_product_discount( $product, $item, $schedule, $customer ) {
		$discount = 0.0;
		$category_triggers = self::get_discount_category_triggers();
		$items = $schedule->get_items();
		
		// Category triggers
		foreach ( $category_triggers as $trigger ) {/** @var WC_Autoship_Custom_Discounts_Category_Trigger $trigger **/
			if ( ! self::product_is_in_category( $product->id, array( $trigger->get_discount_category() ) ) ) {
				continue;
			}
			
			$trigger_quantity = 0;
			$trigger_category = array( $trigger->get_trigger_category() );
			foreach ( $items as $line_item ) {/** @var WC_Autoship_Schedule_Item $line_item **/
				$in_trigger_category = self::product_is_in_category( $line_item->get_product_id(), $trigger_category );
				if ( $in_trigger_category ) {
					$trigger_count += $line_item->get( 'qty' );
				}
				if ( $trigger_count >= $trigger->get_quantity() ) {
					// Trigger is active
					$discount = max( $trigger->get_discount(), $discount );
					break;
				}
			}
		}
		
		return $discount;
	}
	
	/**
	 * @param WC_Autoship_Customer $customer
	 */
	public static function calculate_user_role_discount( $customer ) {
		$discount = 0.0;
		$discount_user_roles = self::get_discount_user_roles();
		
		foreach ( $discount_user_roles as $role ) { /** @var WC_Autoship_Custom_Discounts_User_Role $role **/
			if ( self::user_has_role( $customer->get_id(), $role ) ) {
				$discount = max( $role->get_discount(), $discount );
			}
		}
		
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
	function user_has_role( $user_id, $role ) {
		$user = get_userdata( $user_id );
		if ( empty( $user ) ) {
			return false;
		}
		return ( in_array( $role->get_slug(), (array) $user->roles ) );
	}
}