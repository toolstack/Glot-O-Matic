<?php
/**
 * Sets the mod_rewrite rules
 *
 * @return bool Returns true on success and false on failure
 */
function gp_set_htaccess( $path ) {
	// The server doesn't support mod rewrite
	if ( ! apache_mod_loaded( 'mod_rewrite', true ) ) {
		//return false;
	}

	if ( file_exists( '.htaccess' ) && ! is_writeable( '.htaccess' ) ) {
		return false;
	}

	// check if the .htaccess is in place or try to write it
	$htaccess_file = @fopen( '.htaccess', 'c+' );

	//error opening htaccess, inform user!
	if ( false === $htaccess_file ) {
		return false;
	}

	//'# BEGIN GlotPress' not found, write the access rules
	if ( false === strpos( stream_get_contents( $htaccess_file ), '# BEGIN GlotPress' ) ) {
		fwrite( $htaccess_file, gp_mod_rewrite_rules( $path ) );
	}

	fclose( $htaccess_file );

	return true;
}

/**
 * Sets the IIS rewrite rules
 *
 * @return bool Returns true on success and false on failure
 */
function gp_set_webconfig( $path ) {
	if ( file_exists( 'web.config' ) ) {
		return false;
	}

	// Try and create the web.config file.
	$webconfig_file = @fopen( 'web.config', 'w' );

	// error opening htaccess, inform user!
	if ( false === $webconfig_file ) {
		return false;
	}

	// Write the rules out to the web.config file
	fwrite( $webconfig_file, gp_mod_rewrite_rules($path) );

	fclose( $webconfig_file );

	return true;
}

/**
 * Return the mod_rewrite rewrite rules
 *
 * @return string Rewrite rules
 */
function gp_mod_rewrite_rules( $path ) {
//	$path = gp_add_slash( gp_url_path( guess_uri() ) );
	
	return '
# BEGIN GlotPress
	<IfModule mod_rewrite.c>
	RewriteEngine On
	RewriteBase ' . dirname( $path ) . '
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteRule . ' . $path . 'index.php [L]
	</IfModule>
# END GlotPress';
}

/**
 * Return the IIS rewrite rules
 *
 * @return string Rewrite rules
 */
function gp_iis_rewrite_rules( $path ) {
//	$path = gp_add_slash( gp_url_path( guess_uri() ) );

	return '
<configuration>
    <system.webServer>
		<rewrite>
		  <rules>
			<rule name="GlotPress Rewrite Rule" stopProcessing="true">
			  <match url="." ignoreCase="false" />
			  <conditions>
				<!--# BEGIN GlotPress-->
				<add input="{REQUEST_FILENAME}" matchType="IsFile" ignoreCase="false" negate="true" />
				<add input="{REQUEST_FILENAME}" matchType="IsDirectory" ignoreCase="false" negate="true" />
			  </conditions>
			  <action type="Rewrite" url="' . $path . 'index.php" />
			</rule>
		  </rules>
		</rewrite>
    </system.webServer>
</configuration>';
}
?>