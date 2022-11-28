<?php
/**
 * This template is used to display the login form with [vehicle_login]
 *
 * @package     Knowing God
 * @subpackage  Login
 * @copyright   Copyright (c) 2017, Digisamaritan
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! is_user_logged_in() ) :

    $wp_error = new WP_Error();
    global $wpdb;
    if ( isset( $_POST ) && ! empty( $_POST ) ) {

        $username = trim( $_POST['log'] );
        $password = trim( $_POST['pwd'] );
        $remember = trim( $_POST['rememberme'] );

        if ( $remember ) {
            $remember = true;
        } else {
            $remember = false;
        }

        $data = $_POST;
        if ( wp_verify_nonce( $data['knowing_god_login_nonce'], 'knowing-god-login-nonce' ) ) {
            if ( $data['log'] == '' ) {
                $wp_error->add( 'log', esc_html__( 'Please enter Username or Email', 'knowing-god' ));
            }
            if ( $data['pwd'] == '' ) {
                $wp_error->add( 'pwd', esc_html__( 'Please enter password', 'knowing-god' ));
            }
            if ( $data['log'] != '' && $data['pwd'] != '' ) {

                $user_data_login = get_user_by( 'login', $data['log'] );
                $user_data_email = get_user_by( 'email', $data['log'] );
                $user_meta = '';
                if ( $user_data_login || $user_data_email ) {
                    if ( $user_data_login ) {
                        $user_meta = get_userdata( $user_data_login->ID );
                    } elseif ( $user_data_email ) {
                        $user_meta = get_userdata( $user_data_email->ID );
                    }
                }

                if ( ! empty( $user_meta ) && ! empty( $user_meta->roles ) ) {
                    $login_data = array();
                    $login_data['user_login'] = $username;
                    $login_data['user_password'] = $password;
                    $login_data['remember'] = $remember;

                    $wp_error = wp_signon( $login_data, false );

                    if ( ! isset( $wp_error->errors ) ) {

                        $is_admin = false;
                        $check_roles = $wp_error->roles;
                        if( ! empty( $check_roles ) ) {
                            foreach( $check_roles as $role ) {
                                if ( $role === 'administrator' ) {
                                    $is_admin = true;
                                }
                            }
                        }
                        if ( $is_admin ) {
                            $redirect_to = admin_url();
                        } else {

                            $data = $wp_error->data;
                            $email = $data->user_email;

                            // Let us insert into LMS users table
                            $query = sprintf( "SELECT * FROM users WHERE ( username = '%s' OR email = '%s' )", $username, $username );
                            $records = $wpdb->get_results( $query );
                            if ( empty( $records ) ) {
                                $role_id = ROLE_SUBSCRIBER;
                                if( ! empty( $roles ) ) {
                                    foreach( $roles as $role ) {
                                        if ( $role === 'administrator' ) {
                                            $role_id = ROLE_OWNER;
                                        } elseif ( $role === 'administrator' ) {
                                            $role_id = ROLE_ADMIN;
                                        }
                                    }
                                }

                                $lms_user = array(
                                    'name' => $data->display_name,
                                    'first_name' => get_user_meta( $data->ID, 'first_name', true ),
                                    'last_name' => get_user_meta( $data->ID, 'last_name', true ),
                                    'email' => $email,
                                    'password' => $data->user_pass,
                                    'role_id' => $role_id,
                                    'login_enabled' => 1,
                                    'username' => $data->user_login,
                                    'slug' => sanitize_title( $data->display_name ),
                                    'phone' => '',
                                    'phone_code' => '',
                                    'created_at' => date( 'Y-m-d H:i:s' ),
                                    'updated_at' => date( 'Y-m-d H:i:s' ),
                                    'wp_user_id' => $data->ID,
                                );
                                $wpdb->insert( 'users', $lms_user );
                                $user_id = $wpdb->insert_id;
                                if ( $user_id > 0 ) {
                                    $wpdb->insert( 'role_user', array(
                                        'user_id' => $user_id,
                                        'role_id' => $role_id,
                                    ) );
                                $redirect_to = knowing_god_urls( 'lms' ) . 'login/' . base64_encode( $user_id );
                                }
                            } else {
                                $user_id = $records[0]->id;
                                $redirect_to = knowing_god_urls( 'lms' ) . 'login/' . base64_encode( $user_id );
                            }
                        }
                        wp_safe_redirect( $redirect_to );
                    }

                } else {
                    $wp_error->add( 'denied', esc_html__( 'You need to activate your account. Please check your inbox to find activation link' ) );//create an error
                }
            }
        }
    }
    $rememberme = ! empty( $_POST['rememberme'] );
?>
<div class="col-md-8 mx-auto">

    <form id="knowing_god_login_form" class="knowing_god_form mb-5" action="" method="post">

            <?php
            if ( 'hide' === knowing_god_page_banner() && 'hide' === get_post_meta( get_the_ID(), 'page_title', true ) ) : ?>
            <h2 class="mb-4 col-sm-12"><?php esc_html_e( 'Login' ); ?></h2>
            <?php endif; ?>

            <?php if ( ! empty( $wp_error->errors ) ) { ?>
                <div class="alert alert-danger">
                    <ul><?php echo implode( '</li><li>', $wp_error->get_error_messages() );?></ul>
                </div>
            <?php } elseif ( isset( $_GET['action'] ) && $_GET['action'] == 'registered' ) {
                ?>
                <div class="alert alert-success">
                    <ul><li><?php esc_html_e( 'You have registered sucessfully. You need to activate your account. Please check your inbox to find activation link.', 'knowing-god' );?></li></ul>
                </div>
                <?php
            } elseif ( isset( $_GET['action'] ) && $_GET['action'] == 'activated' ) {
                ?>
                <div class="alert alert-success">
                    <ul><li><?php esc_html_e( 'Acount activated successfully. Please login here.', 'knowing-god' );?></li></ul>
                </div>
                <?php
            } elseif ( isset( $_GET['action'] ) && $_GET['action'] == 'activation-failed' ) {
                ?>
                <div class="alert alert-danger">
                    <ul><li><?php esc_html_e( 'Invalid account details.', 'knowing-god' );?></li></ul>
                </div>
                <?php
            } elseif ( isset( $_GET['action'] ) && $_GET['action'] == 'no-register-access' ) {
                ?>
                <div class="alert alert-danger">
                    <ul><li><?php esc_html_e( 'You dont have option to register.', 'knowing-god' );?></li></ul>
                </div>
                <?php
            } ?>

            <div class="form-group col-sm-12">
<!--                <label for="knowing_god_user_login"> <?php esc_html_e( 'Username or Email', 'knowing-god' ); ?><?php echo knowing_god_required_field(); ?></label>-->
                <div class="inner-addon right-addon">
                    <input name="log" id="knowing_god_user_login" class="required form-control" type="text" placeholder="<?php esc_html_e( 'Username or Email', 'knowing-god' );?>"/>
                </div>
            </div>

            <div class="form-group col-sm-12">
<!--                <label for="knowing_god_user_pass"><?php esc_html_e( 'Password', 'knowing-god' ); ?><?php echo knowing_god_required_field(); ?></label>-->
                <div class="inner-addon right-addon">
                    <input name="pwd" id="knowing_god_user_pass" class="password required form-control" type="password" placeholder="<?php esc_html_e( 'Password', 'knowing-god' ); ?>"/>
                </div>
            </div>

            <div class="form-group col-sm-12">
                <div>
                    <input id="rememberme" type="hidden" name="rememberme" value="forever">
                    <!-- <input id="rememberme" type="checkbox" name="rememberme" value="forever" <?php checked( $rememberme ); ?>>
                    <label for="rememberme"><span><span></span></span><i class="st-terms-accept"><?php esc_html_e( 'Remember Me', 'knowing-god' ); ?></i></label> -->
                </div>
            </div>


            <p class="knowing-god-login-submit col-sm-12">

                <input type="hidden" name="knowing_god_login_nonce" value="<?php echo wp_create_nonce( 'knowing-god-login-nonce' ); ?>"/>
                <input type="hidden" name="knowing_god_action" value="user_login"/>
                <input id="knowing_god_login_submit" type="submit" class="knowing_god_submit btn btn-info" value="<?php esc_html_e( 'Log In', 'knowing-god' ); ?>"/>
            </p>

        <div class="forgot-links col-sm-12">
            <?php echo do_shortcode( '[wordpress_social_login]' ); ?>
            <a href="<?php echo wp_lostpassword_url(); ?>" class="pull-left">
                <?php esc_html_e( 'Forgot password?', 'knowing-god' ); ?>
            </a>

            <span  class="pull-right"><small style="color:#838383; ">Don't have an account? </small>
                <a href="<?php echo knowing_god_urls( 'registration' ); ?>">
                    <?php esc_html_e( 'Register', 'knowing-god' ); ?>
                </a>
            </span>

        </div>

    </form>

</div>
    <script>
    jQuery( '#knowing_god_login_form' ).submit(function (event) {
        var knowing_god_user_login = jQuery( '#knowing_god_user_login' ).val();
        var knowing_god_user_pass = jQuery( '#knowing_god_user_pass' ).val();
        var error = 0;
        jQuery( '.error' ).hide();
        if (knowing_god_user_login == "") {
            jQuery( '#knowing_god_user_login' ).after( '<span class="error"><?php esc_html_e( 'Please enter username OR email address', 'knowing-god' );?></span>' );
            error++;
        }
        if (knowing_god_user_pass == "") {
            jQuery( '#knowing_god_user_pass' ).after( '<span class="error"><?php esc_html_e( 'Please enter password', 'knowing-god' );?></span>' );
            error++;
        }
        if (error > 0) event.preventDefault();
    });
    </script>
<?php else : ?>
    <p class="knowing-god-logged-in"><?php esc_html_e( 'You are already logged in', 'knowing-god' ); ?></p>
<?php endif; ?>
