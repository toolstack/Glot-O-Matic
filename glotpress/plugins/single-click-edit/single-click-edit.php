<?php

class GP_Single_Click_Edit extends GP_Plugin {
	public $id = 'single-click-edit';

	public function __construct() {
		if( !gp_const_get('GP_SINGLE_CLICK_EDIT') ) { 
			return; 
		}

		parent::__construct();
	
		$this->add_action( 'pre_tmpl_load', array( 'args' => 2 ) );
	}

	public function pre_tmpl_load( $template, $args ) {
		$url = gp_url_public_root();
		wp_enqueue_script( 'single-click-edit', $url . '/plugins/single-click-edit/single-click-edit.js', array( 'jquery', 'editor' ) );

	}

}

GP::$plugins->gp_single_click_edite = new GP_Single_Click_Edit;