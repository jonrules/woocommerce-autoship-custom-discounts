<?php

class WC_Autoship_Custom_Discounts_Category_Trigger {
	/**
	 * Trigger category
	 * @var string
	 */
	private $_trigger_category;
	/**
	 * Discount category
	 * @var string
	 */
	private $_discount_category;
	/**
	 * Item quantity
	 * @var int
	 */
	private $_quantity;
	/**
	 * Item discount
	 * @var double
	 */
	private $_discount;
	
	/**
	 * 
	 * @param string $trigger_category
	 * @param string $discount_category
	 * @param int $quantity
	 * @param double $discount
	 */
	public function __construct( $trigger_category, $discount_category, $quantity, $discount ) {
		$this->set_trigger_category( $trigger_category );
		$this->set_discount_category( $discount_category );
		$this->set_quantity( $quantity );
		$this->set_discount( $discount );
	}
	
	public function get_trigger_category() {
		return $this->_trigger_category;
	}
	
	public function set_trigger_category( $trigger_category ) {
		$this->_trigger_category = $trigger_category;
	}
	
	public function get_discount_category() {
		return $this->_discount_category;
	}
	
	public function set_discount_category( $discount_category ) {
		$this->_discount_category = $discount_category;
	}
	
	public function get_quantity() {
		return $this->_quantity;
	}
	
	public function set_quantity( $quantity ) {
		$this->_quantity = $quantity;
	}
	
	public function get_discount() {
		return $this->_discount;
	}
	
	public function set_discount( $discount ) {
		$this->_discount = $discount;
	}
}