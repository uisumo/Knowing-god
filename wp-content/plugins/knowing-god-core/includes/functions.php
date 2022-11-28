<?php
function knowing_god_core_scripts() {
	wp_enqueue_style( 'select2', KNOWING_GOD_PLUGIN_URL . '/css/select2.min.css' );

	wp_enqueue_script( 'select2', KNOWING_GOD_PLUGIN_URL . '/js/select2.min.js', array( 'jquery' ), '4.0.6' );

	wp_enqueue_script( 'main-core', KNOWING_GOD_PLUGIN_URL . '/js/main-core.js', array( 'jquery' ), '1.0' );
}
add_action( 'wp_enqueue_scripts', 'knowing_god_core_scripts', 2 );

if ( ! function_exists( 'knowing_god_signin' ) ) :
	/**
	 * [knowing_god_signin]
	 * @param array $atts - Attributes
	 * @return shortcode page
	 */
	function knowing_god_signin( $atts ){
		include_once( KNOWING_GOD_PLUGIN_PATH . 'includes/pages/login.php' );
	}
endif;
add_shortcode( 'knowing_god_signin', 'knowing_god_signin' );

if ( ! function_exists( 'knowing_god_register' ) ) :
	/**
	 * [knowing_god_signin]
	 * @param array $atts - Attributes
	 * @return shortcode page
	 */
	function knowing_god_register( $atts ){
		include_once( KNOWING_GOD_PLUGIN_PATH . 'includes/pages/register.php' );
	}
endif;
add_shortcode( 'knowing_god_register', 'knowing_god_register' );

if ( ! function_exists( 'knowing_god_required_field' ) ) {
	/**
	 * Returns the "*" mark
	 *
	 * @since 1.0
	 * @return string
	 */
	function knowing_god_required_field() {
		return '<font color="red">*</font>';
	}
}

if ( ! defined( 'knowing_god_is_user' ) ) :
	/**
	 * This function check whenter the user is belongs to the particualte user role or not
	 * @param string $user_role - Role.
	 */
	function knowing_god_is_user( $user_role ) {
		$current_user = ( array ) wp_get_current_user();
		$roles = array();
		if ( isset( $current_user['roles'] ) ) {
			foreach ( $current_user['roles'] as $role ) {
				$roles[] = trim( $role );
			}
		}
		if ( in_array( $user_role, $roles ) )
			return true;
		else
			return false;
	}
endif;

add_action( 'init', 'knowing_god_do_output_buffer' );
/**
 * Allow redirection even if my theme starts to send output to the browser
 */
function knowing_god_do_output_buffer() {
	ob_start();
}

add_action( 'init','knowing_god_redirect_login_page' );
if ( ! function_exists( 'knowing_god_redirect_login_page' ) ) :
	/**
	 * This function redirect the default wordpress login page
	 */
	function knowing_god_redirect_login_page(){

		// Store for checking if this page equals wp-login.php
		$page_viewed = basename( $_SERVER['REQUEST_URI'] );
		$login_pattern =$_SERVER['PHP_SELF'];
		$link_array = explode( '/',$login_pattern );
		$page_viewed = end( $link_array );

		// permalink to the custom login page
		$login_page  = knowing_god_urls( 'login' );

		if ( (( $page_viewed == "wp-login.php" && ! isset( $_POST ) )  || ( isset( $_REQUEST['loggedout'] ) ) )
		){
			wp_redirect( $login_page );
			exit();
		}

		/* activation success / error message */
		if (isset( $_GET['uid'] ) && isset( $_GET['referrel'] ) && $_GET['referrel'] == 'email') {
			$ID = rtrim( base64_decode( $_GET['uid'] ) );
			$userExist = knowing_god_user_id_exists( $ID );
			if( $userExist ){
			  do_action( 'new_user_approve_approve_user', $ID );
			  echo '<span class="alert alert-success">Acount activated successfully</span>';
			  $redirect = add_query_arg( array( 'action' => 'activated' ), knowing_god_urls( 'login' ) );
			}else{
			  echo '<span class="alert alert-danger">Invalid account details </span>';
			  $redirect = add_query_arg( array( 'action' => 'activation-failed' ), knowing_god_urls( 'login' ) );
			}
			wp_safe_redirect( $redirect );
		}
	}
endif;

