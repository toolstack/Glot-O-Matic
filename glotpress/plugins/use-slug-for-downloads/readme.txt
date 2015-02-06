By default GlotPress uses <project path>-<locale>.<ext> as the format for exports, however this is often not the format that is useful for real world usage. 
 
This plugin allows you to instead use <translation slug>.<ext> as the default export file name if a translation slug exists and isn't "default".  Since the translation slug is configurable on a per translation set basis, this allows you to manage your export names easily. 

To enable this plugin, add the following line to your gp-config.php file: 
 
    define( 'GP_USE_SLUG_FOR_DOWNLOADS', true ); 