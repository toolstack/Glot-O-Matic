By default GlotPress displays the top level "Projects" page in the breadcrums at the top of the page, if you only have a single project this can be redundent for all by the administrators.

This plugin allows you to remove the "Projects" from the breadcrums (though you can still access it thorugh the url).  It also let's you add your project to the URL of the GlotPress image at the top of the page.

To enable this plugin, add the following lines to your gp-config.php file:

	define( 'GP_REMOVE_PROJECTS_FROM_BREADCRUMS', true );
	define( 'GP_REMOVE_PROJECTS_FROM_BREADCRUMS_LOGO_URL', 'projects/YOUR-PROJECT-NAME-HERE' );