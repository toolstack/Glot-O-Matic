Last Update is a plugin for GlotPress that will display the last update date/time in the translation set list.

To enable this plugin, add the following lines to your gp-config.php file:

	define( 'GP_LAST_UPDATE', true );
	
In addition, you can set the following defines:

	define( 'GP_LAST_UPDATE_FORMAT', 'M j Y @ g:i a' );			
		See PHP's date() function for format.
		
	define( 'GP_LAST_UPDATE_REQUIRED_PERMISSION', 'approve' );
		Permission required to see the last update/time: read, approve, admin
