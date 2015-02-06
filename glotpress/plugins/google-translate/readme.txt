Google translate is a pay service, you must acquire an API key from Google and setup a payment option with them.

Login to your Google developers console (http://console.developer.google.com) and select "APIs & auth"->Credentials.  Then create a new "Public API access" Key.  Use this key as below to configure access.

The Google translate plugin is enabled for all users by adding the following line to the end of your gp-config.php:

	define( 'GP_GOOGLE_TRANSLATE_KEY', 'YOUR-API-KEY-HERE' );

Alternatively you may assign an API key on a per user basis with the following lines:

	define( 'GP_GOOGLE_TRANSLATE', true );
	define( 'GP_GOOGLE_TRANSLATE_KEY_[USERNAME1]', 'YOUR-API-KEY-HERE' );
	define( 'GP_GOOGLE_TRANSLATE_KEY_[USERNAME2]', 'YOUR-SECOND-API-KEY-HERE' );
	
Where [USERNAME] is the user name you wish to have use the assigned API KEY.

The above two formats can be combined to provide one API key for general users and specific ones for others as follows:

	define( 'GP_GOOGLE_TRANSLATE', true );
	define( 'GP_GOOGLE_TRANSLATE_KEY', 'YOUR-API-KEY-HERE' );
	define( 'GP_GOOGLE_TRANSLATE_KEY_[USERNAME1]', 'YOUR-API-KEY-HERE' );

In the above case, any user other than USERNAME1 will receive the first API key, where as USERNAME1 will receive the second.
