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

		$this->add_action( 'export_translations_filename', array( 'args' => 5 ) );
	}

	public function export_translations_filename( $filename, $format, $locale, $project, $translation_set ) {

		if( $translation_set->slug != '' && $translation_set->slug != 'default' ) {
			$filename = $translation_set->slug . '.' . $format->extension;
		}
		
		return $filename;
	}

}

GP::$plugins->gp_use_slug_for_downloads = new  GP_User_Slug_for_Downloads;