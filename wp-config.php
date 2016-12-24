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
define('DB_NAME', 'auri_wordpress');

/** MySQL database username */
define('DB_USER', 'auri_wordpress');

/** MySQL database password */
define('DB_PASSWORD', 'qwxnw9GfwMsQYsDE');

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
define('AUTH_KEY',         'U62X<V2#D!HWMS4CATM:-ee%ZU([1GEv5;?0Hq#=lt+8/WCv6@R`z`Td7u<nJVxR');
define('SECURE_AUTH_KEY',  ' OD|W.8Q3V-CZl=,-T^a291jn0x&>Wthbqg2p1o)d^n$;*#t.3oqV>kL.?irP8k^');
define('LOGGED_IN_KEY',    '-UFLhS!vBcu=%6c7K{3q[,Ni_hD%x~q2;A7%4A:ebON?hff _3{Tqk/$5R{cM-w?');
define('NONCE_KEY',        'OmJ&TLl~H&YZnq @skP@1g5}:V3yxx`*T$*4RSpDSD5v707tBq5w&Y| ]U8l{c}Q');
define('AUTH_SALT',        'eF:x?U~R%#z>ET3+ufG)#2A}95ivc,8R9*^YR`}jZvEM&DrMS`w&/}jBBbI4R[M`');
define('SECURE_AUTH_SALT', 'H/(l2>A31a7I-uK_vrMnYP}fhi?Tta$r4Tu%]R3Y$j&gp2p3?g>rMC])E_ST6x&Z');
define('LOGGED_IN_SALT',   't&n/W.iSF.|LY<*=KL-Ppg?g%vuUFk@lAaS~Jj[U{GI8{gBP=eTm7YO2jI_(Lx-t');
define('NONCE_SALT',       '$,Q)~ D7xt`Vu|8/IUJ[e}Mu_jezW-,C&O~x!lJN5m1A}+#;.e?L}jdT40xCIQDE');

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
define('WP_ALLOW_REPAIR', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
