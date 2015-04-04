<?php

class GP_Google_Translate extends GP_Plugin {
	public $id = 'google-translate';

	public $errors  = array();
	public $notices = array();

	private $key;

	public function __construct() {
		$this->key = gp_const_get('GP_GOOGLE_TRANSLATE_KEY');

		if( ! gp_const_get('GP_GOOGLE_TRANSLATE') && ! $this->key ) { 
			return; 
		}

		parent::__construct();

		if( GP::$user->current()->can( 'write', 'project' ) ) {
			$this->add_action( 'gp_project_actions', array( 'args' => 2 ) );
		}
		
		$this->add_action( 'pre_tmpl_load', array( 'args' => 2 ) );
		$this->add_filter( 'gp_entry_actions' );
		$this->add_action( 'gp_translation_set_bulk_action' );
		$this->add_action( 'gp_translation_set_bulk_action_post', array( 'args' => 4 ) );

		// We can't use the filter in the defaults route code because plugins don't load until after
		// it has already run, so instead add the routes directly to the global GP_Router object.
		GP::$router->add( "/bulk-translate/(.+?)", array( $this, 'bulk_translate' ), 'get' );
		GP::$router->add( "/bulk-translate/(.+?)", array( $this, 'bulk_translate' ), 'post' );
	}

	public function gp_project_actions( $actions, $project ) {
		$actions[] .= gp_link_get( gp_url( 'bulk-translate/' . $project->slug), __('Bulk Google Translate') );
		
		return $actions;
	}
	
	public function before_request() {
	}
	
	public function bulk_translate( $project_path ) {
		$project_path = urldecode( $project_path );
		$url = gp_url_project( $project_path );

		// If we don't have rights, just redirect back to the project.
		if( !GP::$user->current()->can( 'write', 'project' ) ) {
			gp_redirect( $url );
		}

		// Create a project class to use to get the project object.
		$project_class = new GP_Project;
		
		// Get the project object from the project path that was passed in.
		$project_obj = $project_class->by_path( $project_path );
		
		// Get the translations sets from the project ID.
		$translation_sets = GP::$translation_set->by_project_id( $project_obj->id );

		// Loop through all the sets.
		foreach( $translation_sets as $set ) {
			//Array ( [action] => gtranslate [priority] => 0 [redirect_to] => http://localhost/wp40/gp/projects/sample/bg/my [row-ids] => Array ( [0] => 1 [1] => 2 ) ) 
			$bulk = array( 'action' => 'gtranslate', 'priority' => 0, 'row-ids' => array() );
			
			$translation = new GP_Translation;
			
			$strings = $translation->for_translation( $project_obj, $set, null, array( 'status' => 'untranslated') );

			foreach( $strings as $string ) {
				$bulk['row-ids'][] .= $string->row_id;
			}
			
			$locale = GP_Locales::by_slug( $set->locale );
			
			$this->gp_translation_set_bulk_action_post( $project_obj, $locale, $set, $bulk );
		}

		$url = gp_url_project( $project_path );
		gp_redirect( $url );
	}

	public function after_request() {
	}
	
	public function pre_tmpl_load( $template, $args ) {
		if (GP::$user->logged_in()) {
			$user_obj = GP::$user->current();
			
			$user = strtoupper($user_obj->user_login);

			$user_key = gp_const_get('GP_GOOGLE_TRANSLATE_KEY_'.$user);
			if( $user_key ) { $this->key = $user_key; }
		}

		if( ! $this->key ) {
			return;
		}
		
		if ( 'translations' != $template ) {
			return;
		}

		if ( ! $args['locale']->google_code ) {
			return;
		}

		$url = gp_url_public_root();

		if ( is_ssl() ) {
			$url = gp_url_ssl( $url );
		}

		$options = array(
			'key'    => $this->key,
			'locale' => $args['locale']->google_code
		);

		wp_enqueue_script( 'google-translate', $url . '/plugins/google-translate/google-translate.js', array( 'jquery', 'editor' ) );
		wp_localize_script( 'google-translate', 'gp_google_translate', $options );

	}

	public function gp_entry_actions( $actions ) {
		$actions[] = '<a href="#" class="gtranslate" tabindex="-1">' . __('Translation from Google') . '</a>';

		return $actions;
	}


	public function gp_translation_set_bulk_action() {
		echo '<option value="gtranslate">' . __('Translate via Google') . '</option>';
	}