if ( ! function_exists( 'knowing_god_urls' ) ) {
	/**
	* Filter for Booking steps Page URLs. We are creating filter so that later any one can change URLs to their requirements
	*
	* @since 1.0
	* @param string $slug - URL to get.
	* @return string
	*/
	function knowing_god_urls( $slug ) {
		global $wpdb;
		$is_logged_in = is_user_logged_in();
		$user_slug = '';
		if ( ! empty( $_COOKIE['kg_user'] ) ) {
			$user_name = $_COOKIE['kg_user'];
			$user = $wpdb->get_row( $wpdb->prepare("SELECT * FROM users WHERE username = '%s'", base64_decode( $user_name ) ) );

			if( ! empty( $user ) )
			{
				$user_slug = $user->slug;
			}
		}
		$lms_user_id = knowing_god_get_lms_user_id();
		if ( $lms_user_id > 0 ) {
			$user = $wpdb->get_row( $wpdb->prepare("SELECT * FROM users WHERE id = '%d'", $lms_user_id ) );
			if( ! empty( $user ) )
			{
				$user_slug = $user->slug;
			}
		}
		$urls = array(
			// 'login' => get_permalink( get_page_by_path( 'sign-in' ) ),
			'login' => get_site_url() . '/' . LMS_FOLDER . '/login',
			// 'registration' => get_permalink( get_page_by_path( 'registration' ) ),
			'registration' => get_site_url() . '/' . LMS_FOLDER . '/register',
			'forgotpassword' => get_permalink( get_page_by_path( 'forgotpassword' ) ),
			'resetpassword' => get_permalink( get_page_by_path( 'resetpassword' ) ),
			'my-account' => get_site_url() . '/' . LMS_FOLDER . '/dashboard',
			'my-account-user' => get_site_url() . '/' . LMS_FOLDER . '/user-dashboard',
			'lms' => get_site_url() . '/' . LMS_FOLDER . '/',
			'lms-login' => get_permalink( get_page_by_path( 'wp-laravel-sync' ) ),
			'lms-logout' => get_site_url() . '/' . LMS_FOLDER . '/lms-logout',
			'user-settings' => get_site_url() . '/' . LMS_FOLDER . '/users/settings/',


			'wp-document' => get_site_url() . '/KG-Documentation/pages/index.html',
			'lms-document' => get_site_url() . '/LMS-Documentation/index.html',

			'my-profile' => get_site_url() . '/' . LMS_FOLDER . '/users/edit/' . $user_slug,
			'my-courses' => get_site_url() . '/' . LMS_FOLDER . '/my-courses',
			'my-groups' => get_site_url() . '/' . LMS_FOLDER . '/lms/my-groups',
			'user-categories' => get_site_url() . '/' . LMS_FOLDER . '/lms/categories',
			'user-messages' => get_site_url() . '/' . LMS_FOLDER . '/messages',
			'feedback' => get_site_url() . '/' . LMS_FOLDER . '/feedback/send',
			'translation' => get_site_url() . '/' . LMS_FOLDER . '/send-translation-request',
			'take-quiz' => get_site_url() . '/' . LMS_FOLDER . '/exams/student/quiz/instructions/',
			'lms-url' => get_site_url() . '/' . LMS_FOLDER . '/',

			'by-pathway' => get_site_url() . '/' . LMS_FOLDER . '/student/analysis/subject/' . $user_slug,
			'by-exam' => get_site_url() . '/' . LMS_FOLDER . '/student/analysis/by-exam/' . $user_slug,
			'history' => get_site_url() . '/' . LMS_FOLDER . '/exams/student/exam-attempts/' . $user_slug,
			
			'search-action' => get_site_url() . '/' . LMS_FOLDER . '/global-search',
			'group-dashboard' => get_site_url() . '/' . LMS_FOLDER . '/lms/group-dashboard/',
			'show-course' => get_site_url() . '/' . LMS_FOLDER . '/lms/course/',
			);

		if ( isset( $urls[ $slug ] ) ) return $urls[ $slug ];
		else return '';
	}
}

if ( ! function_exists( 'knowing_god_get_value' ) ) {
	/**
	 * Returns the value from a given array
	 *
	 * @param array|stdClass $item.
	 * @param string $key.
	 * @param string $default - Optional.
	 * @since 1.0
	 * @return string
	 */
	function knowing_god_get_value( $item, $key, $default = '' ) {
		$value = $default;
		if ( isset( $_POST[ $key ] ) ) {
			$value = $_POST[ $key ];
		}
		elseif ( is_array( $item ) ) {
			if ( isset( $item[ $key ] ) ) {
				$value = $item[ $key ];
			}
		} else {
			if ( isset( $item->$key ) ) {
				$value = $item->$key;
			}
		}
		return $value;
	}
}

if ( ! function_exists( 'knowing_god_get_countries' ) ) :
	/**
	 * Let us get countries list which are available
	 *
	 * @global wpdb  $wpdb  WordPress database abstraction object.
	 * @since 1.0
	 */
	function knowing_god_get_countries() {
		global $wpdb;
		$query = "SELECT * FROM " . $wpdb->prefix."kg_countries ORDER BY name ASC";
		return $wpdb->get_results( $query);
	}
endif;

/* Check user ID is valid or not when email verification  */
function knowing_god_user_id_exists($user){
  global $wpdb;
  $count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->users WHERE ID = %d", $user));
  if($count == 1){ return true; }else{ return false; }
}

add_action('init', 'knowing_god_session_manager');
function knowing_god_session_manager() {
	if ( ! session_id() ) {
		session_start();
	}
}

if ( ! function_exists( 'knowing_god_crypto_rand_secure' ) ){
    /**
	 * Returns the random string which is used in 'knowing_god_get_token'
	 * @since 1.0
	 * @return string
	 */
	function knowing_god_crypto_rand_secure( $min, $max ) {
        $range = $max - $min;
        if ( $range < 1 ) return $min; // not so random...
        $log = ceil(log( $range, 2) );
        $bytes = (int) ( $log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes( $bytes ) ) );
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ( $rnd >= $range );
        return $min + $rnd;
    }
}

if ( ! function_exists( 'knowing_god_get_token' ) ) {
	/**
	 * Returns random string
	 *
	 * @since 1.0
	 * @return string
	 */
	function knowing_god_get_token( $length = 6 ) {
		$token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "0123456789";
        $max = strlen( $codeAlphabet ) - 1;
        for ( $i=0; $i < $length; $i++) {
            $token .= $codeAlphabet[ knowing_god_crypto_rand_secure( 0, $max ) ];
        }
        return $token;
	}
}

add_action( 'login_form_lostpassword', 'knowing_god_redirect_to_custom_lostpassword' );
if ( ! function_exists( 'knowing_god_redirect_to_custom_lostpassword' ) ) :
	/**
	 * This function redirect the custom lost password page.
	 */
	function knowing_god_redirect_to_custom_lostpassword() {
		if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
			if ( is_user_logged_in() ) {
				wp_redirect( home_url() );
				exit;
			}

			wp_redirect( knowing_god_urls( 'forgotpassword' ) );
			exit;
		}
	}
endif;

