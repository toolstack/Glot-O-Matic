<?php
/*
	This is the ToolStack.com WordPress Utilities class.
	
	Copyright (C) 2015 Greg Ross <greg@toolstack.com>
	    All rights reserved.
	    This is free software with ABSOLUTELY NO WARRANTY.
	
	You can redistribute it and/or modify it under the terms of the
	GPL2, GNU General Public License version 2.
	
*/
if( !class_exists( 'ToolStack_WP_Utilities_V2_5' ) ) {
	class ToolStack_WP_Utilities_V2_5
		{
		private $plugin_slug = '';
		private $user_id = 0;
		private $debug_log = null;
		private $debug_file = null;
		private $line_type = "\n";
		
		public $options = array();
		public $user_options = array();
		public $plugin_dir;
		public $plugin_url;

		// Construction function.
		public function __construct( $slug = null, $file = null ) 
			{
			if( $file == null ) { $file = __FILE__; }
				
			// Get the plugin's base name, it will be something like 'plugin-slug/ToolStack-WP-Utilities.class.php'
			$plugin_basename = plugin_basename( $file );
			
			// Get the path of the plugins root directory, we do this my striping off the result we obtained above from the current file name.
			$plugin_dir_path = substr( $file, 0, - strlen( $plugin_basename ) );
			
			// Split the basename so we can get the directory of the plug.  We use this instead of the slug as it may be case sensitive but the slug is always lower case.
			// Afterwords, trim the dir name just in case.
			$split = preg_split( '_[\\\\/]_', $plugin_basename );
			$dirname = trim( $split[0] );
			
			// Now set our variables for use later.
			$this->plugin_dir = $plugin_dir_path . $dirname;
			$this->plugin_url = plugins_url() . '/' . $dirname;
			
			// Let's set the slug to use.
			if( $slug == null ) 
				{
				// If no slug was passed in, use the current directory name under the plugins directory, strtolower() to make sure we follow the normal convention.
				$this->plugin_slug = strtolower( $dirname );
				}
			else 
				{
				// If we had a slug passed in, use it as is.
				$this->plugin_slug = $slug;
				}

			// If we don't have a slug after the above, let's set a default so it's not empty.
			if( $this->plugin_slug == '' )
				{
				$this->plugin_slug = 'toolstackplaceholder';
				}
				
			// Load the options from the database
			$this->options = get_option( $this->plugin_slug ); 
			}

		function __destruct() 
			{
			$this->close_debug_log();
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
		public function get_user_option( $option, $default = FALSE ) 
			{
			// If the user id has not been set or no options array exists, return the default.
			if( $this->user_id == 0 ) {return $default; }
			if( !is_array($this->user_options[$this->user_id]) ) { return $default; }
			
			// if the option isn't set yet, return the $default if it exists, otherwise FALSE.
			if( !array_key_exists( $option, $this->user_options[$this->user_id] ) ) 
				{
				return $default;
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

		public function print_r_html( $var, $string = false)
			{
			return $this->var_export_html( $var, $string );
			}
			
		// This function accepts one or more PHP variables and output's it's contents in a human readable, html formates string.
		public function var_dump_html() 
			{
			foreach (func_get_args() as $n) 
				{
				$this->var_export_html( $n );
				echo "<br>\r\n";
				}
			}
			
		// This function accepts a PHP variable and output's it's contents in a human readable, html formates string.
		public function var_export_html( $var, $string = false )
			{
			$temp = str_replace( ' ', '&nbsp;', str_replace( "\n", '<br>', var_export( $var, true ) ) );
			
			if( !$string ) { echo $temp; }
			
			return $temp;
			}

		// This function takes a list of options and creates a table with appropriate <form> controls in it.
		public function generate_options_table( $options ) 
			{
			$ret = "<table>\r\n";
			
			$post_output = '';

			// Let's make sure we have an array passed in, otherwise, just return a blank result.
			if( !is_array( $options ) ) { return ''; }
			
			// Loop through the options and process them.
			foreach( $options as $name => $option ) 
				{
				// If the current option isn't an array or doesn't have a type set, do nothing and move on to the next one.
				if( !is_array( $option) || !array_key_exists( 'type', $option ) ) { continue; }
				
				switch( $option['type'] ) 
					{
					case 'title':
						$ret .= "					<tr><td colspan=\"2\"><h3>" . $name . "</h3></td></tr>\r\n";
						
						break;
					case 'desc':
						// Check to make sure we have everything we need.
						if( !array_key_exists( 'desc', $option ) ) { continue; }
						
						$ret .= "					<tr><td></td><td><span class=\"description\">" . $option['desc'] . "</span></td></tr>\r\n";
						
						break;
					case 'bool':
						// Check to make sure we have everything we need.
						if( !array_key_exists( 'setting', $option ) ) { $option['setting'] = 0; }
						if( !array_key_exists( 'desc', $option ) ) { continue; }
						
						if( $option['setting'] == 1 ) { $checked = " CHECKED"; } else { $checked = ""; } 
						$ret .= "					<tr><td style=\"text-align: right;\">" . $option['desc'] . ":</td><td><input name=\"$name\" value=\"1\" type=\"checkbox\" id=\"$name\"" . $checked. "></td></tr>\r\n";
					
						break;
					case 'image':
						// Check to make sure we have everything we need.
						if( !array_key_exists( 'setting', $option ) ) { $option['setting'] = ''; }
						if( !array_key_exists( 'desc', $option ) ) { continue; }

						$ret .= "					<tr><td style=\"text-align: right;\">" . $option['desc'] . ":</td><td><input name=\"$name\" type=\"text\" size=\"40\" id=\"$name\" value=\"" . $option['setting'] . "\"></td></tr>\r\n";
					
						break;
					case 'hidden':
						// Check to make sure we have everything we need.
						if( !array_key_exists( 'setting', $option ) ) { $option['setting'] = ''; }

						$post_output .= '<input type="hidden" name="lh_id" id="lh_id" value="' . $option['setting'] . '">' . "\r\n";

						break;
					case 'select':
						// Check to make sure we have everything we need.
						if( !array_key_exists( 'option_list', $option ) ) { continue; }
						if( !array_key_exists( 'desc', $option ) ) { continue; }

						$ret .= "					<tr><td style=\"text-align: right;\">" . $option['desc'] . ":</td><td><select name=\"$name\" id=\"$name\">" . $option['option_list']. "</select></td></tr>\r\n";

						break;
					case 'static':
						// Check to make sure we have everything we need.
						if( !array_key_exists( 'setting', $option ) ) { $option['setting'] = ''; }
						if( !array_key_exists( 'desc', $option ) ) { continue; }

						$ret .= "					<tr><td style=\"text-align: right;\">" . $option['desc'] . ":</td><td>" . $option['setting']. "</td></tr>\r\n";

						break;
					default:
						// Check to make sure we have everything we need.
						if( !array_key_exists( 'height', $option ) ) { $option['height'] = 1; }
						if( !array_key_exists( 'size', $option ) ) { $option['size'] = ''; }
						if( !array_key_exists( 'setting', $option ) ) { $option['setting'] = ''; }
						if( !array_key_exists( 'post', $option ) ) { $option['post'] = ''; }
						if( !array_key_exists( 'desc', $option ) ) { continue; }

						if( $option['height'] <= 1 ) 
							{
							$ret .= "					<tr><td style=\"text-align: right;\">" . $option['desc'] . ":</td><td><input name=\"$name\" type=\"text\" size=\"{$option['size']}\" id=\"$name\" value=\"" . $option['setting'] . "\">" . $option['post'] . "</td></tr>\r\n";
							}
						else 
							{
							$ret .= "					<tr><td style=\"text-align: right;\">" . $option['desc'] . ":</td><td><textarea name=\"$name\" type=\"text\" cols=\"{$option['size']}\" rows=\"{$option['height']}\" id=\"$name\">" . esc_html( $option['setting'] ) . "</textarea>" . $option['post'] . "</td></tr>\r\n";
							}
					}
				}

			$ret .= "</table>\r\n";
			
			$ret .= $post_output;
			
			return $ret;
			}
			
		// This function sets the line ending type, either \n or \r\n.
		public function set_line_type( $type )
			{
			$this->line_type = $type;
			}
		
		// This function sets the filename for the debug log, including a path
		public function set_debug_log( $file )
			{
			$this->debug_file = $file;
			}
			
		// This function opens the debug log for appending if it is not already open.
		private function open_debug_log()
			{
			if( $this->debug_log == null ) 
				{
				if( $this->debug_file == null ) { $this->debug_file = sys_get_temp_dir() . '/debug.txt'; }
				$this->debug_log = fopen($this->debug_file, 'a');
				}
			}
			
		// This function writes a line to the debug log.
		public function write_debug_log( $text )
			{
			$this->open_debug_log();
			
			fwrite($this->debug_log, '[' . date("Y-m-d H:i:s") . '] ' . $text . $this->line_type);
			}

		// This function writes a PHP variable to the debug log in a human readable format.
		public function write_debug_log_var( $var ) 
			{
			$this->open_debug_log();

			$var_e = var_export( $var, true );
			$var_e = str_replace( "\n", "\n                      ", $var_e );
			
			fwrite($this->debug_log, '[' . date("Y-m-d H:i:s") . '] ' . $var_e . $this->line_type);
			}
			
		// This function closes the debug log.
		public function close_debug_log() 
			{
			if( $this->debug_log != null ) 
				{
				fclose( $this->debug_log );
				$this->debug_log = null;
				}	
			}
			
		}
	}