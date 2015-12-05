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
define('DB_NAME', 'WYBC');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

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
define('AUTH_KEY',         '%s-<LGvdrr+{I%U;]Or{LY+|uNZd]3c2@d6{U5iyF!&8$hZfOD9K0x_8$%e~(/-_');
define('SECURE_AUTH_KEY',  '7wu_ZloyKd?,7|LU5mrz+`g0*|P347G;35O@H+g[v4AWBq]AhoIQ`|pKQ3R]P]#i');
define('LOGGED_IN_KEY',    'FAs}8-P;[|V!;vN3i/xhB63>>0[j7r,&Dz+rD]^?Shs)K+z ahhZp`Jjnai?NFj|');
define('NONCE_KEY',        'gr[ j-jMR.l Dm,BLG#+7)P,,zmL& |lC#no[6*]o.n+7/P+T3kl<M!L$+e|9L7@');
define('AUTH_SALT',        '~lw1Q.J_y+g5i{AUHs|6!G$hQIgI<1FDZh?dlS[kUjT-bI}SN|X#+s[ ?l$f-*Vl');
define('SECURE_AUTH_SALT', '+qJ)L[B!;L#S#Bvs}K}+iGyi`7l.-)@Tp,2/t,:M<_B}-00UPf3Nl`4%f-~Y7PMT');
define('LOGGED_IN_SALT',   '3m]UNkaYZ*Bt&PQ#mh{d6WA/6h=-1$jRoj^Ythyc[G2rV=$OxbegRdLnfu^03%xt');
define('NONCE_SALT',       '|{8mY$e9=muSeOJdW+2 .K(Y8hm30|sK+JS=~H#fSUCaGF&JGzBPYf(.Pf;{J}%T');

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

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
