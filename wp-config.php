<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'addweb' );

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
define( 'AUTH_KEY',         ';U`Vy+H/^i~YA*XGW_$,lo=(!657f.8;Mb5:S^$1XR7Y+51Bd>ZJX5zf&J:]Bz0F' );
define( 'SECURE_AUTH_KEY',  '(lX `<Duxvnc$995=BZC.<DP1,b5Q:r;M&-fP`IaOanUSgMLM)<n;2ed*d2S!8~>' );
define( 'LOGGED_IN_KEY',    'k92 U&G?.t35USg2l;USgq:_+S^~jP A!!EYy67GJNAv6!b^2BKC#yv5oUr>4%p0' );
define( 'NONCE_KEY',        'u,BI6&`mK+)^a1#MeLA1K58*bH=N;+c[@?n.Xf_k ^JWJ]!g 2g;E*}(4CdejX<L' );
define( 'AUTH_SALT',        '-C|;8h]d>ORtE:8KaukZ+MOHn1+[Ur2splXy3L3)CNpYu{4a4D+IWXS=yI#``V-H' );
define( 'SECURE_AUTH_SALT', 'T*U.df]E oc_SIxQZYLv$W:@;~NEYLFFClq?[rlweZrvB>RAyjhlh}7|(p<DO`fL' );
define( 'LOGGED_IN_SALT',   'Vi`8v*Z_):lhWJ;|)|$J_r{y]1Kh^B!!Z+S;7li=EeR~{6lL)U7z.?97lMLg9AZB' );
define( 'NONCE_SALT',       'DNy-A&iZs+3^4QOe>cTYDgjlBOf7!bu+Qqwzr$^0d(pKX24.:vehZ8U:`{FNFgS[' );

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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
