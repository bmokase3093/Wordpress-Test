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


define('WP_HOME','http://3.90.252.142');
define('WP_SITEURL','http://3.90.252.142');
define('RELOCATE',true)
/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'dsbisux5omkjyxpr8ioap3wncgohibyuqi3wgxrbywi4gdx5dl9g1v3gzef0s5ac' );
define( 'SECURE_AUTH_KEY',  'x7sj6mwtfyglberm7vq12tntwss75coy4rqiua6diyly35jnjipsmqlied8ghwxy' );
define( 'LOGGED_IN_KEY',    'tno4nc30jxihrx4f110h11atcxatzxbejcogdvlbaruyjnuwbyq4lxcsbxy2c8jh' );
define( 'NONCE_KEY',        'h1zkd75puosen3x2yxwihngd8d9fidh58e9fnj22pgf3ylprr6ht7uf5mztg0gor' );
define( 'AUTH_SALT',        'lzw6chw5hpwjeoenxgex7biimy00c0hzd7fmc4tfxgztyeznupjvkp6kaw2du6wb' );
define( 'SECURE_AUTH_SALT', 'ztgsguagwf1how1vfo4zjeuzn6cg3kesu9095jzdd4c9yt4wxkdonl3xtsib7dxk' );
define( 'LOGGED_IN_SALT',   'ouc2le375vzw24hitn5h4blme8wh06bs2hzxs0ulibq65q4mgcqkgezanqk2hy6s' );
define( 'NONCE_SALT',       'qkqu20rgab6qffgg3iev75v1gcsdwvloqhobqorkexwxew8anqclcozkxxa8ja86' );

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