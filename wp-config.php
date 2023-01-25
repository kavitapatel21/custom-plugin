<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'pr' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         ']=E-w9o=I3cUg*_HI@6-L;H:jBWp>MNOelM}6b,C+SD[yC xMX/WaQB 6}h f6jd' );
define( 'SECURE_AUTH_KEY',  'i8|[h31Y6N0-HmdX-r$6mD+^1UAUEu#<n2bF&h0z8CJsmw;}>%8~O;7YMxD0M/kb' );
define( 'LOGGED_IN_KEY',    'O{nySZM} .hlgqhUUol$)a&ISP#67|<I6|G$zJVvxRwmL);rlqV;+JQG~Cgid?r?' );
define( 'NONCE_KEY',        '?*]7%/DoNo**mz@VA[ZFPHFw?>&s>h%Q2 Zyjj#MO**~_(Xgy~DX5Ls`)nl!}q19' );
define( 'AUTH_SALT',        'bm%/6 1 L. u,r};;mc<8z#Uz P ISQ&le#_t&jEU^,impT?OYPt,+8u,gn4$@MD' );
define( 'SECURE_AUTH_SALT', '5/Jg;UO#j_V/;,f/oR^;7^jr[W9dCfgyWyEf9Tz3^h!}Iu7Z3E_fyl}xF>Z[R!M:' );
define( 'LOGGED_IN_SALT',   'in&$DEuyN5&`ef5/@0!x8cF<=O!FLhg%&CXukv0T`0)S2]WL%;c w3Hl]X%7k;{V' );
define( 'NONCE_SALT',       '7F<J(}6|6` ^feV.gv=DpIUscv@l+IMnHPaO0W<;.CW9C0|z@3>&eTwW0+@ H$cy' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
//define( 'WP_DEBUG', false );


/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
