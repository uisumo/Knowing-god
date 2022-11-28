<?php
/**
 * Class to send email activation mail to user and to actiavate user through email
 */
class knowing_god_email_verification {

  /**
   * The only instance of knowing_god_email_verification.
   *
   * @var knowing_god_email_verification
   */
  private static $instance;

  /**
   * Returns the main instance.
   *
   * @return knowing_god_email_verification
   */
  public static function instance() {
    if ( !isset( self::$instance ) ) {
      self::$instance = new knowing_god_email_verification();
    }
    return self::$instance;
  }

  private function __construct() {
  	//login auth verify
  	add_filter( 'wp_authenticate_user', array( $this, 'authenticate_user' ) );
   //user register add user status
    add_action( 'user_register', array( $this, 'add_user_status' ) );
   //email verification user status update
    add_action( 'new_user_approve_approve_user', array( $this, 'approve_user' ) );
  }


   /**
   * Determine if the user is good to sign in based on their status.
   *
   * @uses wp_authenticate_user
   * @param array $userdata
   */
   public function authenticate_user( $userdata ) {
   //echo '<pre>'; print_r($userdata ); exit();
    $status = $this->get_user_status( $userdata->ID );

       if ( empty( $status ) ) {
      // the user does not have a status so let's assume the user is good to go
      return $userdata;
    }

    $message = false;
    switch ( $status ) {
      case 'pending':
        $pending_message = $this->default_authentication_message( 'pending' );
        $message = new WP_Error( 'pending_approval', $pending_message );
        break;
      case 'denied':
        $denied_message = $this->default_authentication_message( 'denied' );
        $message = new WP_Error( 'denied_access', $denied_message );
        break;
      case 'approved':
        $message = $userdata;
        break;
    }

    return $message;
  }
  /**
   * Get the status of a user.
   *
   * @param int $user_id
   * @return string the status of the user
   */
   public function get_user_status( $user_id ) {
    $user_status = get_user_meta( $user_id, 'pw_user_status', true );

    if ( empty( $user_status ) ) {
      $user_status = 'approved';
    }

    return $user_status;
  }


  /**
   * The default message that is shown to a user depending on their status
   * when trying to sign in.
   *
   * @return string
   */
  public function default_authentication_message( $status ) {
    $message = '';

    if ( $status == 'pending' ) {
      $message = __( '<strong>ERROR</strong>: Your account is still pending approval.', 'new-user-approve' );
      $message = apply_filters( 'new_user_approve_pending_error', $message );
    } else if ( $status == 'denied' ) {
      $message = __( '<strong>ERROR</strong>: Your account has been denied access to this site.', 'new-user-approve' );
      $message = apply_filters( 'new_user_approve_denied_error', $message );
    }

    $message = apply_filters( 'new_user_approve_default_authentication_message', $message, $status );

    return $message;
  }

  /**
	 * Give the user a status
	 *
	 * @uses user_register
	 * @param int $user_id
	 */
	public function add_user_status( $user_id ) {
    $user_info = get_userdata( $user_id );
    $user_email_id = $user_info->user_email;
    $redirectURL = wp_login_url();
    $to = $user_email_id;
    $subject = "Email confirmation from ".get_bloginfo( 'name' );

    $myVar = 'QgYLt\J]g$M8n$2S';    

    $user_id_encoded = base64_encode( $user_id );

    add_filter( 'send_password_change_email', '__return_false' );

    $msg = "<html><body><h1 style='margin:10px;'>Welcome to "
           .get_bloginfo( 'name' )."</h1><p style='margin:10px;'>Click on the below button to activate your account.</p><a style='margin:10px; background-color: #000000;color: #fff; padding: 10px; float: left;
             text-decoration: none;border-radius: 5px;' 
             href= $redirectURL?uid=$user_id_encoded&referrel=email>
             VERIFY YOUR ACCOUNT </a><br><br><br><br><br>
             <p style='margin:10px;'></body></html>";

    $headers = 'From: '.get_bloginfo( 'name' ).' <'.get_bloginfo( 'admin_email' ).'>'. "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    wp_mail( $to, $subject, $msg,  $headers);



		$status = 'pending';

		// This check needs to happen when a user is created in the admin
		if ( isset( $_REQUEST['action'] ) && 'createuser' == $_REQUEST['action'] ) {
			$status = 'approved';
		}
		update_user_meta( $user_id, 'pw_user_status', $status );

	}


	/**
	 * email verfied by user
	 *
	 * @uses new_user_approve_approve_user
	 */
	public function approve_user( $user_id ) {
  		global $wpdb;

  		$user = new WP_User( $user_id );

  		wp_cache_delete( $user->ID, 'users' );
  		wp_cache_delete( $user->data->user_login, 'userlogins' );

  		// change usermeta tag in database to approved
  		update_user_meta( $user->ID, 'pw_user_status', 'approved' );
		
		$user->set_role( 'subscriber' );
      
      $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
      $chars .= '!@#$%^&*()';
      $chars .= '-_ []{}<>~`+=,.;:/?|';
        $password = '';
        for ( $i = 0; $i < 8; $i++ ) {
          $password .= substr($chars, rand(0, strlen($chars) - 1), 1);
        }

          $key = $password;

  	    /** This action is documented in wp-login.php */
  	    do_action( 'retrieve_password_key', $user->user_login, $key );

  	    // Now insert the key, hashed, into the DB.
  	    if ( empty( $wp_hasher ) ) {
  	        require_once ABSPATH . WPINC . '/class-phpass.php';
  	        $wp_hasher = new PasswordHash( 8, true );
  	    }
  	    $hashed = time() . ':' . $wp_hasher->HashPassword( $key );

  		$wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user->user_login ) );

  		do_action( 'new_user_approve_user_approved', $user );
  	}

  }

  /* initialize instance */
  function knowing_god_email_verification_call() {
    return knowing_god_email_verification::instance();
  }
  knowing_god_email_verification_call();
  