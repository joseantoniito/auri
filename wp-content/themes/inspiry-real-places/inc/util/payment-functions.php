<?php
if( !function_exists( 'inspiry_completed_payment_handler' ) ) :
	/**
	 * IPN completed payment handler
	 */
	function inspiry_completed_payment_handler( $posted ) {

		global $inspiry_options;

		$paypal_merchant_id     = $inspiry_options[ 'inspiry_paypal_merchant_id' ];
		$publish_on_payment     = $inspiry_options[ 'inspiry_publish_on_payment' ];
		// $payment_notification_email      = is_email( $inspiry_options[ 'inspiry_payment_notification_email' ] );

		if( $posted['business'] == $paypal_merchant_id ) {

			if( isset( $posted['item_number'] ) && ( !empty( $posted['item_number'] ) ) ) {

				$property_id = intval( $posted['item_number'] );
				$property = get_post( $property_id, 'ARRAY_A' );

				if ( $property ) {

					if ( isset( $posted['txn_id'] ) && ( !empty( $posted['txn_id'] ) ) ) {
						update_post_meta( $property_id, 'txn_id', $posted['txn_id'] );
					}

					if ( isset( $posted['payment_date'] ) && ( !empty( $posted['payment_date'] ) ) ) {
						update_post_meta( $property_id, 'payment_date', $posted['payment_date'] );
					}

					if ( isset( $posted['payer_email'] ) && ( !empty( $posted['payer_email'] ) ) ) {
						update_post_meta( $property_id, 'payer_email', $posted['payer_email'] );
					}

					if ( isset( $posted['first_name'] ) && ( !empty( $posted['first_name'] ) ) ) {
						update_post_meta( $property_id, 'first_name', $posted['first_name'] );
					}

					if ( isset( $posted['last_name'] ) && ( !empty( $posted['last_name'] ) ) ) {
						update_post_meta( $property_id, 'last_name', $posted['last_name'] );
					}

					if ( isset( $posted['payment_status'] ) && ( !empty( $posted['payment_status'] ) ) ) {
						update_post_meta( $property_id, 'payment_status', $posted['payment_status'] );
					}

					if ( isset( $posted['payment_gross'] ) && ( !empty( $posted['payment_gross'] ) ) ) {
						update_post_meta( $property_id, 'payment_gross', $posted['payment_gross'] );
					}

					if( isset( $posted['mc_currency'] ) && ( !empty( $posted['mc_currency'] ) ) ) {
						update_post_meta( $property_id, 'mc_currency', $posted['mc_currency'] );
					}

					if ( $publish_on_payment ) {
						$property['post_status'] = 'publish';
						wp_update_post( $property );
					}

					/*
					 * Todo: Plan to implement in version 1.1
					if ( $payment_notification_email ) {
						$site_name = get_bloginfo( 'name' );
						$email_subject  = sprintf( __('Payment Received for a Property at %s', 'inspiry'), $site_name );
					}
					*/

				}
			}
		}


	}
	add_action( 'paypal_ipn_for_wordpress_payment_status_completed', 'inspiry_completed_payment_handler' );
endif;


