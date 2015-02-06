<?php

class GP_New_Window_for_External_Links extends GP_Plugin {
	public $id = 'new-window-for-external-links';

	public $errors  = array();
	public $notices = array();

	private $key;

	public function __construct() {
		if( ! gp_const_get('GP_NEW_WINDOW_FOR_EXTERNAL_LINKS') ) { 
			return; 
		}

		parent::__construct();

		$this->add_action( 'pre_tmpl_load', array( 'args' => 2 ) );
	}

	public function pre_tmpl_load( $template, $args ) {

		$url = gp_url_public_root();

		if ( is_ssl() ) {
			$url = gp_url_ssl( $url );
		}

		wp_enqueue_script( 'new-window-for-external-links', $url . 'plugins/new-window-for-external-links/new-window-for-external-links.js', array( 'jquery' ) );

	}

}

GP::$plugins->gp_new_window_for_external_links = new GP_New_Window_for_External_Links;