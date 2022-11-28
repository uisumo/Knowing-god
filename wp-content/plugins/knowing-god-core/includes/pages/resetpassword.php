<?php
/**
 * This template is used to display the 'resetpassword'
 *
 * @package     Knowing God
 * @subpackage  resetpassword
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;	
}
?>
<div id="resetPassword">

    <!--this check on the link key and user login/username-->
    <?php
        $errors = new WP_Error();
        $user = check_password_reset_key( $_GET['key'], $_GET['login'] );

        if ( is_wp_error( $user ) ) {
            if ( $user->get_error_code() === 'expired_key' ) {
                $errors->add( 'expiredkey', esc_html__( 'Sorry, that key has expired. Please try again.', 'knowing-god' ) );
			} else {
                $errors->add( 'invalidkey', esc_html__( 'Sorry, that key does not appear to be valid.', 'knowing-god' ) );
			}
        }

        // display error message

        ?>

        <form id="resetPasswordForm" method="post" autocomplete="off" class="knowing_god_form">
            <div id="message"><p class="error"><?php
			if ( $errors->get_error_code() )
            echo $errors->get_error_message( $errors->get_error_code() );
			?></p></div>
            <?php
                // this prevent automated script for unwanted spam
                if ( function_exists( 'wp_nonce_field' ) ) {
                    wp_nonce_field( 'rs_user_reset_password_action', 'rs_user_reset_password_nonce' );
				}
            ?>

            <input type="hidden" name="user_key" id="user_key" value="<?php echo esc_attr( $_GET['key'] ); ?>" autocomplete="off" />
            <input type="hidden" name="user_login" id="user_login" value="<?php echo esc_attr( $_GET['login'] ); ?>" autocomplete="off" />

            <div class="form-group">
                <label for="pass1"><?php esc_html_e( 'New password', 'knowing-god' ) ?> <?php echo knowing_god_required_field();?></label>
                <input type="password" name="pass1" id="pass1" class="input form-control" size="20" value="" autocomplete="off" />
            </div>
            <div class="form-group">
                <label for="pass2"><?php esc_html_e( 'Confirm new password', 'knowing-god' ) ?><?php echo knowing_god_required_field();?></label>
                <input type="password" name="pass2" id="pass2" class="input form-control" size="20" value="" autocomplete="off" />
            </div>

            <p class="description indicator-hint"><?php esc_html_e( 'Hint: The password should be at least seven characters long. To make it stronger, use upper and lower case letters, numbers, and symbols like ! " ? $ % ^ & ).', 'knowing-god' ); ?></p>

            <br class="clear" />

            <?php
            /**
             * Fires following the 'Strength indicator' meter in the user password reset form.
             *
             * @since 3.9.0
             *
             * @param WP_User $user User object of the user whose password is being reset.
             */
            do_action( 'resetpass_form', $user );
            ?>
            <p class="submit"><input type="submit" name="wp-submit" id="wp-submit" class="btn btn-info center-block btn-mobile" value="<?php esc_html_e( 'Reset Password', 'knowing-god' ); ?>" />
            </p>
        </form>
    </div>
    <script>
    jQuery(document).ready(function( $ ) {
        // for reset password
        $("form#resetPasswordForm").submit(function( event ){
            var error = 0;
			var pass1 = jQuery( '#pass1' ).val();
			$('#pass1').nextAll().remove();
			$('#pass2').nextAll().remove();
			
			if ( pass1 == "" ) {
				jQuery( '#pass1' ).after( '<span class="error"><?php esc_html_e( 'Please enter password', 'knowing-god' );?></span>' );
				error++;
			} else if ( pass1.length < 8 ) {
				jQuery( '#pass1' ).after( '<span class="error"><?php esc_html_e( 'Password should be minimum 8 characters', 'knowing-god' );?></span>' );
				error++;
			}
			
			var pass2 = jQuery( '#pass2' ).val();
			if ( pass2 == "" ) {
				jQuery( '#pass2' ).after( '<span class="error"><?php esc_html_e( 'Please enter confirm password', 'knowing-god' );?></span>' );
				error++;
			}
			
			if ( pass1 != pass2 ) {
				jQuery( '#pass1' ).after( '<span class="error"><?php esc_html_e( 'Passwords do not match', 'knowing-god' );?></span>' );
				error++;
			}
			
			if ( error > 0 ) {
				event.preventDefault();
			} else {			
				var submit = $("div#resetPassword #submit"),
					preloader = $("div#resetPassword #preloader"),
					message    = $("div#message"),
					contents = {
						action:     'reset_pass',
						nonce:         this.rs_user_reset_password_nonce.value,
						pass1:        this.pass1.value,
						pass2:        this.pass2.value,
						user_key:    this.user_key.value,
						user_login:    this.user_login.value
					};

				// disable button onsubmit to avoid double submision
				submit.attr("disabled", "disabled").addClass( 'disabled' );

				// Display our pre-loading
				preloader.css({'visibility':'visible'});

				$.post( '<?php echo admin_url( 'admin-ajax.php' );?>', contents, function( data ){
					submit.removeAttr("disabled").removeClass( 'disabled' );

					// hide pre-loader
					preloader.css({'visibility':'hidden'});
					
					$( '#pass1' ).val( '' );
					$( '#pass2' ).val( '' );

					// display return data
					message.html( data );
				});

				return false;
			}
        });

    });
    </script>
