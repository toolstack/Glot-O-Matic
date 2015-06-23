<?php
	function gom_user_options_array() {
		GLOBAL $wpdb;
		
		$admin_users = $wpdb->get_results("SELECT user_id FROM gom_permissions WHERE action = 'admin'");
		$users = gom_get_user_list();

		foreach( $users as $user ) {
			$users_array_by_id[$user->ID] = $user;
		}
		
		$ret = array();
		
		$ret['Download File Name']								= array( 'type' => 'title' );
		$ret['GP_USE_SLUG_FOR_DOWNLOADS'] 						= array( 'type' => 'bool', 'desc' => 'Use translation set slug for download file name' );

		$ret['Bulk Translation Download']						= array( 'type' => 'title' );
		$ret['Bulk Desc']										= array( 'type' => 'desc', 'desc' => 'If no formats are selected, PO files will be included by default.');
		$ret['GP_BULK_DOWNLOAD_TRANSLATIONS'] 					= array( 'type' => 'bool', 'desc' => 'Enable bulk download of translation sets' );
		$ret['GP_BULK_DOWNLOAD_TRANSLATIONS_FORMAT_PO'] 		= array( 'type' => 'bool', 'desc' => 'Include PO files in bulk downloads' );
		$ret['GP_BULK_DOWNLOAD_TRANSLATIONS_FORMAT_MO'] 		= array( 'type' => 'bool', 'desc' => 'Include MO files in bulk downloads' );
		$ret['GP_BULK_DOWNLOAD_TRANSLATIONS_FORMAT_ANDROID'] 	= array( 'type' => 'bool', 'desc' => 'Include Android XML files in bulk downloads' );
		$ret['GP_BULK_DOWNLOAD_TRANSLATIONS_FORMAT_RESX'] 		= array( 'type' => 'bool', 'desc' => 'Include .NET files in bulk downloads' );
		$ret['GP_BULK_DOWNLOAD_TRANSLATIONS_FORMAT_STRINGS'] 	= array( 'type' => 'bool', 'desc' => 'Include Mac/iOS String files in bulk downloads' );

		$ret['Project Breadcrumbs']								= array( 'type' => 'title' );
		$ret['GP_REMOVE_PROJECTS_FROM_BREADCRUMS'] 				= array( 'type' => 'bool', 'desc' => 'Remove "Projects" from the breadcrumbs at the top of GlotPress' );
		$ret['GP_REMOVE_PROJECTS_FROM_BREADCRUMS_LOGO_URL'] 	= array( 'type' => 'image', 'desc' => 'Append the following to the URL of the GlotPress logo link' );

		$ret['External Links']									= array( 'type' => 'title' );
		$ret['GP_NEW_WINDOW_FOR_EXTERNAL_LINKS'] 				= array( 'type' => 'bool', 'desc' => 'Open external links in new windows/tabs' );

		$ret['Google Translate']								= array( 'type' => 'title' );
		$ret['Google Desc']										= array( 'type' => 'desc', 'desc' => 'You may enter a global key, per user keys or both.');
		$ret['GP_GOOGLE_TRANSLATE_KEY'] 						= array( 'type' => 'text', 'desc' => 'Global Google API Key(leave blank to disable)', 'size' => 20, 'height' => 1 );
		$ret['Google Desc 2']									= array( 'type' => 'desc', 'desc' => 'If the global key is enabled the follow setting is not required.');
		$ret['GP_GOOGLE_TRANSLATE']		 						= array( 'type' => 'bool', 'desc' => 'Enable per user API Keys' );
		
		foreach( $admin_users as $user ) {
			if( !is_object( $users_array_by_id[$user->user_id] ) ) { continue; }
			$name = strtoupper( $users_array_by_id[$user->user_id]->user_login );
			$ret['GP_GOOGLE_TRANSLATE_KEY_' . $name]		 	= array( 'type' => 'text', 'desc' => 'Google API key for ' . $users_array_by_id[$user->user_id]->user_login, 'size' => 20, 'height' => 1 );
		}
		
		$ret['Footer']											= array( 'type' => 'title' );
		$ret['GP_REMOVE_POWERED_BY'] 							= array( 'type' => 'bool', 'desc' => 'Remove "...Powered By..." from the footer' );

		$ret['SSL']												= array( 'type' => 'title' );
		$ret['GP_SSL'] 											= array( 'type' => 'bool', 'desc' => 'Enable SSL in GlotPress' );
		$ret['GP_FORCE_SSL']									= array( 'type' => 'bool', 'desc' => 'Force SSL in GlotPress' );
		$ret['HTTP_X_FORWARDED_PROTO Desc']						= array( 'type' => 'desc', 'desc' => 'Some hosting providers place the SSL certificate on a proxy server in front of the web server, this will use the HTTP_X_FORWARDED_PROTO header to detect when an SSL connection is being made from the client.');
		$ret['HTTP_X_FORWARDED_PROTO']							= array( 'type' => 'bool', 'desc' => 'Support HTTP_X_FORWARDED_PROTO' );
		
		$ret['Advanced']										= array( 'type' => 'title' );
		$ret['Path Desc']										= array( 'type' => 'desc', 'desc' => 'This is the fully qualified path to glotpress, you can change it to specify a starting location.');
		$ret['glotpress_path']									= array( 'type' => 'text', 'desc' => 'Use this url when opening GlotPress', 'size' => 40, 'height' => 1 );
		$ret['Advanced Desc']									= array( 'type' => 'desc', 'desc' => 'The following will be directly appended to the gp-config.php file.');
		$ret['advanced_gpconfig']								= array( 'type' => 'text', 'desc' => 'gp-config.php lines', 'size' => 80, 'height' => 5 );
					
		return $ret;
	}

	function gom_config_settings_array() {
		GLOBAL $wpdb;
		
		$admin_users = $wpdb->get_results("SELECT user_id FROM gom_permissions WHERE action = 'admin'");
		$users = gom_get_user_list();

		foreach( $users as $user ) {
			$users_array_by_id[$user->ID] = $user;
		}
		
		$ret = array();
		
		$ret['GPDB_NAME'] 										= 'constant';
		$ret['GPDB_USER'] 										= 'constant';
		$ret['GPDB_PASSWORD'] 									= 'constant';
		$ret['GPDB_HOST'] 										= 'constant';
		$ret['GPDB_CHARSET'] 									= 'define';
		$ret['GPDB_COLLATE'] 									= 'define';
		$ret['GP_AUTH_KEY'] 									= 'constant';
		$ret['GP_SECURE_AUTH_KEY'] 								= 'constant';
		$ret['GP_LOGGED_IN_KEY'] 								= 'constant';
		$ret['GP_NONCE_KEY'] 									= 'constant';
		$ret['GP_LANG'] 										= 'define';
		$ret['CUSTOM_USER_TABLE'] 								= 'constant';
		$ret['CUSTOM_USER_META_TABLE'] 							= 'constant';
		$ret['gp_table_prefix'] 								= 'variable';
		$ret['GP_GOOGLE_TRANSLATE_KEY']							= 'define';
		$ret['GP_GOOGLE_TRANSLATE'] 							= 'define-bool';

		foreach( $admin_users as $user ) {
			$name = strtoupper( $users_array_by_id[$user->user_id]->user_login );
			$ret['GP_GOOGLE_TRANSLATE_KEY_' . $name]		 	= 'define';
		}
		
		$ret['GP_USE_SLUG_FOR_DOWNLOADS'] 						= 'define-bool';
		$ret['GP_REMOVE_PROJECTS_FROM_BREADCRUMS'] 				= 'define-bool';
		$ret['GP_REMOVE_PROJECTS_FROM_BREADCRUMS_LOGO_URL'] 	= 'define';
		$ret['GP_WORDPRESS_SINGLE_SIGN_ON']						= 'define-bool';
		$ret['AUTH_CLASS'] 										= 'constant';
		$ret['AUTH_CLASS_FILE'] 								= 'constant';
		$ret['GP_WORDPRESS_HASH'] 								= 'constant';
		$ret['GP_AUTH_COOKIE'] 									= 'constant';
		$ret['GP_SECURE_AUTH_COOKIE']	 						= 'constant';
		$ret['GP_LOGGED_IN_COOKIE'] 							= 'constant';
		$ret['GP_AUTH_SALT'] 									= 'constant';
		$ret['GP_SECURE_AUTH_SALT'] 							= 'constant';
		$ret['GP_LOGGED_IN_SALT'] 								= 'constant';
		$ret['GP_NONCE_SALT'] 									= 'constant';
		$ret['GP_NEW_WINDOW_FOR_EXTERNAL_LINKS'] 				= 'define-bool';
		$ret['GP_REMOVE_POWERED_BY'] 							= 'define-bool';
		$ret['GP_BULK_DOWNLOAD_TRANSLATIONS'] 					= 'define-bool';
		$ret['GP_BULK_DOWNLOAD_TRANSLATIONS_FORMAT_PO'] 		= 'define-bool';
		$ret['GP_BULK_DOWNLOAD_TRANSLATIONS_FORMAT_MO'] 		= 'define-bool';
		$ret['GP_BULK_DOWNLOAD_TRANSLATIONS_FORMAT_ANDROID'] 	= 'define-bool';
		$ret['GP_BULK_DOWNLOAD_TRANSLATIONS_FORMAT_RESX'] 		= 'define-bool';
		$ret['GP_BULK_DOWNLOAD_TRANSLATIONS_FORMAT_STRINGS'] 	= 'define-bool';
		$ret['GP_BULK_DOWNLOAD_TRANSLATIONS_TEMP_DIR'] 			= 'constant';
		$ret['GP_SSL']											= 'define-bool';
		$ret['GP_FORCE_SSL']									= 'define-bool';
		$ret['HTTP_X_FORWARDED_PROTO']							= 'code-bool';
		$ret['advanced_gpconfig']								= 'text';
		
		return $ret;
	}
	
	function gom_config_constants_array() {
		GLOBAL $wpdb;
		$table_prefix = $wpdb->prefix;
		
		$hash = md5( get_bloginfo('url') );
		
		return array(	'GPDB_NAME' 									=> DB_NAME,
						'GPDB_USER' 									=> DB_USER,
						'GPDB_PASSWORD' 								=> DB_PASSWORD,
						'GPDB_HOST' 									=> DB_HOST,
						'GP_AUTH_KEY' 									=> AUTH_KEY,
						'GP_SECURE_AUTH_KEY' 							=> SECURE_AUTH_KEY,
						'GP_LOGGED_IN_KEY' 								=> LOGGED_IN_KEY,
						'GP_NONCE_KEY' 									=> NONCE_KEY,
						'CUSTOM_USER_TABLE' 							=> $table_prefix . 'users',
						'CUSTOM_USER_META_TABLE' 						=> $table_prefix . 'usermeta',
						'AUTH_CLASS' 									=> 'WP_Auth_V2',
						'AUTH_CLASS_FILE' 								=> dirname( __FILE__ ) . '/glotpress/plugins/wordpress-single-sign-on/class.wp-auth.v2.php',
						'GP_WORDPRESS_HASH' 							=> $hash,
						'GP_AUTH_COOKIE' 								=> 'wordpress_' . $hash,
						'GP_SECURE_AUTH_COOKIE' 						=> 'wordpress_sec_auth_' . $hash,
						'GP_LOGGED_IN_COOKIE' 							=> 'wordpress_logged_in_' . $hash,
						'GP_AUTH_SALT' 									=> defined( 'AUTH_SALT' ) ? AUTH_SALT : str_replace( AUTH_KEY, '', wp_salt( 'auth' ) ),
						'GP_SECURE_AUTH_SALT' 							=> defined( 'SECURE_AUTH_SALT' ) ? SECURE_AUTH_SALT : str_replace( SECURE_AUTH_KEY, '', wp_salt( 'secure_auth' ) ),
						'GP_LOGGED_IN_SALT' 							=> defined( 'LOGGED_IN_SALT' ) ? LOGGED_IN_SALT : str_replace( LOGGED_IN_KEY, '', wp_salt( 'logged_in' ) ),
						'GP_NONCE_SALT' 								=> defined( 'NONCE_SALT' ) ? NONCE_SALT : str_replace( NONCE_KEY, '', wp_salt( 'nonce' ) ),						
						'GP_BULK_DOWNLOAD_TRANSLATIONS_TEMP_DIR' 		=> get_temp_dir(),
						'HTTP_X_FORWARDED_PROTO'						=> "if (array_key_exists( 'HTTP_X_FORWARDED_PROTO', \$_SERVER ) && \$_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {\n	\$_SERVER['HTTPS']='on';\n	\$_SERVER['SERVER_PORT']='443';\n}",
					);
	}
	
	function gom_config_schema_array( $table_prefix ) {
		$gp_schema = array();

		/*
		Translations
		 - There are fields to take all the plural forms (no known language has more than 4 plural forms)
		 - Belongs to an original string
		 - Belongs to a user
		 - Status can be: new, approved, unaproved, current, spam or whatever you'd like
		*/
		$gp_schema['translations'] = "CREATE TABLE IF NOT EXISTS `{$table_prefix}translations` (
			`id` INT(10) NOT NULL auto_increment,
			`original_id` INT(10) DEFAULT NULL,
			`translation_set_id` INT(10) DEFAULT NULL,
			`translation_0` TEXT NOT NULL,
			`translation_1` TEXT DEFAULT NULL,
			`translation_2` TEXT DEFAULT NULL,
			`translation_3` TEXT DEFAULT NULL,
			`translation_4` TEXT DEFAULT NULL,
			`translation_5` TEXT DEFAULT NULL,
			`user_id` INT(10) DEFAULT NULL,
			`status` VARCHAR(20) NOT NULL default 'waiting',
			`date_added` DATETIME DEFAULT NULL,
			`date_modified` DATETIME DEFAULT NULL,
			`warnings` TEXT DEFAULT NULL,
			PRIMARY KEY (`id`),
			KEY `original_id` (`original_id`),
			KEY `user_id` (`user_id`),
			KEY `translation_set_id` (`translation_set_id`),
			KEY `translation_set_id_status` (`translation_set_id`,`status`),
			KEY `date_added` (`date_added`),
			KEY `warnings` (`warnings` (1))
		)";

		/*
		Translations sets: a translation set holds specific translation of a project in a specific language
		For example each WordPress Spanish translation (formal, informal and that of Diego) will be different sets.
		Most projects will have only one translation set per language.
		*/
		$gp_schema['translation_sets'] = "CREATE TABLE IF NOT EXISTS `{$table_prefix}translation_sets` (
			`id` INT(10) NOT NULL auto_increment,
			`name` VARCHAR(255) NOT NULL,
			`slug` VARCHAR(255) NOT NULL,
			`project_id` INT(10) DEFAULT NULL,
			`locale` VARCHAR(10) DEFAULT NULL,
			PRIMARY KEY (`id`),
			UNIQUE KEY `project_id_slug_locale` (`project_id`, `slug`, `locale`),
			KEY `locale_slug` (`locale`, `slug`)
		)";

		/*
		Original strings
		 - Has many translations
		 - Belongs to a project
		 */
		$gp_schema['originals'] = "CREATE TABLE IF NOT EXISTS `{$table_prefix}originals` (
			`id` INT(10) NOT NULL auto_increment,
			`project_id` INT(10) DEFAULT NULL,
			`context` VARCHAR(255) DEFAULT NULL,
			`singular` TEXT NOT NULL,
			`plural` TEXT DEFAULT NULL,
			`references` TEXT DEFAULT NULL,
			`comment` TEXT DEFAULT NULL,
			`status` VARCHAR(255) NOT NULL DEFAULT '+active',
			`priority` TINYINT NOT NULL DEFAULT 0,
			`date_added` DATETIME DEFAULT NULL,
			PRIMARY KEY (`id`),
			KEY `project_id_status` (`project_id`, `status`),
			KEY `singular_plural_context` (`singular`(83), `plural`(83), `context`(83))
		)";

		/*
		Glossary Entries
		*/
		$gp_schema['glossary_entries'] = "CREATE TABLE IF NOT EXISTS `{$table_prefix}glossary_entries` (
			`id` INT(10) unsigned NOT NULL auto_increment,
			`glossary_id` INT(10) unsigned NOT NULL,
			`term` VARCHAR(255) NOT NULL,
			`part_of_speech` VARCHAR(255) DEFAULT NULL,
			`comment` TEXT DEFAULT NULL,
			`translation` VARCHAR(255) DEFAULT NULL,
			`date_modified` DATETIME NOT NULL,
			`last_edited_by` BIGINT(20) NOT NULL,
			PRIMARY KEY (`id`)
		)";

		/*
		Glossaries
		*/
		$gp_schema['glossaries'] = "CREATE TABLE IF NOT EXISTS `{$table_prefix}glossaries` (
			`id` INT(10) unsigned NOT NULL auto_increment,
			`translation_set_id` INT(10)  NOT NULL,
			`description` TEXT DEFAULT NULL,
			PRIMARY KEY (`id`)
		)";

		/*
		Projects
		- Has a project -- its parent
		- The path is the combination of the slugs of all its parents, separated by /
		*/
		$gp_schema['projects'] = "CREATE TABLE IF NOT EXISTS `{$table_prefix}projects` (
			`id` INT(10) NOT NULL auto_increment,
			`name` VARCHAR(255) NOT NULL,
			`slug` VARCHAR(255) NOT NULL,
			`path` VARCHAR(255) NOT NULL,
			`description` TEXT NOT NULL,
			`parent_project_id` INT(10) DEFAULT NULL,
			`source_url_template` VARCHAR(255) DEFAULT '',
			`active` TINYINT DEFAULT 0,
			PRIMARY KEY (`id`),
			KEY `path` (`path`),
			KEY `parent_project_id` (`parent_project_id`)
		)";

		/*
		Users
		 - Has many translations
		 - 'user_login', 'user_nicename' and 'user_registered' indices are inconsistent with WordPress
		*/
		/*
		// We don't need the users or usermeta tables
		$gp_schema['users'] = "CREATE TABLE IF NOT EXISTS `{$table_prefix}users` (
			`ID` bigINT(20) unsigned NOT NULL auto_increment,
			`user_login` varchar(60) NOT NULL default '',
			`user_pass` varchar(64) NOT NULL default '',
			`user_nicename` varchar(50) NOT NULL default '',
			`user_email` varchar(100) NOT NULL default '',
			`user_url` varchar(100) NOT NULL default '',
			`user_registered` datetime NOT NULL default '0000-00-00 00:00:00',
			`user_status` INT(11) NOT NULL default 0,
			`display_name` varchar(250) NOT NULL default '',
			PRIMARY KEY (`ID`),
			UNIQUE KEY `user_login` (`user_login`),
			UNIQUE KEY `user_nicename` (`user_nicename`),
			KEY `user_registered` (`user_registered`)
		)";
		*/
		/*
		if ( defined('CUSTOM_USER_TABLE') && {$table_prefix}users == CUSTOM_USER_TABLE ) unset( $gp_schema['users'] );

		// usermeta
		$gp_schema['usermeta'] = "CREATE TABLE IF NOT EXISTS `{$table_prefix}usermeta` (
			`umeta_id` bigINT(20) NOT NULL auto_increment,
			`user_id` bigINT(20) NOT NULL default 0,
			`meta_key` varchar(255) NOT NULL,
			`meta_value` longTEXT NOT NULL,
			PRIMARY KEY (`umeta_id`),
			KEY `user_id` (`user_id`),
			KEY `meta_key` (`meta_key`)
		)";
		*/
		
		// meta
		$gp_schema['meta'] = "CREATE TABLE IF NOT EXISTS `{$table_prefix}meta` (
			`meta_id` bigint(20) NOT NULL auto_increment,
			`object_type` varchar(32) NOT NULL default 'gp_option',
			`object_id` bigint(20) NOT NULL default 0,
			`meta_key` varchar(255) DEFAULT NULL,
			`meta_value` longtext DEFAULT NULL,
			PRIMARY KEY (`meta_id`),
			KEY `object_type__meta_key` (`object_type`, `meta_key`),
			KEY `object_type__object_id__meta_key` (`object_type`, `object_id`, `meta_key`)
		)";

		// permissions
		$gp_schema['permissions'] = "CREATE TABLE IF NOT EXISTS `{$table_prefix}permissions` (
			`id` INT(10) NOT NULL AUTO_INCREMENT,
			`user_id` INT(10) DEFAULT NULL,
			`action` VARCHAR(255) DEFAULT NULL,
			`object_type` VARCHAR(255) DEFAULT NULL,
			`object_id` VARCHAR(255) DEFAULT NULL,
			PRIMARY KEY (`id`),
			KEY `user_id_action` (`user_id`,`action`)
		)";

		// API keys
		$gp_schema['api_keys'] = "CREATE TABLE IF NOT EXISTS `{$table_prefix}api_keys` (
			`id` INT(10) NOT NULL AUTO_INCREMENT,
			`user_id` INT(10) NOT NULL,
			`api_key` VARCHAR(16) NOT NULL,
			PRIMARY KEY (`id`),
			UNIQUE KEY `user_id` (`user_id`),
			UNIQUE KEY `api_key` (`api_key`)
		)";

		return $gp_schema;
	}
?>