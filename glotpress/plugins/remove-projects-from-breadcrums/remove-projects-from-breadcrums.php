<?php

class GP_Remove_Projects_From_Breadcrums extends GP_Plugin {
	public $id = 'gp-remove-projects-from-breadcrums';

	public $errors  = array();
	public $notices = array();

	public function __construct() {

		if( ! gp_const_get('GP_REMOVE_PROJECTS_FROM_BREADCRUMS') ) { 
			return; 
		}
	
		parent::__construct();

		$this->add_action( 'gp_pre_breadcrumb', array( 'args' => 1 ) );
		$this->add_action( 'gp_logo_url', array( 'args' => 1 ) );
	}

	public function gp_pre_breadcrumb( $breadcrums ) {
		
		if( is_array( $breadcrums ) ) { 
			if( is_array( $breadcrums[0] ) ) {
		
				unset( $breadcrums[0][0] ); 
			}
			else {
				unset( $breadcrums[0] ); 
			}
		}

		return $breadcrums;
	}
	
	public function gp_logo_url( $url ) {
		
		if( gp_const_get('GP_REMOVE_PROJECTS_FROM_BREADCRUMS_LOGO_URL') ) { 
			$url = $url . GP_REMOVE_PROJECTS_FROM_BREADCRUMS_LOGO_URL;
		}
		
		return $url;
	}

}

GP::$plugins->gp_remove_projects_from_breadcrums = new  GP_Remove_Projects_From_Breadcrums;