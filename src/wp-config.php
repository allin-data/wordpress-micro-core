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

// ** MySQL settings ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** MySQL database username */
define( 'DB_USER', 'wordpress' );

/** MySQL database password */
define( 'DB_PASSWORD', 'wordpress' );

/** MySQL hostname */
define( 'DB_HOST', 'db:3306' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', '6a(6fjk:@/+8/y-1|i2mV]*:b7(#k82:2+|6WlE)m*(-Cbhd8R1#N;KXGz)8r1]:');
define('SECURE_AUTH_KEY', 'KempE:3S4t-75h!iVwL;2:Uq7GPZ(Vy|6hpKr3KXpSJ;[[b1#A7v5gKyC/01G8D:');
define('LOGGED_IN_KEY', 'P::i5!h_3g#iP)8vo0lt7m2eM|H9j-9)x!*5d:@WRSF1-~_Y2aLODY2~b(||A2z8');
define('NONCE_KEY', 'V)G&cC#dh;2#Xh7K47A*joy_]Tw0z(|;b|o40+2Bk33PM#t02Iz8!%+s#SBh(k28');
define('AUTH_SALT', 'fL4]d3@22%F:bTAa33/I[8Ia22hi25Lm17(E94&[//ID!9Sm9fG;978Z_O0wj+o[');
define('SECURE_AUTH_SALT', 'uQoPDIKDu8Gspr-tf3]mpN0Csa79hH|66jK%VC74R8y182!V6@]|*||qks*!I048');
define('LOGGED_IN_SALT', '!5%M0-kW52Y:f88%46PGtp4Q~u&q);yJau61cpM)d5;5veEO040/9V8|sd8v5Wn2');
define('NONCE_SALT', '6s2YBtA6l&[ov@S409JZYJpnuF;NN4)mVE(8F-95LwJ0))]X;+XEIyv]c3WA1eA_');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

$hostname = 'mahucms.loc';
define('DISABLE_WP_CRON', false);
define('WP_ALLOW_MULTISITE', true);
define('WPLANG', 'de_DE');
define('WP_DEBUG', false);
define('WP_DEBUG_JS', false);
define('WP_HOME', sprintf('http://%s/', $hostname));
define('WP_SITEURL', sprintf('http://%s/', $hostname));
define('DOMAIN_CURRENT_SITE', $hostname);

/* That's all, stop editing! Happy blogging. */

if ( defined( 'WP_CLI' ) ) {
  $_SERVER['HTTP_HOST'] = '127.0.0.1';
}

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

// force the links to the login, register, and forgot password pages to show up as 'https'
define('FORCE_SSL_ADMIN', false);
#if ($_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
#    $_SERVER['HTTPS']='on';

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

define('FS_METHOD', 'direct');

define('WP_TEMP_DIR', ABSPATH . 'tmp/');
