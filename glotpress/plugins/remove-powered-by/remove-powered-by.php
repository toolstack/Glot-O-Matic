<?php

class GP_Remove_Powered_By extends GP_Plugin {
	public $id = 'remove-powered-by';

	public $errors  = array();
	public $notices = array();

	private $key;

	public function __construct() {
		if( ! gp_const_get('GP_REMOVE_POWERED_BY') ) { 
			return; 
		}

		parent::__construct();

		$this->add_action( 'pre_tmpl_load', array( 'args' => 2 ) );
		$this->add_action( 'gp_footer', array( 'args' => 0, 'priority' => 1 ) );
	}

	public function pre_tmpl_load( $template, $args ) {

		$url = gp_url_public_root();

		if ( is_ssl() ) {
			$url = gp_url_ssl( $url );
		}

		wp_enqueue_script( 'remove-powered-by', $url . 'plugins/remove-powered-by/remove-powered-by.js', array( 'jquery' ) );

	}

	public function gp_footer() {
		echo '<span style="display: none;">--GP_RPB_MARKER--</span>&nbsp;';
	}
}

GP::$plugins->gp_remove_powered_by = new GP_Remove_Powered_By;