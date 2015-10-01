This plugin provides single sign on functionality with WordPress.  This plugin requires several things:

	- GlotPress must be installed in a directory under WordPress
	- GlotPress must be configured to use the same private auth keys/salts
	- GlotPress must be installed in the same database as WordPress
	
Installation steps:

	1) Install GlotPress in a subdirectory of your WordPress install (like gp or glotpress).
	
	2) Copy the following lines from your wp-config.php file to your gp-config.php, 

		define('AUTH_KEY',         'put your unique phrase here');
		define('SECURE_AUTH_KEY',  'put your unique phrase here');
		define('LOGGED_IN_KEY',    'put your unique phrase here');
		define('NONCE_KEY',        'put your unique phrase here');
		define('AUTH_SALT',        'put your unique phrase here');
		define('SECURE_AUTH_SALT', 'put your unique phrase here');
		define('LOGGED_IN_SALT',   'put your unique phrase here');
		define('NONCE_SALT',       'put your unique phrase here');

	3) Add "GP_" to the front of each define above like so:
	
		define('GP_AUTH_KEY',         'put your unique phrase here');
		define('GP_SECURE_AUTH_KEY',  'put your unique phrase here');
		define('GP_LOGGED_IN_KEY',    'put your unique phrase here');
		define('GP_NONCE_KEY',        'put your unique phrase here');
		define('GP_AUTH_SALT',        'put your unique phrase here');
		define('GP_SECURE_AUTH_SALT', 'put your unique phrase here');
		define('GP_LOGGED_IN_SALT',   'put your unique phrase here');
		define('GP_NONCE_SALT',       'put your unique phrase here');

	4) Delete the duplicates in the gp-config.php file of the first four lines.
	
	5) Uncomment the following two lines in gp-config.php:
	
		define('CUSTOM_USER_TABLE', 'wp_users');
		define('CUSTOM_USER_META_TABLE', 'wp_usermeta');
		
	6) Add the following line, replacing the url with your WordPress site's url, above the GlotPress:
	
		define('GP_WORDPRESS_HASH', md5( 'http://localhost/wordpress' ) );

	7) Next add the following lines below the line in step 6:
	
		define('GP_AUTH_COOKIE', 'wordpress_' . GP_WORDPRESS_HASH);
		define('GP_SECURE_AUTH_COOKIE', 'wordpress_sec_auth_' . GP_WORDPRESS_HASH);
		define('GP_LOGGED_IN_COOKIE', 'wordpress_logged_in_' . GP_WORDPRESS_HASH);

	8) Finally, add these lines to gp-config.php:
	
		define( 'GP_WORDPRESS_SINGLE_SIGN_ON', true );
		define( 'AUTH_CLASS', 'WP_Auth_V2' );
		define( 'AUTH_CLASS_FILE', './plugins/wordpress-single-sign-on/class.wp-auth.v2.php' );
		define( 'GP_LOGIN_PATH', '/wp-login.php' );
		
		The GP_LOGIN_PATH should be the full link to your WordPress login page, for example /wordpress/wp-login.php, 
		if you've installed WordPress in the wordpress directory.

	9) Logon to your WordPress site and then to to your GlotPress URL, you should be logged in automatically!
	
Notes:

	- Once active, the login/out links are removed from GlotPress, you must use WordPress to login and out.
	- If you manually go to the GlotPress logout URL, nothing will happen and you will be returned to the GlotPress root page.
