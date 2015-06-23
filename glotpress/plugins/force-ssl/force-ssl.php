<?php

class GP_Force_SSL extends GP_Plugin {
	public $id = 'force-ssl';

	public function __construct() {
		if( ! gp_const_get('GP_FORCE_SSL') ) { 
			return; 
		}

		parent::__construct();

		$this->add_action( 'plugins_loaded' );
	}

	public function plugins_loaded() {
		// Check to see if we're connected via https, if not, redirect.
		if( !is_ssl() ) {
			gp_redirect( gp_url_ssl( gp_url_current() ) );
		}
	}

}

GP::$plugins->gp_force_ssl = new GP_Force_SSL;