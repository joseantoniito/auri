<?php
/*
 * Google reCAPTCHA
 */
global $google_reCAPTCHA_counter;

if ( inspiry_is_reCAPTCHA_configured() ) {

        global $inspiry_options;
        ?>
        <div class="inspiry-recaptcha-wrapper clearfix">
	        <div id="inspiry-<?php echo $google_reCAPTCHA_counter; ?>"></div>
        </div>
        <?php
		/* increment in Google reCAPTCHA counter */
		$google_reCAPTCHA_counter++;
}