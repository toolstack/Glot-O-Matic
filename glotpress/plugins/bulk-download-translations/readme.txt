Bulk Download Translations is a plugin for GlotPress that will download all the translation sets (in PO format) of a project in a zip file at once.

This plugin requires that the PHP ZipArchive Class exists in your PHP installation.

To enable this plugin, add the following line to your gp-config.php file:

	define( 'GP_BULK_DOWNLOAD_TRANSLATIONS', true );
	
By default PO files will be exported, you can set which formats will be exported by adding the following line to your gp-config.php file (only include those lines for the formats you wish to export):

	define( 'GP_BULK_DOWNLOAD_TRANSLATIONS_FORMAT_PO', true );
	define( 'GP_BULK_DOWNLOAD_TRANSLATIONS_FORMAT_MO', true );
	define( 'GP_BULK_DOWNLOAD_TRANSLATIONS_FORMAT_ANDROID', true );
	define( 'GP_BULK_DOWNLOAD_TRANSLATIONS_FORMAT_RESX', true );
	define( 'GP_BULK_DOWNLOAD_TRANSLATIONS_FORMAT_STRINGS', true );
	
	Note: If you have custom formats defined you can use 'GP_BULK_DOWNLOAD_TRANSLATIONS_FORMAT_[SLUG]' to include them (SLUG must be in upper case and any dashes replaced with underscores).
	
You can also define the temporary directory to use by adding the following line to your gp-config.php file:

	define( 'GP_BULK_DOWNLOAD_TRANSLATIONS_TEMP_DIR', 'c:/temp' );
	
	Note: Do not include a trailing slash.