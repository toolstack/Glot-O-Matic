Force SSL is a plugin for GlotPress that will redirect any non-SSL requests to https.

To enable this plugin, add the following lines to your gp-config.php file:

	define( 'GP_SSL', true );
	define( 'GP_FORCE_SSL', true );

You may also need to add the following lines depending on your hosting provider.  If your
hosting provider places the SSL certificates on a proxy server and then connects to the web
server via http only AND they support the HTTP_X_FORWARDED_PROTO header then add the following
lines.  If you are connecting on a non-standard port, change it below.

	if (array_key_exists( 'HTTP_X_FORWARDED_PROTO', $_SERVER ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
		$_SERVER['HTTPS']='on';
		$_SERVER['SERVER_PORT']='443';
	}
