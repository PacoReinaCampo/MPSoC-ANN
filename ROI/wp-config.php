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
define('DB_NAME', "queevnju_nwedb");

/** MySQL database username */
define('DB_USER', "queevnju_nwedb");

/** MySQL database password */
define('DB_PASSWORD', "c0qyMyw6R81m");

/** MySQL hostname */
define('DB_HOST', "localhost");

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

define('AUTH_KEY',		'PFirnd8Zna*&Of*5uJ17JGHufgehC6xu8mVoXm!gj1dAi2Qq(*B5C1Nsz^A0l$zu');
define('SECURE_AUTH_KEY',	'4mLp7IEM(O9Mz^L&x*6K6PYU!pp6JCkKyeXWuNhk!3aZW@wvd3GsckB53AUoI7i!');
define('LOGGED_IN_KEY',		'US@c4trwcEhw*d7Gak27Y(b4hs^&7Ba5LGZO6d7QX$SHsIfRELFbh8@i8UIZK$O#');
define('NONCE_KEY',		'WJbts2C#WcnNnOh(BAfCundcBJL0J9YcE4*qFYjQVc1WsAvD0d*anr5(Ws(SB5ke');
define('AUTH_SALT',		'pgSrrp@&B(og*x0$DXyA38C8Re%8A8G@LVf5JAQNxm4PZU@$h3Uy&Urbk8p$(UZx');
define('SECURE_AUTH_SALT',	'Yg*BICDgL5k6J@FsuMEoQpjI4C2tmrKhjk9kWay&W6dXFBI^VgpZxFw42Bi3mQG4');
define('LOGGED_IN_SALT',	'tTX5VNMEI#TGs3W5bN@GL8A3yjHpZ9o$iKueulmMxRQjvHaVxZm!9x@EgQ(iiK#7');
define('NONCE_SALT',		'KV!g!K*Gb5YHVhtQA2R%tNqwwa1OI#i$7qVW82lZcfru@@AUXNNxz(XtnT@PaauI');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

define('WPLANG', 'es_ES');

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