add_action( 'wp_ajax_lost_pass','knowing_god_lost_pass' ); // wp_ajax_{$action} hook.
add_action( 'wp_ajax_nopriv_lost_pass','knowing_god_lost_pass' ); // wp_ajax_nopriv_{$action} hook.
if ( ! function_exists( 'knowing_god_lost_pass' ) ) :
	/*
	 *	Process lost password
	 */
	function knowing_god_lost_pass() {

		global $wpdb, $wp_hasher;

		$nonce = $_POST['nonce'];

		if ( ! wp_verify_nonce( $nonce, 'rs_user_lost_password_action' ) )
			die ( 'Security checked!' );

		//We shall SQL escape all inputs to avoid sql injection.
		$user_login = $_POST['user_login'];

		$errors = new WP_Error();

		if ( empty( $user_login ) ) {
			$errors->add( 'empty_username', esc_html__( 'Enter a username or e-mail address.', 'knowing-god' ) );
		} elseif ( strpos( $user_login, '@' ) ) {
			$user_data = get_user_by( 'email', trim( $user_login ) );
			if ( empty( $user_data ) )
				$errors->add( 'invalid_email', esc_html__( 'There is no user registered with that email address.', 'knowing-god' ) );
		} else {
			$login = trim( $user_login );
			$user_data = get_user_by( 'login', $login );
		}
		if ( ! $user_data ) {
			$errors->add( 'invalidcombo', esc_html__( 'Invalid username or email.', 'knowing-god' ) );
		}

		/**
		 * Fires before errors are returned from a password reset request.
		 *
		 * @since 2.1.0
		 * @since 4.4.0 Added the `$errors` parameter.
		 *
		 * @param WP_Error $errors A WP_Error object containing any errors generated
		 *                         by using invalid credentials.
		 */
		do_action( 'lostpassword_post', $errors );

		if ( $errors->get_error_code() ) {
			echo '<p class="error"><b>' . $errors->get_error_message( $errors->get_error_code() ) . '</b></p>';
		} else {
		// Redefining user_login ensures we return the right case in the email.
		$user_login = $user_data->user_login;
		$user_email = $user_data->user_email;
		$key = get_password_reset_key( $user_data );

		if ( is_wp_error( $key ) ) {
			$errors->add( 'invalidkey', esc_html__( 'Invalid key generated. Refresh the page and try again.', 'knowing-god' ) );
		}

		$message = esc_html__( 'Someone requested that the password be reset for the following account:', 'knowing-god' ) . "\r\n\r\n";
		$message .= network_home_url( '/' ) . "\r\n\r\n";
		$message .= sprintf(esc_html__( 'Username: %s', 'knowing-god' ), $user_login) . "\r\n\r\n";
		$message .= esc_html__( 'If this was a mistake, just ignore this email and nothing will happen.', 'knowing-god' ) . "\r\n\r\n";
		$message .= esc_html__( 'To reset your password, visit the following address:', 'knowing-god' ) . "\r\n\r\n";

		// replace PAGE_ID with reset page ID
		$message .= knowing_god_urls( 'resetpassword' ) . "/?action=rp&key=$key&login=" . rawurlencode( $user_login) . "\r\n";

		if ( is_multisite() )
			$blogname = $GLOBALS['current_site']->site_name;
		else
			/*
			 * The blogname option is escaped with esc_html on the way into the database
			 * in sanitize_option we want to reverse this for the plain text arena of emails.
			 */
			$blogname = wp_specialchars_decode(get_option( 'blogname' ), ENT_QUOTES);

		$title = sprintf( esc_html__( '[%s] Password Reset', 'knowing-god' ), $blogname );

		/**
		 * Filter the subject of the password reset email.
		 *
		 * @since 2.8.0
		 * @since 4.4.0 Added the `$user_login` and `$user_data` parameters.
		 *
		 * @param string  $title      Default email title.
		 * @param string  $user_login The username for the user.
		 * @param WP_User $user_data  WP_User object.
		 */
		$title = apply_filters( 'retrieve_password_title', $title, $user_login, $user_data );

		/**
		 * Filter the message body of the password reset mail.
		 *
		 * @since 2.8.0
		 * @since 4.1.0 Added `$user_login` and `$user_data` parameters.
		 *
		 * @param string  $message    Default mail message.
		 * @param string  $key        The activation key.
		 * @param string  $user_login The username for the user.
		 * @param WP_User $user_data  WP_User object.
		 */
		$message = apply_filters( 'retrieve_password_message', $message, $key, $user_login, $user_data );

		if ( wp_mail( $user_email, wp_specialchars_decode( $title ), $message ) ) {
			$errors->add( 'confirm', esc_html__( 'Check your e-mail for the confirmation link.', 'knowing-god' ), 'message' );
		} else {
			$errors->add( 'could_not_sent', esc_html__( 'The e-mail could not be sent.', 'knowing-god' ) . "<br />\n" . esc_html__( 'Possible reason: your host may have disabled the mail() function.', 'knowing-god' ), 'message' );
		}

		// display error message
		if ( $errors->get_error_code() ) {
			echo '<p class="error"><b>' . $errors->get_error_message( $errors->get_error_code() ) .'</b></p>';
		}
		}
		// return proper result
		die();
	}
endif;

add_action( 'wp_ajax_nopriv_reset_pass', 'knowing_god_reset_pass_callback' );
add_action( 'wp_ajax_reset_pass', 'knowing_god_reset_pass_callback' );
if ( ! function_exists( 'knowing_god_reset_pass_callback' ) ) :
	/*
	 *	Process reset password
	 */
	function knowing_god_reset_pass_callback() {

		$errors = new WP_Error();
		$nonce = $_POST['nonce'];

		if ( ! wp_verify_nonce( $nonce, 'rs_user_reset_password_action' ) )
			die ( 'Security checked!' );

		$pass1 	= $_POST['pass1'];
		$pass2 	= $_POST['pass2'];
		$key 	= $_POST['user_key'];
		$login 	= $_POST['user_login'];

		$user = check_password_reset_key( $key, $login );

		// check to see if user added some string
		if ( empty( $pass1 ) || empty( $pass2 ) ) {
			$errors->add( 'password_required', esc_html__( 'Password is required field', 'knowing-god' ) );
		}

		// is pass1 and pass2 match?
		if ( isset( $pass1 ) && $pass1 != $pass2 ) {
			$errors->add( 'password_reset_mismatch', esc_html__( 'The passwords do not match.', 'knowing-god' ) );
		}

		if ( is_wp_error( $user ) ) {
            if ( $user->get_error_code() === 'expired_key' ) {
                $errors->add( 'expiredkey', esc_html__( 'Sorry, that key has expired. Please try again.', 'knowing-god' ) );
			} else {
                $errors->add( 'invalidkey', esc_html__( 'Sorry, that key does not appear to be valid.', 'knowing-god' ) );
			}
        }

		/**
		 * Fires before the password reset procedure is validated.
		 *
		 * @since 3.5.0
		 *
		 * @param object           $errors WP Error object.
		 * @param WP_User|WP_Error $user   WP_User object if the login and reset key match. WP_Error object otherwise.
		 */
		do_action( 'validate_password_reset', $errors, $user );

		if ( ( ! $errors->get_error_code() ) && isset( $pass1 ) && ! empty( $pass1 ) ) {
			reset_password( $user, $pass1 );

			$errors->add( 'password_reset', esc_html__( 'Your password has been reset.', 'knowing-god' ) );
		}

		// display error message
		if ( $errors->get_error_code() ) {
			if ( 'password_reset' === $errors->get_error_code() ) {
				echo '<div class="alert alert-success">';
			} else {
				echo '<div class="alert alert-danger">';
			}
			echo $errors->get_error_message( $errors->get_error_code() ) . '</div>';
		}
		die();
	}
