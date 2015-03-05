<?php

class WC_Autoship_Custom_Discounts_User_Role {
	/**
	 * User role slug
	 * @var string
	 */
	private $_slug;
	/**
	 * Item discount
	 * @var double
	 */
	private $_discount;
	
	/**
	 * 
	 * @param string $slug
	 * @param double $discount
	 */
	public function __construct( $slug, $discount ) {
		$this->set_slug( $slug );
		$this->set_discount( $discount );
	}
	
	public function get_slug() {
		return $this->_slug;
	}
	
	public function set_slug( $slug ) {
		$this->_slug = $slug;
	}
	
	public function get_discount() {
		return $this->_discount;
	}
	
	public function set_discount( $discount ) {
		$this->_discount = $discount;
	}
}