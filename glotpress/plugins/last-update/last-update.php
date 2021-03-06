<?php

class GP_Last_Update extends GP_Plugin {
	public $id = 'last-update';
	
	private $dateformat;

	public function __construct() {
		// Check to see if we're enabled, if not simply return.
		if( ! gp_const_get('GP_LAST_UPDATE') ) { 
			return; 
		}

		// Call the parent constructor.
		parent::__construct();
		
		// Get the date format we're going to use.
		$this->dateformat = gp_const_get('GP_LAST_UPDATE_FORMAT', 'M j Y @ g:i a' );
		
		// If for some reason the date format is empty, use the default.
		if( $this->dateformat == '' ) { $this->dateformat = 'M j Y @ g:i a'; }
		
		// Get the required permission to see the last update info, make sure it's all lower case.
		$reqperm = strtolower( gp_const_get('GP_LAST_UPDATE_REQUIRED_PERMISSION', false) );
		
		// If it's not recognized as read or approve, force it to admin.
		if( $reqprem != 'read' || $reqperm != 'approve' ) { $reqperm = 'admin'; }

		// Check to see the current user has permissions.
		if( GP::$user->current()->can( $reqperm, 'project' ) ) {
			// Add the hook.
			$this->add_action( 'project_template_translation_set_extra', array( 'args' => 2 ) );
		}
	}

	public function project_template_translation_set_extra( $set, $project ) {
		// Get the translation set's last update time, in GMT.
		$dt = backpress_gmt_strtotime( GP::$translation->last_modified( $set ) );
		
		// Check to see if we have a valid time.
		if( $dt > 0 ) {		
			// Output the last update info for the translation set.
			echo 'Last updated on ' . date( $this->dateformat, $dt ) . '<br>';
		}
		else {
			// A 0 value for the time means there has never been an update done.
			echo 'Never updated<br>';
		}
	}

}

GP::$plugins->gp_last_update = new GP_Last_Update;
