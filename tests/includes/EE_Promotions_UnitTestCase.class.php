<?php
/**
 * All promotions unit tests extend this class
 *
 * @since 		1.0.0
 * @package 		EE4 Promotions
 * @subpackage 	tests
 */
class EE_Promotions_UnitTestCase extends EE_UnitTestCase {


	/**
	 * returns an array of EE_Promotion objects that are with various start and end dates.
	 * The objects have been saved to the db for the test.
	 *
	 * @return EE_Promotion[]
	 */
	protected function _demo_promotions() {
		//require_once( EE_PROMOTIONS_CORE . 'db_classes' . DS . 'EE_Promotion.class.php' );
		$day_from_now = time() + DAY_IN_SECONDS;
		$day_and_half_from_now = time() + ( DAY_IN_SECONDS * 1.5 );
		$day_ago = time() - DAY_IN_SECONDS;
		$half_day_ago = time() - ( DAY_IN_SECONDS / 2 );
		$day_and_half_ago = time() - ( DAY_IN_SECONDS * 1.5 );
		$promotions_to_test = array(
			'upcoming_start_no_end' => EE_Promotion::new_instance( array( 'PRO_start' => $day_from_now ) ),
			'upcoming_start_upcoming_end' => EE_Promotion::new_instance( array(
				'PRO_start' => $day_from_now,
				'PRO_end'   => $day_and_half_from_now
			) ),
			'past_start_no_end' => EE_Promotion::new_instance( array(
				'PRO_start' => $day_ago,
				'PRO_code'  => 'test_code_for_promotions'
			) ),
			'past_start_upcoming_end' => EE_Promotion::new_instance( array(
				'PRO_start' => $day_ago,
				'PRO_end'   => $day_and_half_from_now
			) ),
			'past_start_past_end' => EE_Promotion::new_instance( array(
				'PRO_start' => $day_ago,
				'PRO_end'   => $half_day_ago
			) ),
			'no_start_upcoming_end' => EE_Promotion::new_instance( array( 'PRO_end' => $day_and_half_from_now ) ),
			'no_start_past_end' => EE_Promotion::new_instance( array( 'PRO_end' => $day_and_half_ago ) )
		);


		$base_promo_name = 'Promo %s';
		$base_promo_description = 'Promo description for promo %s';
		$base_price_amount = '10';

		//create the price type for flat price discount and for percent based discount.
		/** @type EE_Price_Type $percent_price_type */
		$percent_price_type = $this->factory->price_type->create( array( 'PBT_ID' => EEM_Price_Type::base_type_discount, 'PRT_is_percent' => true ) );
		/** @type EE_Price_Type $flat_price_type */
		$flat_price_type = $this->factory->price_type->create( array( 'PBT_ID' => EEM_Price_Type::base_type_discount, 'PRT_is_percent' => false ) );

		//the first three promos will be percent promos, the last four will be flat price promos
		$count = 1;

		foreach ( $promotions_to_test as $promotion ) {
			//set up Price and add to promo
			$is_percent = $count < 4 ? true : false;

			$price = EE_Price::new_instance( array(
				'PRT_ID' => $is_percent ? $percent_price_type->ID() : $flat_price_type->ID(),
				'PRC_amount' => $base_price_amount * $count,
				'PRC_name' => sprintf( $base_promo_name, $count ),
				'PRC_desc' => sprintf( $base_promo_description, $count )
			));
			$price->save();
			/** @type EE_Promotion $promotion */
			$promotion->_add_relation_to( $price, 'Price' );
			$promotion->save();
			$count++;
		}


		return $promotions_to_test;
	}


}
// end EE_Promotions_UnitTestCase
// Location: /tests/includes/EE_Promotions_UnitTestCase.class.php
