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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'fundanis_wp16' );

/** MySQL database username */
define( 'DB_USER', 'wordpress' );

/** MySQL database password */
define( 'DB_PASSWORD', 'password' );

/** MySQL hostname */
define( 'DB_HOST', 'wordpress2.cq8nkfqv6vcv.us-east-1.rds.amazonaws.com' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/** JWT Authentication for WP REST API. Don't change this!*/
        define('JWT_AUTH_SECRET_KEY', 'uInz6984AzdHWkgzglkKbB6EcqOfhx8CkVmlbFnAvvYP7L6QaMWSUkBiiQBy');


// define('WP_HOME','http://3.90.252.142');
// define('WP_SITEURL','http://3.90.252.142');
// define('RELOCATE',true)
/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'V61+ry,Kexy-Srg|WpV^R.Kdn&)uvZV@PIwbP=aexb.vH*Q+w=2t6pq1IYl,q!{.');
define('SECURE_AUTH_KEY',  'c 6o5R+wR=$uECznqu&9dYh->FWozHxJ5-<m5k~tHhQ)RzLQT2  :!$l0lc2?k|)');
define('LOGGED_IN_KEY',    '$[`=dM79O$p*GOhXej:@>;i~i_3@=n/vs3|nO{[<-:jaRg,$Rz8|k~~W..WID4Z1');
define('NONCE_KEY',        '+?jr- j>OYe2}v%pufa)E&-N.( -|s5):MMEhS7L-.D.KK<-iV+c`.LH+NFQZM%o');
define('AUTH_SALT',        'Z@Mf{KK2B*;2GyVAeh$%-&Gp9,P`-%Xh+lDsj]GHc)6AL~/2ujNx}#5a--&<oJ-N');
define('SECURE_AUTH_SALT', '^>RO+M_cH}-|#Hm+Db(+v|J>~Y0X++69.wD;}>Sz?v%oOKK?x;LAe+4-lH6QvL~O');
define('LOGGED_IN_SALT',   '51j;sTiP/M`K)^cDi!C9XL~B0;8mQ6hnS1K-8`#9wJF-}cN[l`F=)3?ru#scGFr9');
define('NONCE_SALT',       'n[*v/ ?my-h i:Feg5$0!;<@_ U:`<z:]8=sS1k31.ucU#[Hgp:H>b;yt|1!f_D(');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wpay_';

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
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';