<?php
if( !function_exists( 'inspiry_is_user_restricted' ) ) :
	/**
	 * Checks if current user is restricted to access admin side or not
	 * @return bool
	 */
	function inspiry_is_user_restricted() {
		global $inspiry_options;
		$current_user = wp_get_current_user();

		if ( isset( $inspiry_options[ 'inspiry_restricted_level' ] ) ) {

			// get restricted level from theme options
			$restricted_level = $inspiry_options['inspiry_restricted_level'];
			if ( !empty( $restricted_level ) ) {
				$restricted_level = intval( $restricted_level );
			} else {
				$restricted_level = 0;
			}

			// Redirects user below a certain user level to home url
			// Ref: https://codex.wordpress.org/Roles_and_Capabilities#User_Level_to_Role_Conversion
			if ( $current_user->user_level <= $restricted_level ) {
				return true;
			}

		}

		return false;
	}
endif;


if( !function_exists( 'inspiry_restrict_admin_access' ) ) :
	/**
	 * Restrict user access to admin if his level is equal or below restricted level
	 */
	function inspiry_restrict_admin_access() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			// let it go
		} else if ( isset( $_GET['action'] ) && ( $_GET['action'] == 'delete' )  ) {
			// let it go as it is from my properties delete button
		} else {
			if ( inspiry_is_user_restricted() ) {
				wp_redirect( esc_url_raw( home_url( '/' ) ) );
				exit;
			}
		}
	}
	add_action( 'admin_init', 'inspiry_restrict_admin_access' );
endif;


if( !function_exists( 'inspiry_hide_admin_bar' ) ) :
	/**
	 * Hide the admin bar on front end for users with user level equal to or below restricted level
	 */
	function inspiry_hide_admin_bar() {
		if( is_user_logged_in() ) {
			if ( inspiry_is_user_restricted() ) {
				add_filter( 'show_admin_bar', '__return_false' );
			}
		}
	}
	add_action( 'init', 'inspiry_hide_admin_bar', 9 );
endif;


if( !function_exists( 'inspiry_ajax_login' ) ) :
	/**
	 * AJAX login request handler
	 */
	function inspiry_ajax_login() {

		// First check the nonce, if it fails the function will break
		check_ajax_referer( 'inspiry-ajax-login-nonce', 'inspiry-secure-login' );

		// Nonce is checked, get the POST data and sign user on
		inspiry_auth_user_login( $_POST['log'], $_POST['pwd'], __( 'Login', 'inspiry' ) );

		die();
	}

	// Enable the user with no privileges to request ajax login
	add_action( 'wp_ajax_nopriv_inspiry_ajax_login', 'inspiry_ajax_login' );

endif;


if( !function_exists( 'inspiry_auth_user_login' ) ) :
	/**
	 * This function process login request and displays JSON response
	 *
	 * @param $user_login
	 * @param $password
	 * @param $login
	 */
	function inspiry_auth_user_login ( $user_login, $password, $login ) {

		$info = array();
		$info['user_login'] = $user_login;
		$info['user_password'] = $password;
		$info['remember'] = true;

		$user_signon = wp_signon( $info, false );

		if ( is_wp_error( $user_signon ) ) {
			echo json_encode( array (
				'success' => false,
				'message' => __( '* Wrong username or password.', 'inspiry' ),
			) );
		} else {
			wp_set_current_user( $user_signon->ID );
			echo json_encode( array (
				'success' => true,
				'message' => $login . ' ' . __( 'successful. Redirecting...', 'inspiry' ),
				'redirect' => $_POST['redirect_to']
			) );
		}

		die();
	}
endif;


