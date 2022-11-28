<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'tbrxgqtrgy');

/** MySQL database username */
define('DB_USER', 'tbrxgqtrgy');

/** MySQL database password */
define('DB_PASSWORD', '65u6tUgPrr');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'cr?lt5r_RcrzTc<EjDvP R=v[[[n`@SS9bfo.vU~I=s)-B#,fq9r9I$MQdHHe;(9');
define('SECURE_AUTH_KEY',  'hcoga}<,;Cu$iuu2 H8Q7!ITP>Gx95d* c>)AxFlF9]Sxb}=Pg@ui~*EF`<Rw Dp');
define('LOGGED_IN_KEY',    '=c69QK52R?n-;Ae-b}Y7&O XbUrrKk g w3X((9cPIO>_lGAh0`0la u kRA 9W:');
define('NONCE_KEY',        ',*Hlxnu0`+c%(J)VTdhfDi@D}LHb4+@B~xv+:HCtYZ3S(/`AY>{!115``]YpfLfe');
define('AUTH_SALT',        '#:^B?Bw]k&:0yxi@lS?|XmQ5c%e:z?7Wx] 6!oavTW6{*Ut<~My<Nuz)W_q}Fci:');
define('SECURE_AUTH_SALT', 'Yx|Bs9TWZSs(I%~k=s nj=mqSZGV,|<82u?D2HJT@~d)##SZ>pQ;U0@;8#>M.%z.');
define('LOGGED_IN_SALT',   'NdxA30q18JPJT{aU!fD}$%>1R><VjlHQ<nsu&%eh*WEoLZzQJS!lWNC(o[:yrlTm');
define('NONCE_SALT',       '!6U{t&[v8?RUY(|kI&y?Y3>A=DD|RE<EFHhkMI@_`F_|Le=8F<Kb)XGB r;R399-');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

define('FS_METHOD', 'direct');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
