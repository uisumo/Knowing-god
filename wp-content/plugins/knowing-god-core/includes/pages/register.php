<?php
/**
 * This template is used to display the login form with [vehicle_login]
 *
 * @package     Simontaxi - Vehicle Booking
 * @subpackage  vehicle_login
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! get_option('users_can_register') ) {
    $redirect = add_query_arg( array( 'action' => 'no-register-access' ), knowing_god_urls( 'login' ) );
    wp_safe_redirect( $redirect );
}

if ( ! is_user_logged_in() ) :
    $wp_error = new WP_Error();
    if ( isset( $_POST ) && ! empty( $_POST ) ) {
        $data = $_POST;
        if ( wp_verify_nonce( $data['knowing_god_register_nonce'], 'knowing-god-register-nonce' ) ) {
            if ( $data['user_login'] == '' ) {
                $wp_error->add( 'user_login', esc_html__( 'Please enter Username', 'knowing-god' ) );
            } elseif ( 0 === preg_match("/.{5,}/", $data['user_login'] ) ) {
                $wp_error->add( 'user_login', esc_html__( 'Username should be minimum 5 characters', 'knowing-god' ) );
            }
            if ( strlen( $data['user_login'] ) > 60 ) {
                $wp_error->add( 'user_login', esc_html__( 'User name should not be more than 60 characters', 'knowing-god' ) );
            }
            if ( $data['user_email'] == '' ) {
                $wp_error->add( 'user_email', esc_html__( 'Please enter email address', 'knowing-god' ) );
            }
            if ( $data['user_first_name'] == '' ) {
                $wp_error->add( 'user_first_name', esc_html__( 'Please enter first name', 'knowing-god' ) );
            }
            if ( $data['user_password'] == '' ) {
                $wp_error->add( 'user_password', esc_html__( 'Please enter password', 'knowing-god' ) );
            } elseif ( 0 === preg_match("/.{8,}/", $data['user_password'] ) ) {
                $wp_error->add( 'user_password', esc_html__( 'Password should not be lessthan 8 characters', 'knowing-god' ) );
            }
            if ( $data['user_password_confirm'] == '' ) {
                $wp_error->add( 'user_password_confirm', esc_html__( 'Please enter password again', 'knowing-god' ) );
            }
            if ( $data['user_password'] != '' && $data['user_password_confirm'] != '' ) {
                if ( 0 !== strcmp( $data['user_password'], $data['user_password_confirm'] ) ) {
                    $wp_error->add( 'not_match', esc_html__( 'Passwords do not match', 'knowing-god' ) );
                }
            }
            if ( $data['mobile_countrycode'] == '' ) {
                $wp_error->add( 'mobile_countrycode', esc_html__( 'Please select country code', 'simontaxi' ) );
            }
            if ( $data['mobile'] == '' ) {
                $wp_error->add( 'mobile', esc_html__( 'Please enter mobile number', 'simontaxi' ) );
            }
            if ( function_exists( 'siwp_captcha_html' ) ) {
                if ( $data['siwp_captcha_value'] == '' ) {
                    $wp_error->add( 'siwp_captcha_value', esc_html__( 'Please enter characters shown', 'simontaxi' ) );
                }
                $error = '';
                if ( false === siwp_check_captcha( $error ) ) {
                    $wp_error->add( 'siwp_captcha_value', esc_html__( 'Captcha do not match', 'simontaxi' ) );
                }
            }

            if ( $data['user_login'] != '' && $data['user_email'] != '' ) {
                $user_data_login = get_user_by( 'login', $data['user_login'] );
                $user_data_email = get_user_by( 'email', $data['user_email'] );
                if ( $user_data_login || $user_data_email ) {
                    if ( $user_data_login ) {
                        $wp_error->add( 'already_exists', esc_html__( 'Username already exists', 'knowing-god' ) );
                    } elseif ( $user_data_email ) {
                        $wp_error->add( 'already_exists', esc_html__( 'Email already exists', 'knowing-god' ) );
                    }

                }
            }
            // Check for errors and redirect if none present
            $errors = $wp_error->errors;

            if ( empty( $errors ) ) {

                $user_args = array(
                    'user_login'      => isset( $data['user_login'] ) ? $data['user_login'] : '',
                    'user_pass'       => isset( $data['user_password'] )  ? $data['user_password']  : '',
                    'user_email'      => isset( $data['user_email'] ) ? $data['user_email'] : '',
                    'first_name'      => isset( $data['user_first_name'] ) ? $data['user_first_name'] : '',
                    'last_name'       => isset( $data['user_last_name'] )  ? $data['user_last_name']  : '',
                    'user_nicename'        => isset( $data['user_login'] ) ? $data['user_login'] : '',
                    'display_name'         => $data['user_first_name'] . ' ' . $data['user_last_name'],
                    'nickname'             => $data['user_login'],
                    'user_registered'    => date( 'Y-m-d H:i:s' ),
                    );
                $user_id = wp_insert_user( $user_args );

                // wp_new_user_notification( $user_id, $user_args['user_pass'] );

                $user = new WP_User( $user_id );
                // $user->set_role( 'subscriber' );
                $user->remove_role( 'subscriber' );

                update_user_meta( absint( $user_id ), 'mobile_countrycode', wp_kses_post( $data['mobile_countrycode'] ) );
                update_user_meta( absint( $user_id ), 'mobile', wp_kses_post( $data['mobile'] ) );
                update_user_meta( absint( $user_id ), 'confirmed', rand() );


                $redirect = add_query_arg( array( 'action' => 'registered' ), knowing_god_urls( 'login' ) );
                wp_safe_redirect( $redirect );
            }

        }
    }
?>
    <form id="knowing_god_register_form" class="knowing_god_form-register col-md-8 mx-auto mt-5" action="" method="post">
        <h2 class="mb-4"><?php esc_html_e( 'Register', 'knowing-god' ); ?></h2>
        <div class="row">
            <?php if ( ! empty( $wp_error->errors ) ) { ?>
                <div class="alert alert-danger">
                    <ul><?php echo implode( '</li><li>', $wp_error->get_error_messages() ); ?></ul>
                </div>
            <?php } ?>
            <div class="form-group col-sm-12">
<!--                <label for="user_login"> <?php esc_html_e( 'Username', 'knowing-god' ); ?><?php echo knowing_god_required_field(); ?></label>-->
                <div class="inner-addon right-addon">
                    <input name="user_login" id="user_login" class="required form-control" type="text" placeholder="<?php esc_html_e( 'Username', 'knowing-god' ); ?>" value="<?php echo knowing_god_get_value( $_POST, 'user_login' ); ?>"/>
                </div>
            </div>

            <div class="form-group col-sm-12">
<!--                <label for="user_email"><?php esc_html_e( 'Email', 'knowing-god' ); ?><?php echo knowing_god_required_field(); ?></label>-->
                <div class="inner-addon right-addon">
                    <input name="user_email" id="user_email" class="password required form-control" type="text" placeholder="<?php esc_html_e( 'Email', 'knowing-god' ); ?>" value="<?php echo knowing_god_get_value( $_POST, 'user_email' ); ?>"/>
                </div>
            </div>

            <div class="form-group col-sm-12">
<!--                <label for="user_first_name"> <?php esc_html_e( 'First Name', 'knowing-god' ); ?><?php echo knowing_god_required_field(); ?></label>-->
                <div class="inner-addon right-addon">
                    <input name="user_first_name" id="user_first_name" class="required form-control" type="text" placeholder="<?php esc_html_e( 'First Name', 'knowing-god' ); ?>" value="<?php echo knowing_god_get_value( $_POST, 'user_first_name' ); ?>"/>
                </div>
            </div>

            <div class="form-group col-sm-12">
<!--                <label for="user_last_name"> <?php esc_html_e( 'Last Name', 'knowing-god' ); ?></label>-->
                <div class="inner-addon right-addon">
                    <input name="user_last_name" id="user_last_name" class="required form-control" type="text" placeholder="<?php esc_html_e( 'Last Name', 'knowing-god' ); ?>" value="<?php echo knowing_god_get_value( $_POST, 'user_last_name' ); ?>"/>
                </div>
            </div>

            <div class="form-group col-sm-12">
<!--                <label for="user_password"><?php esc_html_e( 'Password', 'knowing-god' ); ?><?php echo knowing_god_required_field(); ?></label>-->
                <div class="inner-addon right-addon">
                    <input name="user_password" id="user_password" class="password required form-control" type="password" placeholder="<?php esc_html_e( 'Password', 'knowing-god' ); ?>"/>
                </div>
            </div>

            <div class="form-group col-sm-12">
<!--                <label for="user_password_confirm"><?php esc_html_e( 'Confirm Password', 'knowing-god' ); ?><?php echo knowing_god_required_field(); ?></label>-->
                <div class="inner-addon right-addon">
                    <input name="user_password_confirm" id="user_password_confirm" class="password required form-control" type="password" placeholder="<?php esc_html_e( 'Confirm Password', 'knowing-god' ); ?>"/>
                </div>
            </div>

        <?php $countryList = knowing_god_get_countries(); ?>
            <div class="form-group col-sm-6">
<!--            <label for="mobile_countrycode"><?php esc_html_e( 'Country code', 'knowing-god' ); ?><?php echo knowing_god_required_field(); ?></label>-->
            <div class="inner-addon right-addon">
            <select id="mobile_countrycode" name="mobile_countrycode" title="<?php esc_html_e( 'Country code', 'knowing-god' ); ?>"class="selectpicker show-tick show-menu-arrow">
            <option value=""><?php esc_html_e( 'Country code', 'knowing-god' ); ?></option>
            <?php
            if ( $countryList) {
                $mobile_countrycode = knowing_god_get_value( $_POST, 'mobile_countrycode' );
                foreach ( $countryList as $result) {
                    $code = $result->phonecode.'_'.$result->id_countries;
                    ?>
                    <option value="<?php echo $code; ?>" <?php if ( $mobile_countrycode == $code) echo 'selected="selected"'; ?>><?php echo $result->name . ' ( ' . $result->phonecode . ' )'; ?> </option>
                    <?php
                }
            }
            ?>
            </select>
            </div>
        </div>
        <div class="form-group col-sm-6">
<!--            <label for="mobile"><?php esc_html_e( 'Mobile phone', 'knowing-god' ); ?><?php echo knowing_god_required_field(); ?></label>-->
            <div class="inner-addon right-addon">
                <?php
                $mobile = knowing_god_get_value( $_POST, 'mobile' );
                ?>
                <input type="text" class="form-control" id="mobile" name="mobile" placeholder="<?php esc_html_e( 'Mobile phone', 'knowing-god' ); ?>" value="<?php echo esc_attr( $mobile); ?>">
            </div>
        </div>

        <?php if ( function_exists( 'siwp_captcha_html' ) ) : ?>
        <div class="form-group col-sm-12">
            <label for="captcha_challenge_field"><?php _e( 'Enter Characters Shown', 'knowing-god' ); ?></label>
            <div class="inner-addon right-addon">
            <span class="knowing_god_fieldbox">
                <span class="knowing_god_fieldbox_img">
                <?php echo do_shortcode( '[siwp_show_captcha]' ); ?>
                </span>
                <span class="knowing_god_fieldbox_clearfix clearfix"></span>
            </span>
            <span class="knowing_god_clearfix"></span>
            </div>
        </div>
        <?php endif; ?>

            <div class="col-sm-12">
            <p class="knowing-god-login-submit">
                <input type="hidden" name="redirect_to" value="<?php echo knowing_god_urls( 'user_bookings' ); ?>" />
                <input type="hidden" name="knowing_god_register_nonce" value="<?php echo wp_create_nonce( 'knowing-god-register-nonce' ); ?>"/>
                <input type="hidden" name="knowing_god_action" value="user_login"/>
                <input id="knowing_god_login_submit" type="submit" class="knowing_god_submit btn btn-info" value="<?php esc_html_e( 'Register', 'knowing-god' ); ?>"/>
            </p>
            </div>
            <div class="form-group col-sm-12 st-login-tags">
                <a href="<?php echo knowing_god_urls( 'login' ); ?>"><small style="color:#838383">Have an account?</small>
                    <?php esc_html_e( 'Login', 'knowing-god' ); ?>
                </a>
            </div>

        </div>
    </form>
    <script type="text/javascript">
    jQuery( '#knowing_god_register_form' ).submit(function (event) {
        var user_login = jQuery( '#user_login' ).val();
        var error = 0;
        jQuery( '.error' ).hide();
        if ( user_login == "" ) {
            jQuery( '#user_login' ).after( '<span class="error"><?php esc_html_e( 'Please enter username', 'knowing-god' );?></span>' );
            error++;
        }

        var user_email = jQuery( '#user_email' ).val();
        if ( user_email == "" ) {
            jQuery( '#user_email' ).after( '<span class="error"><?php esc_html_e( 'Please enter email address', 'knowing-god' );?></span>' );
            error++;
        } else if ( ! /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(user_email ) ) {
            jQuery( '#user_email' ).after( '<span class="error"><?php esc_html_e( 'Please enter valid email address', 'knowing-god' );?></span>' );
            error++;
        }

        var user_first_name = jQuery( '#user_first_name' ).val();
        if ( user_first_name == "" ) {
            jQuery( '#user_first_name' ).after( '<span class="error"><?php esc_html_e( 'Please enter first name', 'knowing-god' );?></span>' );
            error++;
        }

        var user_password = jQuery( '#user_password' ).val();
        if ( user_password == "" ) {
            jQuery( '#user_password' ).after( '<span class="error"><?php esc_html_e( 'Please enter password', 'knowing-god' );?></span>' );
            error++;
        } else if ( user_password.length < 8 ) {
            jQuery( '#user_password' ).after( '<span class="error"><?php esc_html_e( 'Password should be minimum 8 characters', 'knowing-god' );?></span>' );
            error++;
        }

        var user_password_confirm = jQuery( '#user_password_confirm' ).val();
        if ( user_password_confirm == "" ) {
            jQuery( '#user_password_confirm' ).after( '<span class="error"><?php esc_html_e( 'Please enter confirm password', 'knowing-god' );?></span>' );
            error++;
        }

        if ( user_password != user_password_confirm ) {
            jQuery( '#user_password' ).after( '<span class="error"><?php esc_html_e( 'Passwords do not match', 'knowing-god' );?></span>' );
            error++;
        }

        var captcha_challenge_field = jQuery( '#captcha_challenge_field' ).val();
        if ( captcha_challenge_field == "" ) {
            jQuery( '#captcha_challenge_field' ).after( '<span class="error"><?php esc_html_e( 'Please enter characters shown', 'knowing-god' );?></span>' );
            error++;
        }

        if ( error > 0 ) {
            event.preventDefault();
        }
    });
    </script>
<?php else : ?>
    <p class="knowing-god-logged-in"><?php esc_html_e( 'You are already logged in', 'knowing-god' ); ?></p>
<?php endif; ?>