	public function gp_translation_set_bulk_action_post( $project, $locale, $translation_set, $bulk ) {
		if ( 'gtranslate' != $bulk['action'] ) {
			return;
		}

		if (GP::$user->logged_in() && ! $this->key) {
			$user_obj = GP::$user->current();
			
			$user = strtoupper($user_obj->user_login);

			$user_key = gp_const_get('GP_GOOGLE_TRANSLATE_KEY_'.$user);
			if( $user_key ) { $this->key = $user_key; }
		}

		if( ! $this->key ) {
			return;
		}
		
		$google_errors = 0;
		$insert_errors = 0;
		$ok      = 0;
		$skipped = 0;

		$singulars = array();
		$original_ids = array();

		foreach ( $bulk['row-ids'] as $row_id ) {
			if ( gp_in( '-', $row_id ) ) {
				$skipped++;
				continue;
			}

			$original_id = gp_array_get( explode( '-', $row_id ), 0 );
			$original    = GP::$original->get( $original_id );

			if ( ! $original || $original->plural ) {
				$skipped++;
				continue;
			}

			$singulars[] = $original->singular;
			$original_ids[] = $original_id;
		}

		$results = $this->google_translate_batch( $locale, $singulars );

		if ( is_wp_error( $results ) ) {
			error_log( print_r( $results, true ) );
			$this->errors[] = $results->get_error_message();
			return;

		}

		$items = gp_array_zip( $original_ids, $singulars, $results );

		if ( ! $items ) {
			return;
		}

		foreach ( $items as $item ) {
			list( $original_id, $singular, $translation ) = $item;

			if ( is_wp_error( $translation ) ) {
				$google_errors++;
				error_log( $translation->get_error_message() );
				continue;
			}

			$data = compact( 'original_id' );
			$data['user_id'] = GP::$user->current()->id;
			$data['translation_set_id'] = $translation_set->id;
			$data['translation_0'] = $translation;
			$data['status'] = 'fuzzy';
			$data['warnings'] = GP::$translation_warnings->check( $singular, null, array( $translation ), $locale );

			$inserted = GP::$translation->create( $data );
			$inserted? $ok++ : $insert_errors++;
		}

		if ( $google_errors > 0 || $insert_errors > 0 ) {
			$message = array();

			if ( $ok ) {
				$message[] = sprintf( __('Added: %d.' ), $ok );
			}

			if ( $google_errors ) {
				$message[] = sprintf( __('Error from Google Translate: %d.' ), $google_errors );
			}

			if ( $insert_errors ) {
				$message[] = sprintf( __('Error adding: %d.' ), $insert_errors );
			}

			if ( $skipped ) {
				$message[] = sprintf( __('Skipped: %d.' ), $skipped );
			}

			$this->errors[] = implode( '', $message );
		}
		else {
			$this->notices[] = sprintf( __('%d fuzzy translation from Google Translate were added.' ), $ok );
		}
	}

	public function google_translate_batch( $locale, $strings ) {
		if ( ! $locale->google_code ) {
			return new WP_Error( 'google_translate', sprintf( "The locale %s isn't supported by Google Translate.", $locale->slug ) );
		}

		$url = 'https://www.googleapis.com/language/translate/v2?key=' . $this->key . '&source=en&target=' . urlencode( $locale->google_code );

		foreach ( $strings as $string ) {
			$url .= '&q=' . urlencode( $string );
		}

		if ( count( $strings ) == 1 ) {
			$url .= '&q=';
		}

		$response = wp_remote_get( $url );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$json = json_decode( wp_remote_retrieve_body( $response ) );

		if ( ! $json ) {
			return new WP_Error( 'google_translate', 'Error decoding JSON from Google Translate.' );
		}

		if ( isset( $json->error ) ) {
			return new WP_Error( 'google_translate', sprintf( 'Error auto-translating: %1$s', $json->error->errors[0]->message ) );
		}

		$translations = array();

		if ( ! is_array( $json->data->translations ) ) {
			$json->data->translations = array( $json->data->translations );
		}

		$items = gp_array_zip( $strings, $json->data->translations );

		if ( ! $items ) {
			return new WP_Error( 'google_translate', 'Error merging arrays' );
		}

		foreach ( $items as $item ) {
			list( $string, $translation ) = $item;

			$translations[] = $this->google_translate_fix( $translation->translatedText );
		}

		return $translations;
	}

	public function google_translate_fix( $string ) {
		$string = preg_replace_callback( '/% (s|d)/i', lambda( '$m', '"%".strtolower($m[1])' ), $string );
		$string = preg_replace_callback( '/% (\d+) \$ (s|d)/i', lambda( '$m', '"%".$m[1]."\\$".strtolower($m[2])' ), $string );
		return $string;
	}
}

GP::$plugins->gp_google_translate = new GP_Google_Translate;