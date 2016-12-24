<?php
if( !function_exists( 'inspiry_mail_from_name' ) ) :
	/**
	 * Override 'WordPress' as from name in emails sent by wp_mail function
	 * @return string
	 */
	function inspiry_mail_from_name() {
		// The blogname option is escaped with esc_html on the way into the database in sanitize_option
		// we want to reverse this for the plain text arena of emails.
		$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );

		return $blogname;
	}
	add_filter( 'wp_mail_from_name', 'inspiry_mail_from_name' );
endif;


if( !function_exists( 'inspiry_send_message' ) ){
	/**
	 * contact form handler
	 */
	function inspiry_send_message() {

		if ( isset($_POST['email'] ) ):

			global $inspiry_options;
			$nonce = $_POST['nonce'];

			if (!wp_verify_nonce($nonce, 'send_message_nonce')) {
				echo json_encode(array(
					'success' => false,
					'message' => __('Unverified Nonce!', 'inspiry')
				));
				die;
			}

			if ( class_exists( 'Inspiry_Real_Estate' )
				&& ( ! isset( $_POST['ISGR'] ) )
				&& ( $inspiry_options[ 'inspiry_google_reCAPTCHA' ] )
				&& !empty( $inspiry_options[ 'inspiry_reCAPTCHA_site_key' ] )
				&& !empty( $inspiry_options[ 'inspiry_reCAPTCHA_secret_key' ] )) {

				// include reCAPTCHA library - https://github.com/google/recaptcha
				require_once( WP_PLUGIN_DIR . '/inspiry-real-estate/reCAPTCHA/autoload.php' );

				// If the form submission includes the "g-captcha-response" field
				// Create an instance of the service using your secret
				$reCAPTCHA = new \ReCaptcha\ReCaptcha( $inspiry_options[ 'inspiry_reCAPTCHA_secret_key' ] );

				// If file_get_contents() is locked down on your PHP installation to disallow
				// its use with URLs, then you can use the alternative request method instead.
				// This makes use of fsockopen() instead.
				//  $reCAPTCHA = new \ReCaptcha\ReCaptcha($secret, new \ReCaptcha\RequestMethod\SocketPost());

				// Make the call to verify the response and also pass the user's IP address
				$resp = $reCAPTCHA->verify( $_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR'] );

				if ( $resp->isSuccess() ){
					// If the response is a success, that's it!
				} else {
					// reference for error codes - https://developers.google.com/recaptcha/docs/verify
					$error_messages =  array(
						'missing-input-secret' => 'The secret parameter is missing.',
						'invalid-input-secret' => 'The secret parameter is invalid or malformed.',
						'missing-input-response' => 'The response parameter is missing.',
						'invalid-input-response' => 'The response parameter is invalid or malformed.',
					);
					$error_codes = $resp->getErrorCodes();
					$final_error_message = $error_messages[ $error_codes[0] ];
					echo json_encode( array(
						'success' => false,
						'message' => __('reCAPTCHA Failed:', 'inspiry') . ' ' . $final_error_message
					) );
					die;
				}

			}

			// Sanitize and Validate Target email address that is coming from agent form
			$to_email = sanitize_email( $_POST['target'] );
			$to_email = is_email( $to_email );
			if ( !$to_email ) {
				echo wp_json_encode( array(
					'success' => false,
					'message' => __( 'Target Email address is not properly configured!', 'inspiry' )
				));
				die;
			}

			/*
			 *  Sanitize and Validate contact form input data
			 */
			$from_name = sanitize_text_field( $_POST['name'] );
			$phone_number = sanitize_text_field( $_POST['number'] );
			$message = wp_kses_data( $_POST['message'] );
			$from_email = sanitize_email( $_POST['email'] );
			$from_email = is_email( $from_email );
			if (! $from_email ) {
				echo json_encode(array(
					'success' => false,
					'message' => __('Provided Email address is invalid!', 'inspiry')
				));
				die;
			}

			$email_subject = __( 'New message sent by', 'inspiry' ) . ' ' . $from_name . ' ' . __( 'using contact form at', 'inspiry' ) . ' ' . get_bloginfo( 'name' );
			$email_body = __( "You have received a message from: ", 'inspiry' ) . $from_name . " <br/>";

			if ( !empty( $phone_number ) ) {
				$email_body .= __( "Phone Number : ", 'inspiry' ) . $phone_number . " <br/>";
			}

			$email_body .= __( "Their additional message is as follows.", 'inspiry' ) . " <br/>";
			$email_body .= wpautop( $message );
			$email_body .= wpautop( sprintf( __( 'You can contact %1$s via email %2$s', 'inspiry'), $from_name, $from_email ) );

			/*
			 * Email Headers ( Reply To and Content Type )
			 */
			$headers = array();
			$headers[] = "Reply-To: $from_name <$from_email>";
			$headers[] = "Content-Type: text/html; charset=UTF-8";
			$headers = apply_filters( "inspiry_contact_mail_header", $headers );    // just in case if you want to modify the header in child theme

			if ( wp_mail( $to_email, $email_subject, $email_body, $headers ) ) {
				echo json_encode( array(
					'success' => true,
					'message' => __("Message Sent Successfully!", 'inspiry')
				) );
			} else {
				echo json_encode( array(
						'success' => false,
						'message' => __( "Server Error: WordPress mail function failed!", 'inspiry' )
					)
				);
			}

		else:
			echo json_encode( array(
					'success' => false,
					'message' => __("Invalid Request !", 'inspiry')
				)
			);
		endif;

		die;
	}

	add_action( 'wp_ajax_nopriv_inspiry_send_message', 'inspiry_send_message' );
	add_action( 'wp_ajax_inspiry_send_message', 'inspiry_send_message' );

}



if ( !function_exists( 'inspiry_agent_message_handler' ) ) {
	/**
	 * Ajax request handler for agent's contact form.
	 */
	function inspiry_agent_message_handler() {

		if ( isset( $_POST['email'] ) ):
			global $inspiry_options;
			$nonce = $_POST['nonce'];

			if ( !wp_verify_nonce( $nonce, 'agent_message_nonce' ) ) {
				echo wp_json_encode(array(
					'success' => false,
					'message' => __('Unverified Nonce!', 'inspiry')
				));
				die;
			}

			if ( inspiry_is_reCAPTCHA_configured() ) {

				// include reCAPTCHA library - https://github.com/google/recaptcha
				require_once( WP_PLUGIN_DIR . '/inspiry-real-estate/reCAPTCHA/autoload.php' );

				// If the form submission includes the "g-captcha-response" field
				// Create an instance of the service using your secret
				$reCAPTCHA = new \ReCaptcha\ReCaptcha( $inspiry_options[ 'inspiry_reCAPTCHA_secret_key' ] );

				// If file_get_contents() is locked down on your PHP installation to disallow
				// its use with URLs, then you can use the alternative request method instead.
				// This makes use of fsockopen() instead.
				//  $reCAPTCHA = new \ReCaptcha\ReCaptcha($secret, new \ReCaptcha\RequestMethod\SocketPost());

				// Make the call to verify the response and also pass the user's IP address
				$resp = $reCAPTCHA->verify( $_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR'] );

				if ( $resp->isSuccess() ){
					// If the response is a success, that's it!
				} else {
					// reference for error codes - https://developers.google.com/recaptcha/docs/verify
					$error_messages =  array(
						'missing-input-secret' => 'The secret parameter is missing.',
						'invalid-input-secret' => 'The secret parameter is invalid or malformed.',
						'missing-input-response' => 'The response parameter is missing.',
						'invalid-input-response' => 'The response parameter is invalid or malformed.',
					);
					$error_codes = $resp->getErrorCodes();
					$final_error_message = $error_messages[ $error_codes[0] ];
					echo json_encode( array(
						'success' => false,
						'message' => __('reCAPTCHA Failed:', 'inspiry') . ' ' . $final_error_message
					) );
					die;
				}

			}

			// Sanitize and Validate Target email address that is coming from agent form
			$to_email = sanitize_email( $_POST['target'] );
			$to_email = is_email( $to_email );
			if ( !$to_email ) {
				echo wp_json_encode( array(
					'success' => false,
					'message' => __( 'Target Email address is not properly configured!', 'inspiry' )
				));
				die;
			}


			// Sanitize and Validate contact form input data
			$from_name = sanitize_text_field($_POST['name']);
			$message = wp_kses_data( $_POST['message'] );

			$property_title = '';
			if( isset( $_POST['property_title'] ) ) {
				$property_title = sanitize_text_field( $_POST['property_title'] );
			}

			$property_permalink = '';
			if( isset( $_POST['property_permalink'] ) ) {
				$property_permalink = esc_url( $_POST['property_permalink'] );
			}

			$from_email = sanitize_email( $_POST['email'] );
			$from_email = is_email( $from_email );
			if ( !$from_email ) {
				echo wp_json_encode( array(
					'success' => false,
					'message' => __('Provided Email address is invalid!', 'inspiry')
				) );
				die;
			}

			$email_subject = sprintf( __( 'New message sent by %1$s using agent contact form at %2$s', 'inspiry') ,$from_name , get_bloginfo('name') );

			$email_body = wpautop( __( 'You have received a message from:', 'inspiry') . ' ' . $from_name );

			if ( ! empty( $property_title ) ) {
				$email_body .= wpautop( __( 'Property Title :', 'inspiry') . ' ' . $property_title );
			}

			if ( ! empty( $property_permalink ) ) {
				$email_body .= wpautop( __( 'Property URL :', 'inspiry' ) . ' ' . '<a href="'. $property_permalink. '">' . $property_permalink . "</a>" );
			}

			$email_body .= wpautop( __("Their additional message is as follows.", 'inspiry') );
			$email_body .= wpautop( $message );
			$email_body .= wpautop( sprintf( __( 'You can contact %1$s via email %2$s', 'inspiry' ), $from_name, $from_email ) );

			/*
			 * Email Headers ( Reply To and Content Type )
			 */
			$headers = array();
			$headers[] = "Reply-To: $from_name <$from_email>";
			$headers[] = "Content-Type: text/html; charset=UTF-8";


			/*
			 * Add given CC email address/addresses into email header
			 */
			$cc_email = $inspiry_options['inspiry_agent_cc_email'];
			if ( !empty( $cc_email ) ) {
				if ( strpos( $cc_email, ',' ) ) {                   // For multiple emails
					$cc_emails = explode( ',', $cc_email );
					if( !empty( $cc_emails ) ){
						foreach( $cc_emails as $single_cc_email ){
							$single_cc_email = sanitize_email( $single_cc_email );
							$single_cc_email = is_email( $single_cc_email );
							if ( $single_cc_email ) {
								$headers[] = "Cc: $single_cc_email";
							}
						}
					}
				} elseif ( $cc_email = is_email( $cc_email ) ) {    // For single email
					$headers[] = "Cc: $cc_email";
				}
			}

			$headers = apply_filters( "inspiry_agent_mail_header", $headers );    // just in case if you want to modify the header in child theme

			/*
			 * Send Message
			 */
			if ( wp_mail( $to_email, $email_subject, $email_body, $headers ) ) {
				echo wp_json_encode( array(
					'success' => true,
					'message' => __("Message Sent Successfully!", 'inspiry')
				));
			} else {
				echo wp_json_encode(array(
						'success' => false,
						'message' => __("Server Error: WordPress mail function failed!", 'inspiry')
					)
				);
			}

		else:
			echo wp_json_encode(array(
					'success' => false,
					'message' => __("Invalid Request !", 'inspiry')
				)
			);
		endif;
		die;
	}

	add_action( 'wp_ajax_nopriv_send_message_to_agent', 'inspiry_agent_message_handler' );
	add_action( 'wp_ajax_send_message_to_agent', 'inspiry_agent_message_handler' );

}


if( !function_exists( 'inspiry_is_reCAPTCHA_configured' ) ) :
	/**
	 * Check if Google reCAPTCHA is property configured and enabled or not
	 * @return bool
	 */
    function inspiry_is_reCAPTCHA_configured() {
	    global $inspiry_options;

	    if ( class_exists( 'Inspiry_Real_Estate' )
		    && ( $inspiry_options[ 'inspiry_google_reCAPTCHA' ] )
		    && !empty( $inspiry_options[ 'inspiry_reCAPTCHA_site_key' ] )
		    && !empty( $inspiry_options[ 'inspiry_reCAPTCHA_secret_key' ] ) ) {
		    return true;
	    }
	    return false;
    }
endif;


if( !function_exists( 'inspiry_recaptcha_callback_generator' ) ) :
	/**
	 * Generates a call back JavaScript function for reCAPTCHA
	 */
    function inspiry_recaptcha_callback_generator() {
	    if ( inspiry_is_reCAPTCHA_configured() ) {
		    global $google_reCAPTCHA_counter;
		    global $inspiry_options;
		    ?>
		    <script type="text/javascript">
			    var googleReCAPTCHACounter = <?php echo $google_reCAPTCHA_counter; ?>;
			    var inspirySiteKey = '<?php echo $inspiry_options[ 'inspiry_reCAPTCHA_site_key' ]; ?>';
			    var loadInspiryReCAPTCHA = function(){
				    while( googleReCAPTCHACounter > 1 ) {
					    googleReCAPTCHACounter--;
					    grecaptcha.render( document.getElementById( 'inspiry-' + googleReCAPTCHACounter ), {
						    'sitekey' : inspirySiteKey
					    } );
				    }
			    };
		    </script>
		    <?php
	    }
    }

	add_action( 'wp_footer', 'inspiry_recaptcha_callback_generator' );
endif;