<?php
	if( is_admin() ) {
		GLOBAL $wpdb;
		
		// Store the new version information.
		update_option('gom_plugin_version', GOM_VERSION);

		if( $GOM_Installed == false ) {
			// Setup the primary values for the options.
			$gom_utils->update_option('glotpress_path', plugin_dir_url( __FILE__ ) . 'glotpress' );
			$gom_utils->update_option('GPDB_CHARSET', 'utf8' );
			$gom_utils->update_option('GPDB_COLLATE', '' );
			$gom_utils->update_option('GP_LANG', '' );
			$gom_utils->update_option('gp_table_prefix', 'gom_' );
			$gom_utils->update_option('GP_GOOGLE_TRANSLATE', false );
			$gom_utils->update_option('GP_USE_SLUG_FOR_DOWNLOADS', true );
			$gom_utils->update_option('GP_REMOVE_PROJECTS_FROM_BREADCRUMS', false );
			$gom_utils->update_option('GP_REMOVE_PROJECTS_FROM_BREADCRUMS_LOGO_URL', '' );
			$gom_utils->update_option('GP_WORDPRESS_SINGLE_SIGN_ON', true );
			$gom_utils->update_option('GP_NEW_WINDOW_FOR_EXTERNAL_LINKS', true );
			$gom_utils->update_option('GP_REMOVE_POWERED_BY', true );
			$gom_utils->update_option('GP_BULK_DOWNLOAD_TRANSLATIONS', true );
			$gom_utils->update_option('GP_BULK_DOWNLOAD_TRANSLATIONS_FORMAT_PO', false );
			$gom_utils->update_option('GP_BULK_DOWNLOAD_TRANSLATIONS_FORMAT_MO', false );
			$gom_utils->update_option('GP_BULK_DOWNLOAD_TRANSLATIONS_FORMAT_ANDROID', false );
			$gom_utils->update_option('GP_BULK_DOWNLOAD_TRANSLATIONS_FORMAT_RESX', false );
			$gom_utils->update_option('GP_BULK_DOWNLOAD_TRANSLATIONS_FORMAT_STRINGS', false );
			
			// Get the schema for the database.
			$schema = gom_config_schema_array( $gom_utils->get_option('gp_table_prefix') );

			// Create each of the tables.
			foreach( $schema as $table ) {
				$wpdb->query( $table . " DEFAULT CHARACTER SET 'utf8'" );
			}

			// avoid duplicating permissions just in case.
			if( $wpdb->get_var( "SELECT count(id) FROM gom_permissions" ) == 0 ) {
			
				// By default, assign all WordPress admins as GlotPress admins.
				$admins = get_users("role=administrator");
				
				foreach( $admins as $admin ) {
					$sqlstring = $wpdb->prepare( 'INSERT INTO ' . $gom_utils->get_option('gp_table_prefix') . 'permissions (user_id, action) VALUES ( %d, %s);', $admin->ID, 'admin' );
					$wpdb->query( $sqlstring );
				}
			}
			
		} else {

			// If this is an upgrade, we need to do anything.
			
		}

		// Write out the config file (will also write out the .htaccess/web.config as required).
		gom_write_gp_config_file();
	}
?>