if( !function_exists( 'inspiry_ajax_register' ) ) :
	/**
	 * AJAX register request handler
	 */
	function inspiry_ajax_register() {

		// First check the nonce, if it fails the function will break
		check_ajax_referer( 'inspiry-ajax-register-nonce', 'inspiry-secure-register' );

		// Nonce is checked, Get to work
		$info = array();
		$info['user_nicename'] = $info['nickname'] = $info['display_name'] = $info['first_name'] = $info['user_login'] = sanitize_user( $_POST['register_username'] ) ;
		$info['user_pass'] = wp_generate_password( 12 );
		$info['user_email'] = sanitize_email( $_POST['register_email'] );

		// Register the user
		$user_register = wp_insert_user( $info );

		if ( is_wp_error( $user_register ) ) {

			$error  = $user_register->get_error_codes()	;
			if ( in_array( 'empty_user_login', $error ) ) {
				echo json_encode( array (
					'success' => false,
					'message' => __( $user_register->get_error_message( 'empty_user_login' ) )
				) );
			} elseif ( in_array ( 'existing_user_login', $error ) ) {
				echo json_encode ( array (
					'success' => false,
					'message' => __( 'This username already exists.', 'inspiry' )
				) );
			} elseif ( in_array ( 'existing_user_email', $error ) ) {
				echo json_encode( array (
					'success' => false,
					'message' => __( 'This email is already registered.', 'inspiry' )
				) );
			}

		} else {

			/* send password as part of email to newly registered user */
			inspiry_new_user_notification( $user_register, $info[ 'user_pass' ]  );

			echo json_encode( array(
				'success' => true,
				'message' => __( 'Registration is complete. Check your email for details!', 'inspiry' ),
			) );
		}

		die();
	}

	// Enable the user with no privileges to request ajax register
	add_action( 'wp_ajax_nopriv_inspiry_ajax_register', 'inspiry_ajax_register' );

endif;


if( !function_exists( 'inspiry_ajax_reset_password' ) ) :
	/**
	 * AJAX reset password request handler
	 */
	function inspiry_ajax_reset_password(){

		// First check the nonce, if it fails the function will break
		check_ajax_referer( 'inspiry-ajax-forgot-nonce', 'inspiry-secure-reset' );

		$account = $_POST['reset_username_or_email'];
		$error = "";
		$get_by = "";

		if ( empty( $account ) ) {
			$error = __( 'Provide a valid username or email address!', 'inspiry' );
		} else {
			if ( is_email( $account ) ) {
				if ( email_exists( $account ) ) {
					$get_by = 'email';
				} else {
					$error = __( 'No user found for given email!', 'inspiry' );
				}
			} elseif ( validate_username ( $account ) ) {
				if ( username_exists ( $account ) ) {
					$get_by = 'login';
				} else {
					$error = __( 'No user found for given username!', 'inspiry' );
				}
			} else {
				$error = __( 'Invalid username or email!', 'inspiry' );
			}
		}

		if ( empty ( $error ) ) {

			// Generate new random password
			$random_password = wp_generate_password();

			// Get user data by field ( fields are id, slug, email or login )
			$target_user = get_user_by( $get_by, $account );

			$update_user = wp_update_user( array (
				'ID' => $target_user->ID,
				'user_pass' => $random_password
			) );

			// if  update_user return true then send user an email containing the new password
			if ( $update_user ) {

				$from = get_option( 'admin_email' ); // Set whatever you want like mail@yourdomain.com

				if ( !isset( $from ) || !is_email( $from ) ) {
					$site_name = strtolower( $_SERVER['SERVER_NAME'] );
					if ( substr( $site_name, 0, 4 ) == 'www.' ) {
						$site_name = substr( $site_name, 4 );
					}
					$from = 'admin@' . $site_name;
				}

				$to = $target_user->user_email;
				$website_name = get_bloginfo( 'name' );
				$subject = sprintf( __('Your New Password For %s', 'inspiry'), $website_name );
				$message = wpautop( sprintf( __( 'Your new password is: %s', 'inspiry' ), $random_password ) );

				/*
				* Email Headers ( Reply To and Content Type )
				*/
				$headers = array();
				$headers[] = "Reply-To: $website_name <$from>";
				$headers[] = "Content-Type: text/html; charset=UTF-8";
				$headers = apply_filters( "inspiry_password_reset_header", $headers );    // just in case if you want to modify the header in child theme

				$mail = wp_mail( $to, $subject, $message, $headers );

				if ( $mail ) {
					$success = __( 'Check your email for new password', 'inspiry' );
				} else {
					$error = __( 'Failed to send you new password email!', 'inspiry' );
				}

			} else {
				$error = __( 'Oops! Something went wrong while resetting your password!', 'inspiry' );
			}
		}

		if( ! empty( $error ) ){
			echo json_encode(
				array (
					'success' => false,
					'message' => $error
				)
			);
		} elseif ( ! empty( $success ) ) {
			echo json_encode(
				array (
					'success' => true,
					'message' => $success
				)
			);
		}

		die();
	}

	// Enable the user with no privileges to request ajax password reset
	add_action( 'wp_ajax_nopriv_inspiry_ajax_forgot', 'inspiry_ajax_reset_password' );

endif;