endif;

if ( ! function_exists( 'knowing_god_remove_admin_bar' ) ) :

function knowing_god_remove_admin_bar() {
	if ( current_user_can('subscriber') ) {
	  show_admin_bar( false );
	}
}
add_action('admin_init', 'knowing_god_remove_admin_bar');
add_action('after_setup_theme', 'knowing_god_remove_admin_bar');
endif;

function knowing_god_get_label_singular() {
	return 'KG';
}

function knowing_god_insert_lmsuser( $data ) {
	/**
	 * @global wpdb  $wpdb  WordPress database abstraction object.
	 */
	global $wpdb;
	$user_args = array(
				'name' 			=> $data['user_login'],
				'first_name'      => isset( $data['user_first_name'] ) ? $data['user_first_name'] : '',
				'last_name'       => isset( $data['user_last_name'] )  ? $data['user_last_name']  : '',
				'email'      => isset( $data['user_email'] ) ? $data['user_email'] : '',
				'username'      => isset( $data['user_login'] ) ? $data['user_login'] : '',
				'password'       => isset( $data['user_password'] )  ? bcrypt( $data['user_password'] )  : '',
				'login_enabled'  => 1,
				'slug' => sanitize_title( $data['user_first_name'] . ' ' . $data['user_last_name'] ),
				'phone_code'		=> isset( $data['mobile_countrycode'] ) ? $data['mobile_countrycode'] : '',
				'phone'		=> isset( $data['mobile'] ) ? $data['mobile'] : '',
				'created_at'	=> date( 'Y-m-d H:i:s' ),
			);
	$wpdb->insert( 'users', $user_args );
	$user_id = $wpdb->insert_id;
}

function knowing_god_social_share() {
	include_once(KNOWING_GOD_PLUGIN_PATH . '/includes/pages/share-modal.php' );
}

function knowing_god_globe_icon() {
	include_once(KNOWING_GOD_PLUGIN_PATH . '/includes/pages/globe-confirm-modal.php' );
}

function knowing_god_quiz_icon() {
	include_once(KNOWING_GOD_PLUGIN_PATH . '/includes/pages/quiz-confirm-modal.php' );
}

function knowing_god_login_modal() {
	include_once(KNOWING_GOD_PLUGIN_PATH . '/includes/pages/login-modal.php' );
}

function knowing_god_login_modal_form() {
	include_once(KNOWING_GOD_PLUGIN_PATH . '/includes/pages/login-modal-form.php' );
}

function knowing_god_siteissue_modal() {
	include_once(KNOWING_GOD_PLUGIN_PATH . '/includes/pages/siteissue-modal.php' );
}

function knowing_god_generic_modal() {
	include_once(KNOWING_GOD_PLUGIN_PATH . '/includes/pages/generic-modal.php' );
}

function knowing_god_newsletter_modal() {
	include_once(KNOWING_GOD_PLUGIN_PATH . '/includes/pages/newsletter-modal.php' );
}

add_action('series_add_form_fields', 'knowing_god_add_series_form_fields',1);

