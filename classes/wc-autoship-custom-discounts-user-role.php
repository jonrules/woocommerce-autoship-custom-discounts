<?php

class WC_Autoship_Custom_Discounts_User_Role {
	/**
	 * User role slug
	 * @var string
	 */
	private $_slug;
	/**
	 * Item discount rate
	 * @var double
	 */
	private $_discount_rate;
	
	/**
	 * 
	 * @param string $slug
	 * @param double $discount
	 */
	public function __construct( $slug, $discount_rate ) {
		$this->set_slug( $slug );
		$this->set_discount_rate( $discount_rate );
	}
	
	public function get_slug() {
		return $this->_slug;
	}
	
	public function set_slug( $slug ) {
		$this->_slug = $slug;
	}
	
	public function get_discount_rate() {
		return $this->_discount_rate;
	}
	
	public function set_discount_rate( $discount_rate ) {
		$this->_discount_rate = $discount_rate;
	}
}