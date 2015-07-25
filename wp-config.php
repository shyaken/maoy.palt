<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'admin_masterapk');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         '|Jo7h37hwy$W/|fCK5YW^=-3~ U(M2-YCdegy~^XxTLP(LD6i[0GgZdOwAB-b&tZ');
define('SECURE_AUTH_KEY',  'jg+-W/WDjj2-3Q%:q~=Fp*R+LO/2,#q0;Y2XV*JYW]V9vk.V[XmJ-erX&/3wNAx+');
define('LOGGED_IN_KEY',    '*`i Rp4/!L/N0Bex(vdV9}jB,~ndd) 2H{ZrZNU-R5++ju(mQ|GIXZ|.e5@{M/=R');
define('NONCE_KEY',        '0=-Tq~D(rR}55#c?X1~ /{^]ZaKO$,udg+|t+$MS22n{7zqM!AInmIcX@8gnDfa|');
define('AUTH_SALT',        '-]=}.DWqK-g;Yrr>h[nY*yj)j$m3ztdN:F=0O#?7+tX44wZpq+^!2|7:O~-,.A5:');
define('SECURE_AUTH_SALT', '-jsTJDSR|q}ZI{YP[7&~6LYOaM$h F-+ej$a!m9z!GdqWfbaOmtG<4d~$`-HBjz3');
define('LOGGED_IN_SALT',   '{C{Ug-!`RamYT(El`>Whgvqg:u2p9w)=E.^WQJ)h60pBSY|Gq|9rv%#)naQP07gY');
define('NONCE_SALT',       'iIy -Jjf~MWUK,:MMg@k3$x*[IxWT},_Y6Waj%L^4}4Q* A<tu&6(o[}obOY?N`=');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