function knowing_god_add_series_form_fields($taxonomy) {
	global $orgseries;	
	?>
	<div class="form-field">
		<label for="series_start_date">			
			<input id="series_start_date" type="text" name="series_start_date" class="datetimepicker" readonly />
			<p><?php _e('Select Series Start Date', 'organize-series') ?></p>
		</label>
	</div>
	
	<div class="form-field">
		<label for="series_author">			
			<?php wp_dropdown_users( array( 'role' => 'administrator' ) ); ?>
			<p><?php _e('Please select an author.', 'organize-series') ?></p>
		</label>
	</div>
	
	<div class="form-field">
		<label for="series_pathway">			
			<select name="series_pathway" id="series_pathway" class="">
				<option value="pathwaystart"><?php esc_html_e( 'PathwayStart', 'knowing-god' ); ?></option>
				<option value="pathwayforward"><?php esc_html_e( 'PathwayForward', 'knowing-god' ); ?></option>
				<option value="pathwayforever"><?php esc_html_e( 'PathwayForever', 'knowing-god' ); ?></option>
			</select>
			<p><?php _e('Please select pathway.', 'organize-series') ?></p>
		</label>
	</div>
	
	<div class="form-field">
		<div style="float:left;" id="selected-image-icon"></div>
		<div style="clear:left;"></div>
		<label for="series_icon">
			<input id="series_image_loc_display" type="text" style="width: 70%;" name="series_image_loc_display" value="" disabled="disabled" /><input style="float:right; width: 100px;" id="upload_series_image_button" type="button" value="Select Image" class="custom_image_button" data-field_name="series_image_loc"/>
			<input id="series_image_loc" type="hidden" name="series_image_loc" />
			<p><?php _e('Upload an featured image for the series.', 'organize-series') ?></p>
		</label>
	</div>
	<script>
	jQuery(document).ready( function( $ ) {
		$('.datetimepicker').datepicker({
			timepicker:false
		});
	});
	</script>
	<?php
}
function knowing_god_edit_series_form_fields($series, $taxonomy) {
	global $orgseries;
	$t_id = $series->term_id;
	$series_image_loc = get_term_meta( $t_id, 'series_image_loc', true );
	$series_author = get_term_meta( $t_id, 'series_author', true );
	
	$series_start_date = get_term_meta( $t_id, 'series_start_date', true );
	?>
	<tr valign="top">
		<th scope="row"><?php _e('Select series start date:', 'organize-series') ?></th>
		<td><label for="series_start_date">
			<input id="series_start_date" type="text" name="series_start_date" class="datetimepicker" value="<?php echo $series_start_date; ?>" readonly />
			<p><?php _e('Select Series Start Date', 'organize-series') ?></p>
			</label>
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><?php _e('Select an author:', 'organize-series') ?></th>
		<td><label for="series_image">
			<?php wp_dropdown_users( array( 'role' => 'administrator', 'selected' => $series_author ) ); ?>
			</label>
		</td>
	</tr>
	<?php
	$series_pathway = get_term_meta( $t_id, 'series_pathway', true );
	?>
	<tr valign="top">
		<th scope="row"><?php _e('Select pathway:', 'organize-series') ?></th>
		<td><label for="series_pathway">
			<select name="series_pathway" id="series_pathway" class="">
				<option value="pathwaystart" <?php if( $series_pathway == 'pathwaystart') echo 'selected'; ?>><?php esc_html_e( 'PathwayStart', 'knowing-god' ); ?></option>
				<option value="pathwayforward" <?php if( $series_pathway == 'pathwayforward') echo 'selected'; ?>><?php esc_html_e( 'PathwayForward', 'knowing-god' ); ?></option>
				<option value="pathwayforever" <?php if( $series_pathway == 'pathwayforever') echo 'selected'; ?>><?php esc_html_e( 'PathwayForever', 'knowing-god' ); ?></option>
			</select>
			</label>
		</td>
	</tr>	
	<tr valign="top">
		<?php if ( $series->term_id != '' ) { ?>
		<th scope="row"><?php _e('Current series image:', 'organize-series'); ?></th><?php } ?>
		<td>
			<?php if ($series_image_loc != '') {
					echo '<img src="'.$series_image_loc.'" alt="">';
				} else {
					echo '<p>'. __('No icon currently', 'organize-series') .'</p>';
				}
			 ?>
			<div id="selected-icon"></div>
		</td>
	</tr>
	<?php if ( $series_image_loc != '' ) { ?>
	<tr>
		<th></th>
		<td>
		<p style="width: 50%;"><input style="margin-top: 0px;" name="delete_image_2" id="delete_image_2" type="checkbox" value="true" />  <?php _e('Delete image? (note: there will not be an image associated with this series if you select this)', 'organize-series'); ?></p>
		</td>
	</tr>
	<?php } ?>
	<tr valign="top">
		<th scope="row"><?php _e('Series Image Upload:', 'organize-series') ?></th>
		<td><label for="series_image">
			<input id="series_image_loc_display" type="text" size="36" name="series_image_loc_display" value="" disabled="disabled"/>
			<input id="upload_series_image_button" type="button" value="Select Image" class="custom_image_button" data-field_name="series_image_loc"/>
			<p><?php _e('Upload an image for the series.', 'organize-series'); ?></p>
			<input id="series_image_loc" type="hidden" name="series_image_loc" />
			</label>
		</td>
	</tr>
	<script>
	jQuery(document).ready( function( $ ) {
		$('.datetimepicker').datepicker({
			timepicker:false
		});
	});
	</script>
	<?php
}
add_action('series_edit_form_fields','knowing_god_edit_series_form_fields',1,2);

add_action( 'edited_series', 'knowing_god_save_series_image', 10, 2 ); //edited_{$taxonomy} hook
add_action( 'created_series', 'knowing_god_save_series_image', 10, 2 );

function knowing_god_save_series_image( $term_id, $tt_id ) {
	if ( ! empty( $_POST['series_image_loc'] ) ) {
		$t_id = $term_id;
		update_term_meta( $term_id, 'series_image_loc', $_POST['series_image_loc'] );
	}
	
	if ( ! empty( $_POST['user'] ) ) {
		$t_id = $term_id;
		update_term_meta( $term_id, 'series_author', $_POST['user'] );
	}
	
	if ( ! empty( $_POST['series_pathway'] ) ) {
		$t_id = $term_id;
		update_term_meta( $term_id, 'series_pathway', $_POST['series_pathway'] );
	}
	
	if ( ! empty( $_POST['series_start_date'] ) ) {
		$t_id = $term_id;
		update_term_meta( $term_id, 'series_start_date', $_POST['series_start_date'] );
	}
	
	if ( ! empty( $_POST['delete_image_2'] ) ) {
		delete_term_meta( $term_id, 'series_image_loc' );
	}
}

