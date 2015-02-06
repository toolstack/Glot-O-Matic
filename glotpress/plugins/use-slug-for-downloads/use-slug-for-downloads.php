<?php

class GP_User_Slug_for_Downloads extends GP_Plugin {
	public $id = 'use-slug-for-downloads';

	public $errors  = array();
	public $notices = array();

	public function __construct() {

		if( ! gp_const_get('GP_USE_SLUG_FOR_DOWNLOADS') ) { 
			return; 
		}
	
		parent::__construct();

		$this->add_action( 'export_filename', array( 'args' => 5 ) );
	}

	public function export_filename( $filename, $project_path, $translation_set_slug, $export_locale, $format_extension ) {
		if( $translation_set_slug != '' && $translation_set_slug != 'default' ) {
			$filename = $translation_set_slug . '.' . $format_extension;
		}
		
		return $filename;
	}

}

GP::$plugins->gp_use_slug_for_downloads = new  GP_User_Slug_for_Downloads;