if ( ! function_exists( 'inspiry_new_user_notification' ) ) :
	/**
	 * Email confirmation email to newly-registered user with randomly generated password included as part of it
	 *
	 * A new user registration notification is sent to admin email
	 */
	function inspiry_new_user_notification( $user_id, $user_password ) {

		$user = get_userdata( $user_id );

		// The blogname option is escaped with esc_html on the way into the database in sanitize_option
		// we want to reverse this for the plain text arena of emails.
		$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );

		/**
		 * Admin Email
		 */
		$message = sprintf( __( 'New user registration on your site %s:', 'inspiry' ), $blogname ) . "\r\n\r\n";
		$message .= sprintf( __( 'Username: %s', 'inspiry' ), $user->user_login ) . "\r\n\r\n";
		$message .= sprintf( __( 'Email: %s', 'inspiry' ), $user->user_email ) . "\r\n";

		wp_mail( get_option( 'admin_email' ), sprintf( __( '[%s] New User Registration', 'inspiry' ), $blogname ), $message );

		/**
		 * Newly Registered User Email
		 */
		$message = sprintf( __( 'Welcome to %s', 'inspiry' ), $blogname ) . "\r\n\r\n";
		$message .= sprintf( __( 'Your username is: %s', 'inspiry' ), $user->user_login ) . "\r\n\r\n";
		$message .= sprintf( __( 'You can login using following password: %s', 'inspiry' ), $user_password ) . "\r\n\r\n";
		$message .= __( 'It is highly recommended to change your password after login.', 'inspiry' ) . "\r\n\r\n";
		$message .= __( 'For more details visit:', 'inspiry' ) . ' ' . home_url( '/' ) . "\r\n";

		wp_mail( $user->user_email, sprintf( __( 'Welcome to %s', 'inspiry' ), $blogname ), $message );
	}
endif;


