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
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'wordpress');

/** MySQL database password */
define('DB_PASSWORD', 'dbpassword');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'gW9e3e_qZOx{Rj.ds4:^DCNn~Klppr:v{avtV(98lk^U1e@_|[]PS#0hy*?>u>|h');
define('SECURE_AUTH_KEY',  'g!9%OP+~51$ZuG:aQIuXq#IvZ],:-74zw|+gdG$>Rf6OM/J`P-XK+zOUq{1lPNG#');
define('LOGGED_IN_KEY',    'DX|{Ltmcbh|8`ig`-<U-L%+$Hte+xh+Cda~|Z|0=o-~MZaU<NLyZKioWfk^>0^,R');
define('NONCE_KEY',        'nkBZmcSN^AJie+GUg)HPH*B#9!jT`F_io]y|QSTZw$dba)_z@0k%.Lehv?+1r^g^');
define('AUTH_SALT',        ' sOb{FMeqizGcK*H-#=h.vKWjd_4 M)QF48X9qcy}&hY`DQcV 8n$kU^74SD:t*z');
define('SECURE_AUTH_SALT', 'QMnEg?e0$wxMnYLp[5a%XkOYFTaTM?}6t4H=J;I)~gEy$#{j;s RCa:{6U|^S,|T');
define('LOGGED_IN_SALT',   'PSj>|- jTHHj,+hNWlgKdi`<7 Lm/@:F+mtEA_@@)Sz;NO@gL[]:}ge00%fdo(D+');
define('NONCE_SALT',       'F0LJLNc{tT]6 BSI-g[,Fz^(%-U#=2%$@|q0(BEaVJ_Se-2;14+amg|/Wb`6IQ{c');

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
