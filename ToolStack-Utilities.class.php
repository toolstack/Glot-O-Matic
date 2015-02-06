<?php
/*
	This is the ToolStack.com WordPress Utilities class.
*/
if( !class_exists( 'ToolStack_Utilities' ) ) {
	class ToolStack_Utilities 
		{
		private $plugin_slug = '';
		private $user_id = 0;
		
		public $options = array();
		public $user_options = array();

		// Construction function.
		public function __construct( $slug = null ) 
			{
			if( $slug == null ) 
				{
				$plugin_basename = plugin_basename( __FILE__ );
				
				$exp = preg_split( '_[\\\\/]_', $plugin_basename );

				$this->plugin_slug = strtolower( trim( $exp[0] ) );
				}
			else 
				{
				$this->plugin_slug = $slug;
				}

			if( $this->plugin_slug == '' )
				{
				$this->plugin_slug = 'toolstackplaceholder';
				}
				
			// Load the options from the database
			$this->options = get_option( $this->plugin_slug ); 
			}

		// This function sets the current WordPress user id for the class.
		public function set_user_id( $id = 0 ) 
			{
			if( $id > 0 )
				{
				$this->user_id = $id;
				}

			if( $this->user_id == 0 ) 
				{
				$this->user_id = get_current_user_id();
				}
			}
		
		// This function loads the options from WordPress, it is included here for completeness as the options are loaded automatically in the class constructor.
		public function load_options() 
			{
			$this->options = get_option( $this->plugin_slug ); 
			}
		
		// This function loads the user options from WordPress.  It is NOT called during the class constructor.
		public function load_user_options( $user_id = 0 ) 
			{
			// Avoid reloading options.
			if( array_key_exists( $this->user_id, $this->user_options ) ) { return; }
			
			$this->set_user_id( $user_id );

			// Not sure why, but get_user_meta() is returning an array or array's unless $single is set to true.
			$this->user_options[$this->user_id] = get_user_meta( $this->user_id, $this->plugin_slug, true );
			}
		
		// The function mimics WordPress's get_option() function but uses the array instead of individual options.
		public function get_option( $option, $default = null ) 
			{
			// If no options array exists, return FALSE.
			if( !is_array($this->options) ) { return FALSE; }
		
			// if the option isn't set yet, return the $default if it exists, otherwise FALSE.
			if( !array_key_exists( $option, $this->options ) ) 
				{
				if( isset( $default ) ) 
					{
					return $default;
					} 
				else 
					{
					return FALSE;
					}
				}
			
			// Return the option.
			return $this->options[$option];
			}
		
		public function get_the_author_meta( $option, $user_id = 0 )
			{
			if( $user_id == 0 || $user_id == $this->user_id ) 
				{
				return get_user_option( $option );
				}
			else
				{
				$temp_user_id = $this->user_id;
				
				$this->set_user_id( $user_id );

				if( !array_key_exists( $user_id, $this->user_options ) ) { $this->load_user_options(); }
				
				$result = get_user_option( $option );
				
				$this->set_user_id( $temp_user_id );
				
				return $result;
				}
			}
		
		public function update_user_meta( $user_id, $option, $default )
			{
			if( $user_id == 0 || $user_id == $this->user_id ) 
				{
				return update_user_option( $option, $default );
				}
			else
				{
				$temp_user_id = $this->user_id;
				
				$this->set_user_id( $user_id );

				if( !array_key_exists( $user_id, $this->user_options ) ) { $this->load_user_options(); }
				
				$result = update_user_option( $option, $default );
				
				$this->set_user_id( $temp_user_id );
				
				return $result;
				}
			}
		
		// This function mimics WordPress's get_user_meta() function but uses the array instead of individual options.
		public function get_user_option( $option, $default = null ) 
			{
			// If the user id has not been set or no options array exists, return FALSE.
			if( $this->user_id == 0 ) {return FALSE; }
			if( !is_array($this->user_options[$this->user_id]) ) { return FALSE; }
			
			// if the option isn't set yet, return the $default if it exists, otherwise FALSE.
			if( !array_key_exists( $option, $this->user_options[$this->user_id] ) ) 
				{
				if( isset( $default ) ) 
					{
					return $default;
					} 
				else 
					{
					return FALSE;
					}
				}
			
			// Return the option.
			return $this->user_options[$this->user_id][$option];
		}

		// The function mimics WordPress's update_option() function but uses the array instead of individual options.
		public function update_option( $option, $value ) 
			{
			// Store the value in the array.
			$this->options[$option] = $value;
			
			// Write the array to the database.
			update_option( $this->plugin_slug, $this->options );
			}
		
		// The function mimics WordPress's update_user_meta() function but uses the array instead of individual options.
		public function update_user_option( $option, $value ) 
			{
			// If the user id has not been set return FALSE.
			if( $this->user_id == 0 ) { return FALSE; }

			// Store the value in the array.
			$this->user_options[$this->user_id][$option] = $value;
			
			// Write the array to the database.
			update_user_meta( $this->user_id, $this->plugin_slug, $this->user_options[$this->user_id] );
			}

		// This function is similar to update_option, but it only stores the option in the array.  This save some writing to the database if you have multiple values to update.
		public function store_option( $option, $value ) 
			{
			$this->options[$option] = $value;
			}
		
		// This function is similar to update_user_option, but it only stores the option in the array.  This save some writing to the database if you have multiple values to update.
		public function store_user_option( $option, $value ) 
			{
			// If the user id has not been set return FALSE.
			if( $this->user_id == 0 ) { return FALSE; }
			
			$this->user_options[$this->user_id][$option] = $value;
			}

		// This function saves the current options array to the database.
		public function save_options() 
			{
			update_option( $this->plugin_slug, $this->options );
			}
		
		// This function saves the current user options array to the database.
		public function save_user_options() 
			{
			if( $this->user_id == 0 ) { return FALSE; }
			if( !array_key_exists( $this->user_id, $this->user_options ) ) { return FALSE; }

			update_user_meta( $this->user_id, $this->plugin_slug, $this->user_options[$this->user_id] );
			}
		
		// This function check to see if an option is currently set or not.
		public function isset_option( $option ) 
			{
			if( !is_array($this->options) ) { return FALSE; }
			
			return array_key_exists( $option, $this->options );
			}
		
		// This function check to see if a user option is currently set or not.
		public function isset_user_option( $option ) 
			{
			if( $this->user_id == 0 ) { return FALSE; }
			if( !is_array($this->user_options) ) { return FALSE; }

			return array_key_exists( $option, $this->user_options[$this->user_id] );
			}

		}
	}