if( !function_exists( 'inspiry_image_upload' ) ) {
	/**
	 * Ajax image upload for property submit and update
	 */
	function inspiry_image_upload( ) {

		// Verify Nonce
		$nonce = $_REQUEST['nonce'];
		if ( ! wp_verify_nonce( $nonce, 'inspiry_allow_upload' ) ) {
			$ajax_response = array(
				'success' => false ,
				'reason' => __('Security check failed!', 'inspiry')
			);
			echo json_encode( $ajax_response );
			die;
		}

		$submitted_file = $_FILES['inspiry_upload_file'];
		$uploaded_image = wp_handle_upload( $submitted_file, array( 'test_form' => false ) );   //Handle PHP uploads in WordPress, sanitizing file names, checking extensions for mime type, and moving the file to the appropriate directory within the uploads directory.

		if ( isset( $uploaded_image['file'] ) ) {
			$file_name          =   basename( $submitted_file['name'] );
			$file_type          =   wp_check_filetype( $uploaded_image['file'] );   //Retrieve the file type from the file name.

			// Prepare an array of post data for the attachment.
			$attachment_details = array(
				'guid'           => $uploaded_image['url'],
				'post_mime_type' => $file_type['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $file_name ) ),
				'post_content'   => '',
				'post_status'    => 'inherit'
			);

			$attach_id      =   wp_insert_attachment( $attachment_details, $uploaded_image['file'] );       // This function inserts an attachment into the media library
			$attach_data    =   wp_generate_attachment_metadata( $attach_id, $uploaded_image['file'] );     // This function generates metadata for an image attachment. It also creates a thumbnail and other intermediate sizes of the image attachment based on the sizes defined
			wp_update_attachment_metadata( $attach_id, $attach_data );                                      // Update metadata for an attachment.

			$thumbnail_url = inspiry_get_thumbnail_url( $attach_data ); // return escaped url

			$ajax_response = array(
				'success'   => true,
				'url' => $thumbnail_url,
				'attachment_id'    => $attach_id
			);

			echo json_encode( $ajax_response );
			die;

		} else {
			$ajax_response = array(
				'success' => false,
				'reason' => __('Image upload failed!', 'inspiry')
			);
			echo json_encode( $ajax_response );
			die;
		}

	}
	add_action( 'wp_ajax_ajax_img_upload', 'inspiry_image_upload' );    // only for logged in user
}



if( !function_exists( 'inspiry_get_thumbnail_url' ) ){
	/**
	 * Get thumbnail url based on attachment data
	 *
	 * @param $attach_data
	 * @return string
	 */
	function inspiry_get_thumbnail_url( $attach_data ){
		$upload_dir         =   wp_upload_dir();
		$image_path_array   =   explode( '/', $attach_data['file'] );
		$image_path_array   =   array_slice( $image_path_array, 0, count( $image_path_array ) - 1 );
		$image_path         =   implode( '/', $image_path_array );
		$thumbnail_name     =   $attach_data['sizes']['thumbnail']['file'];
		return esc_url( $upload_dir['baseurl'] . '/' . $image_path . '/' . $thumbnail_name ) ;
	}
}



if ( !function_exists( 'inspiry_remove_gallery_image' ) ) {
	/**
	 * Property Submit Form - Gallery Image Removal
	 */
	function inspiry_remove_gallery_image() {

		// Verify Nonce
		$nonce = $_POST['nonce'];
		if ( ! wp_verify_nonce ( $nonce, 'inspiry_allow_upload' ) ) {
			$ajax_response = array(
				'post_meta_removed' => false ,
				'attachment_removed' => false ,
				'reason' => __('Security check failed!', 'inspiry')
			);
			echo json_encode( $ajax_response );
			die;
		}

		$post_meta_removed = false;
		$attachment_removed = false;

		if( isset( $_POST['property_id'] ) && isset( $_POST['attachment_id'] ) ) {
			$property_id = intval( $_POST['property_id'] );
			$attachment_id = intval( $_POST['attachment_id'] );
			if ( $property_id > 0 && $attachment_id > 0 ) {
				$post_meta_removed = delete_post_meta( $property_id, 'REAL_HOMES_property_images', $attachment_id );
				$attachment_removed = wp_delete_attachment ( $attachment_id );
			} else if ( $attachment_id > 0 ) {
				if( false === wp_delete_attachment ( $attachment_id ) ){
					$attachment_removed = false;
				} else {
					$attachment_removed = true;
				}
			}
		}

		$ajax_response = array(
			'post_meta_removed' => $post_meta_removed,
			'attachment_removed' => $attachment_removed,
		);

		echo json_encode( $ajax_response );
		die;

	}
	add_action( 'wp_ajax_remove_gallery_image', 'inspiry_remove_gallery_image' );
}



if ( !function_exists( 'inspiry_submit_notice' ) ) {
	/**
	 * Property Submit Notice Email
	 *
	 * @param $property_id
	 */
	function inspiry_submit_notice( $property_id ) {

		// get and sanitize target email
		global $inspiry_options;
		$target_email = $inspiry_options[ 'inspiry_submit_notice_email' ];
		$target_email = is_email( $target_email );
		if ( $target_email ) {

			// current user ( submitter ) information
			$current_user = wp_get_current_user();
			$submitter_name = $current_user->display_name;
			$submitter_email = $current_user->user_email;
			$site_name = get_bloginfo( 'name' );

			// email subject
			$email_subject  = sprintf( __('A new property is submitted by %s at %s', 'inspiry'), $submitter_name, $site_name );

			// start of email body
			$email_body = wpautop( $email_subject );

			/* preview link */
			$preview_link = set_url_scheme( get_permalink( $property_id ) );
			$preview_link = esc_url( apply_filters( 'preview_post_link', add_query_arg( 'preview', 'true', $preview_link ) ) );
			if ( ! empty( $preview_link ) ) {
				$email_body .= wpautop( __( 'You can preview it here :', 'inspiry' ) . ' ' . '<a target="_blank" href="'. $preview_link .'">' . sanitize_text_field( $_POST['inspiry_property_title'] ) . '</a>' );
			}

			/* message to reviewer */
			if ( isset( $_POST['message_to_reviewer'] ) ) {
				$message_to_reviewer = wp_kses_data( $_POST['message_to_reviewer'] );
				if ( ! empty( $message_to_reviewer ) ) {
					$email_body .= wpautop( sprintf( __( 'Message to the Reviewer : %s', 'inspiry' ), $message_to_reviewer ) );
				}
			}

			/* End of message body */
			$email_body .= wpautop( sprintf( __( 'You can contact the submitter %1$s via email %2$s', 'inspiry' ), $submitter_name, $submitter_email ) );

			/*
			 * Email Headers ( Reply To and Content Type )
			 */
			$headers = array();
			$headers[] = "Reply-To: $submitter_name <$submitter_email>";
			$headers[] = "Content-Type: text/html; charset=UTF-8";
			$headers = apply_filters( "inspiry_property_submit_mail_header", $headers );    // just in case if you want to modify the header in child theme

			// Send Email
			if ( ! wp_mail( $target_email, $email_subject, $email_body, $headers ) ){
				inspiry_log( 'Failed to send property submit notice' );
			}

		}

	}
	add_action( 'inspiry_after_property_submit', 'inspiry_submit_notice' );
}



