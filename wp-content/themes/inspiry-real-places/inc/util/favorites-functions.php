<?php
if( !function_exists( 'inspiry_add_to_favorites' ) ){
	/**
	 * Add a property id into favorite properties of a user if logged in otherwise store the favorites in cookies
	 */
	function inspiry_add_to_favorites() {

		if ( isset( $_POST['property_id'] ) && is_user_logged_in() ) {

			$property_id = intval( $_POST['property_id'] );
			$user_id = get_current_user_id();

			if ( $property_id > 0 && $user_id > 0 ) {

				if ( add_user_meta($user_id,'favorite_properties', $property_id ) ) {
					echo wp_json_encode( array(
						'success' => true,
						'message' => __( 'Added to Favorites', 'inspiry' )
					));
					die;
				} else {
					echo wp_json_encode( array(
						'success' => false,
						'message' => __( 'Failed!', 'inspiry' )
					));
					die;
				}
			}
		} elseif ( isset( $_POST['property_id'] ) ) {
			$property_id = intval( $_POST[ 'property_id' ] );
			if ( $property_id > 0 ) {
				$inspiry_favorites = array();
				if ( isset( $_COOKIE[ 'inspiry_favorites' ] ) ) {
					$inspiry_favorites = unserialize( $_COOKIE[ 'inspiry_favorites' ] );
				}
				$inspiry_favorites[] = $property_id;
				if ( setcookie( 'inspiry_favorites', serialize( $inspiry_favorites ), time() + ( 60 * 60 * 24 * 30 ), '/' ) ) {
					echo wp_json_encode( array(
						'success' => true,
						'message' => __( 'Added to Favorites', 'inspiry' )
					));
				} else {
					echo wp_json_encode( array(
						'success' => false,
						'message' => __( 'Failed!', 'inspiry' )
					));
				}
			}
		} else {
			echo wp_json_encode( array(
				'success' => false,
				'message' => __( 'Invalid parameters', 'inspiry' )
			));
		}

		die;

	}
	add_action( 'wp_ajax_add_to_favorites', 'inspiry_add_to_favorites' );
	add_action( 'wp_ajax_nopriv_add_to_favorites', 'inspiry_add_to_favorites' );
}


if ( !function_exists( 'is_added_to_favorite' ) ) {
	/**
	 * Check if a property is already added to favorites properties of a user
	 * @param $property_id
	 * @param $user_id
	 * @return bool
	 */
	function is_added_to_favorite( $property_id, $user_id = 0 ) {

		if ( $property_id > 0 ) {

			/* if user id is not provided then try to get current user id */
			if ( ! $user_id ) {
				$user_id = get_current_user_id();
			}

			if ( $user_id > 0 ) {
				/* if logged in check in database */
				global $wpdb;
				$results = $wpdb->get_results( "SELECT * FROM $wpdb->usermeta WHERE meta_key='favorite_properties' AND meta_value=" . $property_id . " AND user_id=" . $user_id );
				if ( isset( $results[ 0 ]->meta_value ) && ( $results[ 0 ]->meta_value == $property_id ) ) {
					return true;
				}
			} else {
				/* if not logged in check in cookies */
				if ( isset( $_COOKIE[ 'inspiry_favorites' ] ) ) {
					$inspiry_favorites = unserialize( $_COOKIE[ 'inspiry_favorites' ] );
					if ( in_array( $property_id, $inspiry_favorites ) ) {
						return true;
					}
				}
			}
		}
		return false;
	}
}


if ( !function_exists( 'inspiry_remove_from_favorites' ) ) {
	/**
	 * Remove a property from favorites properties of current user
	 */
	function inspiry_remove_from_favorites() {

		if ( isset( $_POST['property_id'] ) ) {

			$property_id = intval( $_POST['property_id'] );

			if ( $property_id > 0 ) {

				if ( is_user_logged_in() ) {

					if( delete_user_meta( get_current_user_id(), 'favorite_properties', $property_id ) ) {
						echo wp_json_encode( array(
							'success' => true
						));
						die;
					} else {
						echo wp_json_encode( array(
							'success' => false,
							'message' => __( 'Failed to remove!', 'inspiry' )
						));
						die;
					}

				} else {
					if ( isset( $_COOKIE['inspiry_favorites'] ) ) {
						$inspiry_favorites = unserialize( $_COOKIE['inspiry_favorites'] );
						$target_index = array_search( $property_id, $inspiry_favorites );
						if ( $target_index >= 0 && $target_index !== false ) {
							unset( $inspiry_favorites[$target_index] );
							setcookie( 'inspiry_favorites', serialize( $inspiry_favorites ), time() + ( 60 * 60 * 24 * 30 ), '/' );
							echo json_encode( array(
									'success' => true,
									'message' => __( "Removed Successfully!", 'framework' )
								)
							);
							die;
						} else {
							echo json_encode( array(
									'success' => false,
									'message' => __( "Failed to remove!", 'framework' )
								)
							);
							die;
						}
					}
				}
			}
		}

		echo wp_json_encode( array(
			'success' => false,
			'message' => __( 'Invalid parameters!', 'inspiry' )
		));
		die;

	}

	add_action( 'wp_ajax_remove_from_favorites', 'inspiry_remove_from_favorites' );
	add_action( 'wp_ajax_nopriv_remove_from_favorites', 'inspiry_remove_from_favorites' );
}


if( !function_exists( 'inspiry_import_favorites' ) ) :
	/**
	 * Import properties from cookies to database on login
	 * @param $user_login
	 * @param $user
	 */
	function inspiry_import_favorites( $user_login, $user ) {

		if ( isset( $_COOKIE['inspiry_favorites'] ) ) {
			$favorites_in_cookies = unserialize( $_COOKIE['inspiry_favorites'] );
			if ( 0 < count( $favorites_in_cookies ) ) {
				foreach ( $favorites_in_cookies as $favorited_id ) {
					if ( ! is_added_to_favorite( $favorited_id, $user->ID ) ) {
						add_user_meta( $user->ID, 'favorite_properties', $favorited_id );
					}
				}
				// clear cookies
				setcookie( 'inspiry_favorites', serialize( array( 0 ) ), time() - ( 60 * 60 ), '/' );
			}
		}
	}
	add_action( 'wp_login', 'inspiry_import_favorites', 10, 2 );
endif;


if( !function_exists( 'inspiry_generate_favorite_data' ) ) {
	/**
	 * Generates favorite related data that is consumed by JS script
	 */
	function inspiry_generate_favorite_data() {
		if ( !is_admin() ) {
			$add_to_favorite_data = array(
				'ajaxURL' => esc_url( admin_url( 'admin-ajax.php' ) ),
				'propertyID' => get_the_ID(),
				'action' => 'add_to_favorites',
			);
			wp_localize_script( 'inspiry-favorites', 'favoriteData', $add_to_favorite_data );
		}
	}
	add_action( 'inspiry_add_to_favorites', 'inspiry_generate_favorite_data' );
}
