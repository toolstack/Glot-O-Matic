<?php

class GP_WordPress_Single_Sign_On extends GP_Plugin {
	public $id = 'wordpress-single-sign-on';

	public $errors  = array();
	public $notices = array();

	public function __construct() {

		if( ! gp_const_get('GP_WORDPRESS_SINGLE_SIGN_ON') ) { 
			return; 
		}
	
		parent::__construct();

		$this->add_action( 'gp_logout_link', array( 'args' => 1 ) );
		$this->add_action( 'gp_login_link', array( 'args' => 1 ) );
	}

	public function gp_logout_link( $default ) {
		return '';
	}

	public function gp_login_link( $default ) {
		return '';
	}

}

GP::$plugins->gp_wordpress_single_sign_on = new  GP_WordPress_Single_Sign_On;