function lmscontent_comments( $lesson_id )
{
	global $wpdb;
	return $wpdb->get_results( "SELECT * FROM lmscontents_comments
	WHERE content_id = " . $lesson_id );
}

add_action( 'wp_ajax_nopriv_ajaxlogin', 'knowing_god_ajax_login' );

function knowing_god_ajax_login(){
	global $wpdb;
    // First check the nonce, if it fails the function will break
    check_ajax_referer( 'ajax-login-nonce', 'security' );

    // Nonce is checked, get the POST data and sign user on
    $info = array();
    $info['user_login'] = $_POST['username'];
    $info['user_password'] = $_POST['password'];
    $info['remember'] = true;
	$redirect_back = $_POST['redirect_back'];
	
    $user_signon = wp_signon( $info, false );
    if ( is_wp_error($user_signon) ){
        echo json_encode(array('loggedin'=>false, 'message'=>__('Wrong username or password.')));
    } else {
        
		$user_data = get_user_by( 'login', $info['user_login'] );
		if ( ! $user_data ) {
			$user_data = get_user_by( 'email', $info['user_login'] );
		}
		
		if ( $user_data ) {
			$remember = true;
			$user_id = $user_data->ID;
			$user_login = $info['user_login'];			
			wp_set_auth_cookie( $user_id, $remember );
			wp_set_current_user( $user_id, $user_login );			
			do_action( 'wp_login', $user_login, get_userdata( $user_id ) );
		}
				
		// echo json_encode(array('loggedin'=>true, 'message'=>__('Login successful, redirecting...')));
		if ( is_user_logged_in() ) {						
			$wpdb->query( "UPDATE users SET is_wp_loggedin='yes' WHERE id = " . knowing_god_get_lms_user_id() );
			
			$user_details = (array) wp_get_current_user();
			if ( ! empty( $user_details ) ) {
				$data = $user_details['data'];
				$roles = $user_details['roles'];
				$username = $data->user_login;
				$email = $data->user_email;
				$user_id = $data->ID;
				
				$query = sprintf( "SELECT * FROM users WHERE ( username = '%s' OR email = '%s' )", $info['user_login'], $info['user_login'] );
				$records = $wpdb->get_results( $query );
				
				if ( empty( $records ) ) { // User Not found in LMS DB
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
						'confirmed' => '1',
						'status' => 'activated',
					);
					
					$wpdb->insert( 'users', $lms_user );
					$user_id = $wpdb->insert_id;
					if ( $user_id > 0 ) {
						$wpdb->insert( 'role_user', array(
							'user_id' => $user_id,
							'role_id' => $role_id,
						) );
						$redirect_to = knowing_god_urls( 'lms' ) . 'login/' . base64_encode( $user_id ) . '/' . base64_encode( $redirect_back );
						// wp_safe_redirect( $redirect_to );
						echo json_encode(array('loggedin'=>true, 'message'=>__('Login successful, redirecting...'), 'redirecturl' => $redirect_to));
					} else {
						echo json_encode( array( 'loggedin' => false, 'message' => esc_html__( 'Something went wrong. Unable to assign roles to LMS user.', 'knowing-god' ) ) );
					}
				} else { // User found in LMS DB
					$user_id = $records[0]->id;
					
					$wpdb->query( "UPDATE users SET is_wp_loggedin='yes' WHERE id = " . knowing_god_get_lms_user_id() );
					
					$redirect_to = knowing_god_urls( 'lms' ) . 'login/' . base64_encode( $user_id ) . '/' . base64_encode( $redirect_back );
					// wp_safe_redirect( $redirect_to );
					echo json_encode(array('loggedin'=>true, 'message'=>__('Login successful, redirecting...'), 'redirecturl' => $redirect_to));
				}
			} else {
				echo json_encode( array( 'loggedin' => false, 'message' => esc_html__( 'Something went wrong. User details not found.', 'knowing-god' ) ) );
			}
		} else {
			echo json_encode( array( 'loggedin' => false, 'message' => esc_html__( 'Something went wrong. Please contact administrator.', 'knowing-god' ) ) );
		}
    }
    die();
}

function knowing_god_get_current_url()
{
	return (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
}

// LMS tables
define('TBL_LMS_USERS', 'users');
define('TBL_LMS_LMSSERIES', 'lmsseries');
define('TBL_LMS_LMSCONTENTS', 'lmscontents');
define('TBL_LMS_LMSCONTENTS_TRACK', 'lmscontents_track');
define('TBL_LMS_USERS_MY_COURSES', 'users_my_courses');
define('TBL_LMS_USERS_COMPLETED_COURSES', 'users_completed_courses');
global $wpdb;
define('TBL_WP_POSTS', $wpdb->prefix . 'posts');

function is_lesson_completed( $lesson_id, $course_id, $module_id = '', $content_type = '', $type = 'content', $user_id = '' )
{
	global $wpdb;
	if ( is_user_logged_in() ) {
		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
			$lms_user_id = get_user_meta( $user_id, 'lms_user_id', TRUE);
			if ( ! empty( $lms_user_id ) ) {
				$user_id = $lms_user_id;
			} else {
				// If any case it dont find LMS user id in WP DB! Let us take it from LMS DB directly
				$user = wp_get_current_user();
				if ( $user ) {
					$lms_user = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ".TBL_LMS_USERS." WHERE username = %s ", $user->user_login ) );
					if ( ! empty( $lms_user ) ) {
						$user_id = $lms_user[0]->id;
					}
				}
				
			}
		}
		
		$details = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . TBL_LMS_LMSCONTENTS . " WHERE id = %d ", $lesson_id ) );
		
		if ( ! empty( $details ) ) {
			$details = $details[0];
		}
		
		$pieces_completed = TRUE;
		$pieces = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ".TBL_LMS_LMSCONTENTS." WHERE parent_id = %d ", $lesson_id ) );
		if ( ! empty( $pieces ) ) {
			foreach( $pieces as $piece ) {
				/*
				 * Even One part of the piece is not completed the piece is not completed.
				 * Terminology:
				 * Piece: is part of a lesson
				 * Part is : Parts in a lesson/Piece Eg: Video, Quiz, text
				 */
				if ( ! is_lesson_completed( $piece->id, $course_id, $module_id = '', $content_type = '', $type = 'content', $user_id = '' ) ) {
					$pieces_completed = FALSE;
				}
			}
		}
		
		if ( ! empty( $content_type ) ) {		
			$track_history = "SELECT * FROM lmscontents_track 
				INNER JOIN lmscontents ON lmscontents.id = lmscontents_track.content_id
				WHERE user_id = $user_id AND lmscontents_track.content_id = $lesson_id AND lmscontents_track.type = $content_type";
			if ( ! empty( $course_id ) ) {				
				// $track_history .= " AND lmscontents_track.course_id = $course_id";
			}
			if ( ! empty( $module_id ) ) {				
				// $track_history .= "lmscontents_track.module_id = $module_id";
			}
			$track_history = $wpdb->get_results( $track_history );
		} else {			
			$track_history = "SELECT * FROM lmscontents_track 
				INNER JOIN lmscontents ON lmscontents.id = lmscontents_track.content_id
				WHERE user_id = $user_id AND lmscontents_track.content_id = $lesson_id";
			if ( ! empty( $course_id ) ) {				
				// $track_history .= " AND lmscontents_track.course_id = $course_id";
			}
			if ( ! empty( $module_id ) ) {				
				// $track_history .= "lmscontents_track.module_id = $module_id";
			}
			// echo $track_history;
			$track_history = $wpdb->get_results( $track_history );
		}
		
		$video_completed = $text_completed = $quiz_completed = FALSE;
		if ( ! empty( $details ) ) {
			if ( ! empty( $track_history ) ) {
				foreach( $track_history as $history ) {
					// Video.
					if ( ! empty( $details->file_path_video ) ) {
						if ( $history->status == 'completed' && $history->type == 'video' ) {
							$video_completed = TRUE;
						}
					} else {
						$video_completed = TRUE;
					}
					
					// Description.
					if ( ! empty( $details->description ) ) {
						if ( $history->status == 'completed' && $history->type == 'text' ) {
							$text_completed = TRUE;
						}
					} else {
						$text_completed = TRUE;
					}
					
					// Quiz.
					if ( ! empty( $details->quiz_id ) ) {
						if ( $history->status == 'completed' && $history->type == 'quiz' ) {
							$quiz_completed = TRUE;
						}
					} else {
						$quiz_completed = TRUE;
					}
				}
			}
			
			if ( ! empty( $content_type ) ) {
				if ( $content_type == 'video' ) {
					return $video_completed;
				}
				if ( $content_type == 'text' ) {
					return $text_completed;
				}
				if ( $content_type == 'quiz' ) {
					return $quiz_completed;
				}
			} else {
				
				if ( $video_completed == TRUE && $text_completed == TRUE && $quiz_completed == TRUE && $pieces_completed == TRUE ) {
					return TRUE;
				} else {
					return FALSE;
				}
			}
		}
	} else {
		return FALSE;
	}
}

