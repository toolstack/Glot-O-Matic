<?php
/**
 *	The base configuration of the GlotPress
 *
 *	This file has the following configurations: MySQL settings, table prefix,
 *	secret keys, GlotPress language and integration with WordPress user system.
 *	You can get the MySQL settings from your web host.
 *
 */

/** The name of the database for GlotPress */
define('GPDB_NAME', 'glotpress');

/** MySQL database username */
define('GPDB_USER', 'username');

/** MySQL database password */
define('GPDB_PASSWORD', 'password');

/** MySQL hostname */
define('GPDB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('GPDB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('GPDB_COLLATE', '');

/**#@+
 * Authentication Unique Keys.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/ WordPress.org secret-key service}
 *
 * @since 0.1
 */
define('GP_AUTH_KEY', 'put your unique phrase here');
define('GP_SECURE_AUTH_KEY', 'put your unique phrase here');
define('GP_LOGGED_IN_KEY', 'put your unique phrase here');
define('GP_NONCE_KEY', 'put your unique phrase here');
/**#@-*/

/**
 * GlotPress Localized Language, defaults to English.
 *
 * Change this to localize GlotPress. A corresponding MO file for the chosen
 * language must be installed to languages/. For example, install
 * fr_FR.mo to languages/ and set GP_LANG to 'fr_FR' to enable French
 * language support.
 */
define('GP_LANG', '');

/**
 * Custom users and usermeta tables for integration with WordPress user system.
 * 
 * You might want to delete your current permissions, since they will point to different
 * users in the custom table. You can use `php scripts/wipe-permissions.php` for that.
 * 
 * If you start with fresh permissions, you can add admins via `php scripts/add-admin.php`
 */
// define('CUSTOM_USER_TABLE', 'wp_users');
// define('CUSTOM_USER_META_TABLE', 'wp_usermeta');

/**
 * GlotPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$gp_table_prefix = 'gp_';

/**
 * Google Translate Plugin
 * 
 * Enable the Google Translate plugin by uncommenting the following define and
 * adding your Google API key.
 * 
 * For more options, see plugins/google-translate/readme.txt
 */
//define('GP_GOOGLE_TRANSLATE', 'YOUR-API-KEY-HERE');

/**
 * Use Slug for Downloads Plugin
 * 
 * Enable the Use Slug for Downloads plugin by uncommenting the following define.
 * 
 * For more options, see plugins/use-slug-for-downloads/readme.txt
 */
//define('GP_USE_SLUG_FOR_DOWNLOADS', true);

/**
 * Remove Projects from Breadcrumbs Plugin
 * 
 * Enable the Remove Projects from Breadcrumbs plugin by uncommenting the following define.
 * If you wish the GlotPress logo to take you directly to a spcific project, then uncomment
 * the second define and add your own URL.
 * 
 * For more options, see plugins/remove-projects-from-breadcrumbs/readme.txt
 */
//define('GP_REMOVE_PROJECTS_FROM_BREADCRUMS', true);
//define('GP_REMOVE_PROJECTS_FROM_BREADCRUMS_LOGO_URL', 'projects/test/');

/**
 * WordPress Single Sign On Plugin
 * 
 * For more details, see plugins/wordpress-single-sign-on/readme.txt
 */
//define('GP_WORDPRESS_SINGLE_SIGN_ON', true);
//define('AUTH_CLASS', 'WP_Auth_V2');
//define('AUTH_CLASS_FILE', './plugins/wordpress-single-sign-on/class.wp-auth.v2.php');
//define('GP_WORDPRESS_HASH', md5( 'YOUR-WP-PATH-HERE' ) );
//define('GP_AUTH_COOKIE', 'wordpress_' . GP_WORDPRESS_HASH);
//define('GP_SECURE_AUTH_COOKIE', 'wordpress_sec_auth_' . GP_WORDPRESS_HASH);
//define('GP_LOGGED_IN_COOKIE', 'wordpress_logged_in_' . GP_WORDPRESS_HASH);
//define('GP_AUTH_SALT',        'YOUR-SALT-HERE');
//define('GP_SECURE_AUTH_SALT', 'YOUR-SALT-HERE');
//define('GP_LOGGED_IN_SALT',   'YOUR-SALT-HERE');
//define('GP_NONCE_SALT',       'YOUR-SALT-HERE');

/**
 * New Window for External Links Plugin
 * 
 * Enable the New Window for External Links plugin by uncommenting the following define.
 * 
 * For more options, see plugins/google-translate/readme.txt
 */
//define( 'GP_NEW_WINDOW_FOR_EXTERNAL_LINKS', true );

/**
 * Remove Powered By Plugin
 * 
 * Enable the Remove Powered By plugin by uncommenting the following define.
 * 
 * For more options, see plugins/remove-powered-by/readme.txt
 */
//define( 'GP_REMOVE_POWERED_BY', true );

/**
 * Bulk Download Translations Plugin
 * 
 * Enable the Bulk Download Translations plugin by uncommenting the first define below.
 * To include more than just the PO files in the export, uncomment the appropriate define below.
 * 
 * Note: If uncomment more than the first define and want the PO files in the export you must
 *       uncomment the second define.
 * 
 * For more options, see the readme in plugins/google-translate/readme.txt
 */
//define( 'GP_BULK_DOWNLOAD_TRANSLATIONS', true );
//define( 'GP_BULK_DOWNLOAD_TRANSLATIONS_FORMAT_PO', true );
//define( 'GP_BULK_DOWNLOAD_TRANSLATIONS_FORMAT_MO', true );
//define( 'GP_BULK_DOWNLOAD_TRANSLATIONS_FORMAT_ANDROID', true );
//define( 'GP_BULK_DOWNLOAD_TRANSLATIONS_FORMAT_RESX', true );
//define( 'GP_BULK_DOWNLOAD_TRANSLATIONS_FORMAT_STRINGS', true );
//define( 'GP_BULK_DOWNLOAD_TRANSLATIONS_TEMP_DIR', 'c:/temp' );
