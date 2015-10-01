<?php

class GP_Color_Logo extends GP_Plugin {
	public $id = 'gp-color-logo';

	public $errors  = array();
	public $notices = array();

	public function __construct() {

		if( ! gp_const_get('GP_COLOR_LOGO') ) { 
			return; 
		}
	
		parent::__construct();

		$this->add_action( 'gp_get_option_title', array( 'args' => 1 ) );
	}

	public function gp_get_option_title( $url ) {
		
		return '<span style="color: #21759B;">Glot</span><span style="color: #EEEEEE;">Press</span>';
	}

}

GP::$plugins->gp_color_logo = new  GP_Color_Logo;