function knowing_god_get_course_details( $course_id )
{
	global $wpdb;
	return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM %s WHERE id = %d ", TBL_LMS_LMSSERIES, $course_id ) );
}

function knowing_god_get_my_courses( $user_id, $options = array() )
{
	global $wpdb;
	$course_id = 0;
	if ( ! empty( $options['course_id'] ) ) {
		$course_id = $options['course_id'];
	}
	if ( $course_id > 0 ) {
		return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ".TBL_LMS_USERS_MY_COURSES." WHERE content_type='postsseries' AND user_id = %d AND course_id = %d", $user_id, $course_id ) );
	} else {
		return FALSE;
	}
}

/**
 * $lesson_id : Post ID
 * $course_id : Series ID
 */
function knowing_god_is_post_completed( $lesson_id, $course_id, $module_id = '', $content_type = '', $type = 'content', $user_id = '' ) {
	global $wpdb;
	
	if ( empty( $user_id ) ) {
		// $user_id = get_current_user_id();
		$user_id = knowing_god_get_lms_user_id(); // We haver changed logic to save LMS user id while tracking the post.
	}
	$details = get_post_meta( $post_id );
	if ( ! empty( $content_type ) ) {
		$sql = 'SELECT * FROM %1$s INNER JOIN %2$s ON %2$s.id = %1$s.content_id WHERE user_id = ' . $user_id . ' AND %1$s.content_id = ' . $lesson_id . ' AND  %1s.type = "%3$s"';
		if ( ! empty( $course_id ) ) {
			$sql .= ' AND %1$s.course_id = ' . $course_id;
		}
		if ( ! empty( $module_id ) ) {
			$sql .= ' AND %1$s.module_id = ' . $module_id;
		}
		// echo $wpdb->prepare( $sql, TBL_LMS_LMSCONTENTS_TRACK, TBL_WP_POSTS, $content_type );
		$track_history = $wpdb->get_results( $wpdb->prepare( $sql, TBL_LMS_LMSCONTENTS_TRACK, TBL_WP_POSTS, $content_type ) );
	} else {
		$sql = 'SELECT * FROM %1$s INNER JOIN %2$s ON %2$s.id = %1$s.content_id WHERE user_id = ' . $user_id . ' AND %1$s.content_id = ' . $lesson_id;
		if ( ! empty( $course_id ) ) {
			$sql .= ' AND %1$s.course_id = ' . $course_id;
		}
		if ( ! empty( $module_id ) ) {
			$sql .= ' AND %1$s.module_id = ' . $module_id;
		}
		
		$track_history = $wpdb->get_results( $wpdb->prepare( $sql, TBL_LMS_LMSCONTENTS_TRACK, TBL_WP_POSTS ) );
	}
	// echo $wpdb->prepare( $sql, TBL_LMS_LMSCONTENTS_TRACK, TBL_WP_POSTS );
	$video_completed = $text_completed = $quiz_completed = FALSE;

	if ( ! empty( $track_history ) ) {
		foreach( $track_history as $history ) {
			// Video.
			$file_path_video = get_post_meta( $lesson_id, 'file_path_video', true );
			if ( ! empty( $file_path_video ) ) {
				if ( $history->status == 'completed' && $history->type == 'video' ) {
					$video_completed = TRUE;
				}
			} else {
				$video_completed = TRUE;
			}
			
			// Description.
			$description = get_the_content( $lesson_id );
			if ( ! empty( $description ) ) {
				if ( $history->status == 'completed' && $history->type == 'text' ) {
					$text_completed = TRUE;
				}
			} else {
				$text_completed = TRUE;
			}
			
			// Quiz.
			$quiz_id = get_post_meta( $lesson_id, 'quiz_id', true );
			if ( ! empty( $quiz_id ) ) {
				if ( $history->status == 'completed' && $history->type == 'quiz' ) {
					$quiz_completed = TRUE;
				}
			} else {
				$quiz_completed = TRUE;
			}
		}
		
		if ( ! empty( $content_type ) ) {
			if ( $content_type == 'video' ) {
				return $video_completed;
			}
			if ( $content_type == 'text' ) {
				return $text_completed;
			}
			if ( $content_type == 'quiz' ) {
				return $quiz_completed;
			}
		} else {
			if ( $video_completed == TRUE && $text_completed == TRUE && $quiz_completed == TRUE ) {
				return TRUE;
			} else {
				return FALSE;
			}
		}
	} else {
		return FALSE;
	}	
}

function knowing_god_get_series_id() {
	$serarray = get_the_series();
	$series_id = 0;
	if ( ! empty( $serarray ) ) {
		$series = $serarray[0];
		$series_id = $series->term_id;
	}
	return $series_id;
}

function knowing_god_is_series_completed( $series_id ) {
	$posts_completed = $total_posts = 0;
	// $series_id = 0;
	// $series_id = knowing_god_get_series_id();
	
	if ( ! empty( $series_id ) ) {
		$posts = knowing_god_get_series_posts( $series_id );
		
		$total_posts = count( $posts );
		if ( ! empty( $posts ) ) {
			foreach( $posts as $post ) {
				if ( knowing_god_is_post_completed( $post['id'], $series_id ) ) {
					$posts_completed++;
				}
			}
		}
	}
	return $posts_completed === $total_posts;
}

