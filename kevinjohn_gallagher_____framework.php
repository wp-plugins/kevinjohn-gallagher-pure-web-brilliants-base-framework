<?php
/*
	Plugin Name: 			Kevinjohn Gallagher:  _PWB Base Framework
	Description: 			Framework required for all Pure Web Brilliant plug-ins, themes and CMS features.
	Version: 				2.0
	Author: 				Kevinjohn Gallagher
	Author URI: 			http://kevinjohngallagher.com/
	
	Contributors:			kevinjohngallagher, purewebbrilliant 
	Donate link:			http://kevinjohngallagher.com/
	Tags: 					kevinjohn gallagher, pure web brilliant, framework, cms, simple, multisite
	Requires at least:		3.0
	Tested up to: 			3.2
	Stable tag: 			2.0
*/
/**
 *
 *	Kevinjohn Gallagher: Pure Web Brilliant's base framework
 * ==========================================================
 *
 *	Framework required for all Pure Web Brilliant plug-ins, themes and CMS features.
 *
 *
 *	This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 *	General Public License as published by the Free Software Foundation; either version 3 of the License, 
 *	or (at your option) any later version.
 *
 * 	This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
 *	without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *	See the GNU General Public License (http://www.gnu.org/licenses/gpl-3.0.txt) for more details.
 *
 *	You should have received a copy of the GNU General Public License along with this program.  
 * 	If not, see http://www.gnu.org/licenses/ or http://www.gnu.org/licenses/gpl-3.0.txt
 *
 *
 *	Copyright (C) 2008-2012 Kevinjohn Gallagher / http://www.kevinjohngallagher.com
 *
 *
 *	@package				Pure Web Brilliant
 *	@version 				2.0.1
 *	@author 				Kevinjohn Gallagher <wordpress@kevinjohngallagher.com>
 *	@copyright 				Copyright (c) 2012, Kevinjohn Gallagher
 *	@link 					http://kevinjohngallagher.com
 *	@license 				http://www.gnu.org/licenses/gpl-3.0.txt
 *
 *
 */



	define( '_KEVINJOHN_GALLAGHER_FRAMEWORK', '2.0' );
	

	
	class kevinjohn_gallagher 
	{
	
			const PM	=	'_kevinjohn_gallagher_framework';
			var				$instance;
			var				$plugin_dir;
			var				$plugin_url;			


		
			public	function	__construct() 
			{
					$this->instance 		=& $this;
					$this->uniqueID 		= self::PM;
					add_action( 'init', array( $this, 'init' ) );
					add_action( 'admin_menu', array( $this, 'framework_admin_menu_setup' ) );
					
			}


			public function init() 
			{
					$this->plugin_dir		=	plugin_dir_path(__FILE__);	
					$this->plugin_url		=	plugin_dir_url(__FILE__);				
			}
			
			

			/**
			 *		Adds parent menu page
			 *		 
			 */							
			public 	function 	framework_admin_menu_setup()
			{					
					add_menu_page(	'Kevinjohn Gallagher\'s PWN framework',				 
									'Kevinjohn Gallagher', 					
									'manage_options',					 
									'kevinjohn-gallagher-framework',	 
									array( $this, 'framework_admin_page' ),	 
									'',
									100								 
									);
			}
			
			
		
		
			/**
			 *		
			 *		 
			 * 		@return		object
			 */			
			public function 	child_settings_array_default_setup()
			{
					$this->child_settings_array_default = array(
																'id'      		=> 'default_field',
																'title'   		=> 'Default Field',
																'description'	=> 'This is a default description.',
																'std'     		=> '',
																'type'    		=> 'text',
																'section' 		=> 'general',
																'choices' 		=> array(),
																'class'   		=> ''
															);
			}



		
		
			/**
			 *		Adds a submenu item to the WP menu system
			 *		 
			 * 		@param  	string $page_title
			 * 		@param  	string $menu_title
			 * 		@param  	string menu_slug
			 * 		@param  	string function			 
			 */
			public 	function 	framework_admin_menu_child($page_title, $menu_title, $menu_slug, $function)
			{
					add_submenu_page(	'kevinjohn-gallagher-framework', 
										$page_title .' :: Kevinjohn Gallagher Framework', 
										$menu_title, 
										'edit_pages', 
										$menu_slug, 
										$function
									);
			}

			
		
		
			/**
			 *		Constant Checker to help inheritance
			 *		 
			 */			
			function constant_check()
			{
				return self::PM;
			}
			
			
		
		
			/**
			 *		inheritence function to register child settings
			 *		
			 */			
			public 	function 	admin_init_register_settings()
			{
					$this->child_register_settings();
			}

			
		
		
			/**
			 *		register child settings
			 *		 
			 */			
			public 	function 	child_register_settings()
			{			
					register_setting(	'kevinjohn_gallagher_options', 
										$this->uniqueID . '___options', 
										array( $this, 'framework_validate_options') 
									);
														
					
					foreach ( $this->child_settings_sections as $slug => $title )
					{
						add_settings_section( $slug, $title, array( &$this, 'framework_display_section' ), $this->uniqueID. '___options' );
					}	
					
					
					foreach ( $this->child_settings_array as $id => $setting ) {
						$setting['id'] = $id;
						$this->framework_create_setting( $setting );
					}
			}			
			
			
		
		
			/**
			 *		Outputs settings page
			 *		 
			 */
			public 	function 	framework_admin_page()
			{
					 $this->framework_admin_page_header('title', 'icon_class');
					 $this->framework_admin_page_footer();
			}



		
			/**
			 *		Outputs settings page footer
			 *		 
			 * 		@title
			 * 		@icon_class			 
			 * 		@return		string
			 */			
			public 	function 	framework_admin_page_header($title, $icon_class)
			{
					echo	"<div 	class='wrap'>";
					echo	"<h2 	class='". $icon_class ."'> ". $title ." </h2>";
					echo	"<div 	class='metabox-holder'>";	
					echo	"<form method='post' action='options.php'>";
					
					settings_fields('kevinjohn_gallagher_options');
					$options	=	get_option($this->uniqueID . '___options');
					

					do_settings_sections( $this->uniqueID . '___options' );
			
			}


		
		
			/**
			 *		Outputs settings page footer
			 *		 
			 */
			public 	function 	framework_admin_page_footer()
			{
					echo	'<br /><p class="submit">';
					echo	'<input name="Submit" type="submit" class="button-primary" value="' . __( 'Save Changes' ) . '" />';
					echo	'</p>';
					echo	"</form>";	
					echo	"</div>	<!--	.metabox-holder	-->";			
					echo	"</div>	<!--	.wrap	-->";
			}
			
			
		
		
			/**
			 *		This actually does more - sadly waiting for licences to expire
			 *		 
			 * 		@param  	string
			 * 		@return		string
			 */			
			public 	function 	framework_validate_options($input)
			{
					return $input;
			}
			

		
		
			/**
			 *		Calls correct form field for settings API
			 *		 
			 * 		@args  		array
			 * 		@return		boolean
			 */			
			public 	function 	framework_display_setting( $args = array() )
			{
					$options = get_option( $this->uniqueID. '___options' );

					call_user_func( array($this, 'framework_display_setting_type_'. $args['type']) , $args, $options);

					echo	"<span class='description'> ". $args['description'] ." </span>";
					
					return true;			
			}
			

		
		
			/**
			 *		Outputs Text input for a WP settings page
			 *		 
			 * 		@args  		array
			 * 		@options	array
			 */
			public function 	framework_display_setting_type_text($args, $options)
			{
					extract($args);

					echo	'<input 
									type="text"					
									class="regular-text ' . $field_class . '" 
									name="'. $this->uniqueID . '___options[' . $id . ']"
									id="' . $id . '" 
									placeholder="' . $std . '" 
									value="' . esc_attr( $options[$id] ) . '" 
								/>';
			}
			
			

		
		
			/**
			 *		Outputs Checkboxes for a WP settings page
			 *		 
			 * 		@args  		array
			 * 		@options	array
			 */
			public function 	framework_display_setting_type_checkbox($args, $options)
			{
					extract($args);

					echo	'<input 
									type="checkbox"					
									class="checkbox ' . $field_class . '" 
									name="'. $this->uniqueID . '___options[' . $id . ']"
									id="' . $id . '" 
									value="1" 
									'. checked( $options[$id], 1, false ) .'
								/>';
					
			}
						
			

		
		
			/**
			 *		Outputs Radio Buttons for a WP settings page
			 *		 
			 * 		@args  		array
			 * 		@options	array
			 */
			public function 	framework_display_setting_type_radio($args, $options)
			{
					extract($args);

					$i = 0;
					foreach ( $choices as $value => $label ) 
					{
						echo	'<input 
										class="radio' . $field_class . '" 
										type="radio" 
										name="'. $this->uniqueID . '___options[' . $id . ']"  
										id="' . $id . '___' . $value . '" 
										value="' . esc_attr( $value ) . '" ' . checked( $options[$id], $value, false ) . '
								> 
								<label for="' . $id . $i . '">' . $label . '</label>';
								
						echo 	'<br />';
						
						$i++;
					}		
					
					echo 	'<br />';				
					
			}			


		
		
			/**
			 *		Outputs Select for a WP settings page
			 *		 
			 * 		@args  		array
			 * 		@options	array
			 */
			public function 	framework_display_setting_type_select($args, $options)
			{
					extract($args);
					
					echo	'<select 
									class="radio' . $field_class . '" 
									type="radio" 
									name="'. $this->uniqueID . '___options[' . $id . ']"  
									id="' . $id . '___' . $value . '" 
							>';
													
					$i = 0;
					foreach ( $choices as $value => $label ) 
					{
						echo	'<option 
										value="' . esc_attr( $value ) . '" ' . selected( $options[$id], $value, false ) . '
								> 
								' . $label . '
								</option>';
														
						$i++;
					}

					echo	'</select>';							
					echo 	'<br />';
					echo 	'<br />';				
					
			}	



		
		
			/**
			 *		Outputs Media for a WP settings page
			 *		 
			 * 		@args  		array
			 * 		@options	array
			 */
			public function 	framework_display_setting_type_media($args, $options)
			{
					extract($args);

					echo	'<input 
									id="' . $id . '" 
									type="text" 
									size="36" 
									name="'. $this->uniqueID . '___options[' . $id . ']" 
									value="" 
								/>';
								
					echo	'<input 
									id="' . $id . '_trigger" 
									type="button" 
									class="button-secondary" 
									value="Upload Image" 
								/>';
					
					if ( $options[$id] != '' ) 
					{
						echo '<br />
								<br />
								<a class="thickbox" href=' . $options[$id] . '>' .  __('Currently uploaded image') . '</a>';
					}
					
					echo 	'<br />';
			}


		
		
			/**
			 *		Outputs Password for a WP settings page
			 *		 
			 * 		@args  		array
			 * 		@options	array
			 */
			public function 	framework_display_setting_type_password($args, $options)
			{
					extract($args);

					echo	'<input 
									type="password"					
									class="regular-text ' . $field_class . '" 
									name="'. $this->uniqueID . '___options[' . $id . ']"
									id="' . $id . '" 
									placeholder="' . $std . '" 
									value="' . esc_attr( $options[$id] ) . '" 
								/>';
			}
			


		
		
			/**
			 *		Outputs Textarea for a WP settings page
			 *		 
			 * 		@args  		array
			 * 		@options	array
			 */
			public function 	framework_display_setting_type_textarea($args, $options)
			{
					extract($args);

					echo	'<textarea 				
									class="regular-text ' . $field_class . '" 
									name="'. $this->uniqueID . '___options[' . $id . ']"
									id="' . $id . '" 
									placeholder="' . $std . '" 
									rows="6" 
									cols="30"
								>';
								
					echo	wp_htmledit_pre( $options[$id] );
								
					echo	'</textarea>';
					
			}
			
			
			
			

			/**
			 *		Creates a settings field for option pages
			 *		 
			 * 		@args  	A multipurpose array 
			 */	
			public function framework_create_setting( $args = array() ) {	
			
					extract( wp_parse_args( $args, $this->child_settings_array_default ) );
					
				
					$field_args = array(
											'type'      	=> $type,
											'id'        	=> $id,
											'description'   => $description,
											'std'       	=> $std,
											'choices'   	=> $choices,
											'label_for' 	=> $id,
											'class'     	=> $class
										);
				
					if ( $type == 'checkbox' )
					{
						$this->checkboxes[] = $id;
					}
						
					add_settings_field( $id, $title, array( $this, 'framework_display_setting' ),  $this->uniqueID . '___options', $section, $field_args );
			
			}





			
			/**
			 *		Inserts a string inside another string
			 *		 
			 * 		@str  		Long string
			 * 		@search  	String to search for
			 * 		@insert  	Insert text
			 * 		@return		string
			 */			
			public	function	str_insert($str, $search, $insert) 
			{
					$index 					=	strpos($str, $search);
					
					if($index === false) 
					{
							    return $str;
					}
					
					$string_to_return		=	substr_replace($str, $search.$insert, $index, strlen($search)); 
					
					return	$string_to_return; 
			}
			
			
	

			/**
			 *		A helper fucntion to output PHP arrays in a human readable format
			 *		 
			 * 		@array  	array to be output in a styled Print_r
			 * 		@return		string
			 */				
			public	function	print_r_nicely($array)
			{
					echo "<pre style='	border:				1px solid grey; 
										padding:			5px; 
										margin:				20px 20px; 
										background-color:	white; 
										max-width:			100%; 
										overflow:			auto; 
										max-height:			400px; 
										color:				#666;
										font-size:			14px; 
										font-family:		monaco, courier;'>";
					
					print_r($array);
					
					echo "</pre>";
			}
		
		
			/**
			 *		A simple loader test function
			 *		 
			 * 		@return		string
			 */					    
		    public	function	test_class_inheritance()
		    {
			    	return		"The Eagle has landed";
		    }
	}


	$kevinjohn_gallagher	=	new 	kevinjohn_gallagher();