function knowing_god_get_series_posts( $ser_ID = array(), $referral = false, $display = false, $serieswidg_title = false ) {
	
 	global $post, $orgseries;
	if ( is_single() )
		$cur_id = $post->ID; //to get the id of the current post being displayed.
	else
		$cur_id = -1;

	if ( !is_single() && ( !isset($ser_ID) ) )
		return false;

	if (!empty($ser_ID) ) $ser_ID = is_array($ser_ID) ? $ser_ID : array($ser_ID);

	if ( !isset($ser_ID) || empty($ser_ID) ) {
		$serarray = get_the_series();
		if (!empty($serarray) ) {
			foreach ($serarray as $series) {
				$ser_ID[] = $series->term_id;
			}
		}
	}

	$series_post = array();
	$posts_in_series = array();
	$settings = $orgseries->settings;
	$result = '';
	foreach ( $ser_ID as $ser ) {
		$series_post = get_objects_in_term($ser, 'series');
		$is_unpub_template = TRUE;
		$is_unpub_template = apply_filters('unpublished_post_template', $is_unpub_template);

		$posts_in_series = get_series_order($series_post, 0, $ser, FALSE, $is_unpub_template);
	}
	return $posts_in_series;
}

function post_groups( $post_id, $user_id = '' )
{
	global $wpdb;
	if ( empty( $user_id ) && is_user_logged_in() ) {
		$user_id = knowing_god_get_lms_user_id();
	}
	$query = 'SELECT lg.* FROM lmsgroups AS lg 
	INNER JOIN lmsgroups_contents AS lgc ON lgc.group_id = lg.id
	INNER JOIN ' . TBL_WP_POSTS . ' ON ' . TBL_WP_POSTS . '.ID = lgc.content_id
	WHERE 
	lgc.content_type = "post" 
	AND lg.group_status = "active" 
	AND '.TBL_WP_POSTS.'.post_status = "publish"
	AND lgc.content_id = ' . $post_id;
	return $wpdb->get_results( $query );
}

function course_groups( $course_id, $user_id = '' )
{
	global $wpdb;
	if ( empty( $user_id ) && is_user_logged_in() ) {
		$user_id = knowing_god_get_lms_user_id();
	}
	$query = 'SELECT lg.* FROM lmsgroups AS lg 
	INNER JOIN lmsgroups_contents AS lgc ON lgc.group_id = lg.id
	INNER JOIN ' . TBL_LMS_LMSSERIES . ' ON ' . TBL_LMS_LMSSERIES . '.id = lgc.content_id
	WHERE 
	lgc.content_type = "course"
	AND lg.group_status = "active" 
	AND '.TBL_LMS_LMSSERIES.'.status = "active"
	AND lgc.content_id = ' . $post_id;
	return $wpdb->get_results( $query );
}

function is_course_completed_new( $course_id )
{
	global $wpdb;
	// Course Modules
	$modules = $wpdb->get_results( $wpdb->prepare( 'SELECT lmsseries.* FROM lmsseries 
	INNER JOIN quizcategories AS qc ON qc.id = lmsseries.lms_category_id
	WHERE lmsseries.status = "active" AND qc.category_status = "active" AND lmsseries.parent_id = %d', $course_id  ) );
	
	// Course Lessons
	$lessons = $wpdb->get_results( $wpdb->prepare( 'SELECT lmscontents.* FROM lmsseries_data 
	INNER JOIN lmscontents ON lmscontents.id = lmscontent_id
	WHERE lmsseries_id = %d', $course_id  ) );
	$modules_completed = $lessons_completed = 0;
	if ( count( $modules ) > 0 ) {
		foreach( $modules as $module ) {
			if ( is_module_completed( $module->id ) ) {
				$modules_completed++;
			}
		}
	}

	if ( count( $lessons ) > 0 ) {
		foreach( $lessons as $lesson ) {
			if ( is_lesson_completed( $lesson->id, $course_id  ) ) {
				$lessons_completed++;
			}
		}
	}
	if ( $modules_completed == count( $modules ) && $lessons_completed == count( $lessons ) ) {
		return TRUE;
	} else {
		return FALSE;
	}
}

function is_module_completed( $module_id, $content_id = '' )
{
	global $wpdb;
	$lessons = App\LmsSeries::getAllModuleLessons( $course_id );
	$lessons = DB::table('lmsseries_data')
				->select( 'lmscontents.*', 'module.id AS module_id', 'module.title AS module_title', 'module.sub_title AS module_sub_title',  'course.id AS course_id', 'course.title AS course_title', 'lmscontents.created_at AS post_date' )
				->join('lmscontents', 'lmscontents.id', '=', 'lmscontent_id')
				->join( 'lmsseries as module', 'module.id', '=', 'lmsseries_data.lmsseries_id' )
				->join( 'lmsseries as course', 'course.id', '=', 'module.parent_id' )
				->where('lmscontents.lesson_status', '=', 'active' )
				->where('lmsseries_id', '=', $module_id )
				->where('lmscontents.id', '=', $content_id )
				->get();
	$lessons = $wpdb->get_results( $wpdb->prepare( 'SELECT lmscontents.*, module.id AS module_id, module.title AS module_title, module.sub_title AS module_sub_title, course.id AS course_id, course.title AS course_title, lmscontents.created_at AS post_date INNER JOIN lmscontents ON lmscontents.id = lmscontent_id INNER JOIN lmsseries as module' ) );
	// echo '<pre>'; print_r ( $lessons );
	if ( $lessons->count() > 0 ) {
		$total_contents = $lessons->count();
		$completed = 0;
		foreach( $lessons as $lesson ) {
			if ( is_lesson_completed( $lesson->id, $lesson->course_id, $lesson->module_id  ) ) {
				$completed++;
			}
		}
		if ( $total_contents == $completed ) {
			return TRUE;
		} else {
			return FALSE;
		}
	} else {
		return TRUE;
	}
}
