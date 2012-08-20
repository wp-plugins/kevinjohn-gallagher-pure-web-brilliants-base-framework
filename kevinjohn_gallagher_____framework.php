<?php
/*
	Plugin Name: 			Kevinjohn Gallagher:  _PWB Base Framework
	Description: 			Framework required for all Pure Web Brilliant plug-ins, themes and CMS features.
	Version: 				2.4
	Author: 				Kevinjohn Gallagher
	Author URI: 			http://kevinjohngallagher.com/
	
	Contributors:			kevinjohngallagher, purewebbrilliant 
	Donate link:			http://kevinjohngallagher.com/
	Tags: 					kevinjohn gallagher, pure web brilliant, framework, cms, simple, multisite
	Requires at least:		3.0
	Tested up to: 			3.5
	Stable tag: 			2.4
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
 *	@version 				2.4
 *	@author 				Kevinjohn Gallagher <wordpress@kevinjohngallagher.com>
 *	@copyright 				Copyright (c) 2012, Kevinjohn Gallagher
 *	@link 					http://kevinjohngallagher.com
 *	@license 				http://www.gnu.org/licenses/gpl-3.0.txt
 *
 *
 */

 	if ( ! defined( 'ABSPATH' ) )
 	{ 
 			die( 'Direct access not permitted.' ); 
 	}
 	


	define( '_KEVINJOHN_GALLAGHER_FRAMEWORK', '2.4' );
	

	
	class kevinjohn_gallagher 
	{
	
			/*
			**
			**		VARIABLES
			**
			*/
	
			const PM		=	'_kevinjohn_gallagher_framework';
			
			var					$instance;
			var					$uniqueID;
			var 				$plugin_name;										
			var					$plugin_dir;
			var					$plugin_url;			
			var 				$plugin_options;
			var 				$child_settings_array;
			var 				$http_or_https;

			var 				$post_custom_fields;
			
			
			
			
		
			public	function	__construct() 
			{
					$this->instance 				=	&$this;
					$this->uniqueID 				=	self::PM;
					$this->plugin_dir				=	plugin_dir_path(__FILE__);	
					$this->plugin_url				=	plugin_dir_url(__FILE__);
					
					$this->plugin_name				=	"Kevinjohn Gallagher: Pure Web Brilliant's Framework";
					
					
					add_action( 'init', 				array( 	&$this, 	'init' ) );
			//		add_action( 'admin_init',			array( 	&$this, 	'framework_admin_setup' ) );
					add_action(	'admin_init',			array( 	&$this, 	'admin_init_register_settings'), 100);
					add_action( 'admin_menu', 			array(	&$this, 	'framework_admin_menu_setup' ) );

					/*					
					add_action( 'admin_init',			array(	&$this,	'framework_admin_define_child_settings_sections' ) );
					add_action( 'admin_init',			array(	&$this,	'framework_admin_define_child_settings_array' ) );	
					*/				
										
			}


			public function init() 
			{
					
					$this->http_or_https			=	is_ssl() ? 'https:' : 'http:';
					$this->plugin_options			=	get_option($this->uniqueID . '___options');
					
					
					add_action( 'admin_init',			array( 	&$this, 	'framework_admin_setup' ) );
					
					add_filter( 'wp',					array(	&$this,	'get_post_custom_fields' ), 100 );				
					add_action(	'wp_head',				array(	&$this,	'framework_print_plugin_name'));
					add_action(	'login_head',			array(	&$this,	'framework_print_plugin_name'));
					add_action(	'admin_head',			array(	&$this,	'framework_print_plugin_name'));
					
					add_action(	'admin_init',			array( 	&$this, 'framework_on_action_admin_init'),	99);


					
					if( method_exists( $this, 'define_child_settings_sections') )
					{
							add_action(	'admin_init',		array( $this, 'define_child_settings_sections'),	1);
					}
					

					if( method_exists( $this, 'define_child_settings_array') )
					{
							add_action(	'admin_init',		array( $this, 'define_child_settings_array'),		2);							
					}					
					

					if( method_exists( $this, 'add_plugin_to_menu') )
					{
							add_action( 'admin_menu',		array( $this, 'add_plugin_to_menu'));
					}	

					
			}
			
			
			
			public 	function 	framework_capability_test( $capability = 'manage_options' )
			{
					if( empty( $capability ) )
					{
							$capability 	= 	'manage_options';
					}
										
					
				
					if ( ! current_user_can( $capability ) )
					{
							die( 'Sorry, but you are not allowed to load this page. ' );				
					}
				
			}


			public 	function 	framework_on_action_admin_init()
			{
					$this->child_settings_sections 		=	apply_filters( 	'kjg_pwb_hook_child_settings_sections_'. $this->uniqueID,	 	$this->child_settings_sections);
					$this->child_settings_array			= 	apply_filters( 	'kjg_pwb_hook_child_settings_array_'. $this->uniqueID, 			$this->child_settings_array);
					
			}


			
			
			public 	function framework_admin_setup()
			{
						global $pagenow;
						
						if ( 'admin.php' == $pagenow ) 
						{
								
								if( $this->is_page_mine() )
								{
								
										$this->framework_capability_test();
								
										wp_enqueue_script(	'media-upload'	); 		
										wp_enqueue_script(	'thickbox'	);
										wp_enqueue_style(	'thickbox'	);
										
														
										wp_enqueue_style(	'pwb_framework', 
															plugins_url(
																			'_stylesheets/kevinjohn_gallagher_____framework.css', 
																			__FILE__
																		),
															array(), 
															'1.0', 
															'all'
														);
								}
						}
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
			
					if( $this->plugin_page_title )
					{
							$page_title					=	$this->plugin_page_title;
					}

					if( $this->plugin_menu_title )
					{
							$menu_title					=	$this->plugin_menu_title;
					}


					if( $this->plugin_slug )
					{
							$menu_slug					=	$this->plugin_slug;
					}
			
			
					add_submenu_page(	'kevinjohn-gallagher-framework', 
										$page_title .' :: Kevinjohn Gallagher Framework', 
										$menu_title, 
										'edit_pages', 
										'kjg-pwb-'. $menu_slug, 
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
				//	Truly hate this, but then I never pretended to be a good developer
				//
				//	Lets test if this is our page
				//	Seems hacky...
				//				

					global	$pagenow;
					
					
					if( 'options.php' == $pagenow )
					{
					
							if( isset( $_SERVER['HTTP_REFERER'] ) )
							{
									$test_string_on_this	=	$_SERVER['HTTP_REFERER'];
									
							}	
							elseif( isset( $_REQUEST['_wp_http_referer'] ) ) 
							{
								
									$test_string_on_this	=	$_REQUEST['_wp_http_referer'];
							}
						
					} else {
						
							$test_string_on_this 		= 		$_SERVER['QUERY_STRING'];
					}
					
					

					if( isset( $test_string_on_this )  && isset( $this->plugin_slug ) )
					{
							if( strpos( $test_string_on_this , $this->plugin_slug ) !== false )
							{
									$this->child_register_settings();	
							}
					}						
				
			
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
					
					
					foreach ( $this->child_settings_array as $id => $setting ) 
					{
							$setting['id'] 		= 	$id;
							$this->framework_create_setting( $setting );
					}
			}			
			
			

			/**
			 *		Outputs settings page
			 *
			 *		I know this seems weird, the fact that this doesn't do anything
			 *		but the function needs a valid callback, and I don't need to process that right now 
			 *		 
			 */			
			public 	function 	framework_display_section($args)
			{
			
			}

			
			/**
			 *		Valid Callback
			 *
			 *		I know this seems weird, the fact that this doesn't do anything
			 *		but the function needs a valid callback, and I don't need to process that right now 
			 *		 
			 */			
			public 	function 	framework_valid_callback($args)
			{
			
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
					echo	"<div 	id='kjg-pwb-framework-page' class='wrap'>";
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
					do_action('kjg_framework_validate_options_action_pre');
					
					

				
					$input 	= 	apply_filters( 'kjg_framework_validate_options_filter', 	$input 	);
					
			
					return $input;
					
					do_action('kjg_framework_validate_options_action_apres');
					
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
					
					//
					//	This is how we'll handle extenstions while in beta.
					//
					if( $args['type'] == 'wp_image_upload' )
					{

							if( class_exists('kevinjohn_gallagher___image_controls') )
							{
								
									kevinjohn_gallagher___image_controls::framework_display_setting_type_wp_image_upload($args, $options);
								
							} else {
								
									$args['description'] = "Sorry, this requires the <strong> Image Control </strong> plug-in.  ";
							}
												
						
					} else {
						
							call_user_func( array($this, 'framework_display_setting_type_'. $args['type']) , $args, $options);				
						
					}


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

//					echo 	"<br />";
					echo	'<input 
									type="checkbox"					
									class="checkbox ' . $field_class . '" 
									name="'. $this->uniqueID . '___options[' . $id . ']"
									id="' . $id . '" 
									value="1" 
									'. checked( $options[$id], 1, false ) .'
									style="margin-top:8px"
								/>';
					echo 	"<br />";
					echo 	"<br />";
					
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
			 *		Outputs only description text for a WP settings page
			 *		 
			 * 		@args  		array
			 * 		@options	array
			 */
			public function 	framework_display_setting_type_text_only($args, $options)
			{
					/*
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
					*/
					
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
			 *		Sets the custom variables / meta data for this $post
			 *		 
			 */			
			public 	function 	get_post_custom_fields()
			{
			
					if ( empty($post) ) 
					{
							global $post;
							
							if ( !isset($post) )
							{
								return 	false;
							}
					}			
			
					$this->post_custom_fields 		= 	get_post_custom($post->ID);
					
					return	true;
			}




			/**
			 *		Gets a custom field variable
			 *		 
			 * 		@post_id  		$post->ID
			 * 		@meta_key  		meta key to save under
			 * 		@meta_value  	meta value to save
			 * 		
			 */						

			public 	function 	framework_get_value( $post_id = '', $meta_key ) 
			{
					if ( empty($post_id) ) 
					{
							global $post;
							
							if ( isset($post) )
							{
									$post_id 	= $post->ID;
							} else { 
							
									return		false;
							}
					}
					
					if( empty( $this->post_custom_fields ) )
					{
							$this->get_post_custom_fields();						
					}


					if ( !empty($this->post_custom_fields[$meta_key][0]) )
					{
							return	maybe_unserialize( $this->post_custom_fields[$meta_key][0] );
					
					} else {
					
							return	false;
					}
			}
			
			
			
			
			/**
			 *		Sets a custom field variable
			 *		 
			 * 		@post_id  		$post->ID
			 * 		@meta_key  		meta key to save under
			 * 		@meta_value  	meta value to save
			 * 		
			 */						
			public 	function 	framework_set_value( $post_id='', $meta_key, $meta_value  ) 
			{
					
					if ( empty($post_id) ) 
					{
							global $post;
							
							if ( isset($post) )
							{
								$post_id = $post->ID;
							} else { 
								return false;
							}
					}
					
					update_post_meta( $post_id, '_'. $this->plugin_name .'_'. $meta_key, $meta_value );
			}
			
			

			/**
			 *		Outputs the plugin name.
			 *		Commented out to not appear on the front end
			 *		No link back to owner, merely for information.
			 *		 
			 */						
			public 	function 	framework_print_plugin_name() 
			{
					
					echo	"\n";
				//	echo	"\n\n";
					echo	"<!--	" . $this->plugin_name . " 	-->";
				//	echo	"<!--	". __FILE__ ."	-->";					
				//	echo	"\n\n";
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
			 *		So we don't load our scripts on other plugin pages.
			 *		 
			 * 		@string  	test for a specific slug 
			 * 		@return		boolean
			 */				

			public 	function 	is_page_mine($this_is_me='')
			{
					if( strpos( $_SERVER['QUERY_STRING'] , "kjg" ) !== false || strpos( $_SERVER['QUERY_STRING'] , "pwb" ) !== false || strpos( $_SERVER['QUERY_STRING'] , "kevinjohn" ) !== false )
					{
							$is_it_mine	=	true;
							
					}	elseif( !empty( $this_is_me ) ) {
					
							if( strpos( $_SERVER['QUERY_STRING'] , $this_is_me ) !== false )
							{
									$is_it_mine	=	true;					
							}
					}
					
					return 	$is_it_mine;
			}


	

			/**
			 *		A helper fucntion to output PHP arrays in a human readable format
			 *		 
			 * 		@array  	array to be output in a styled Print_r
			 * 		@return		string
			 */				
			public	function	print_r_nicely($array)
			{
					echo "<pre 	class='print_r_nicely'
								style='	border:				1px solid grey; 
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
		    
		    
			/**
			 *		A simple loader test function
			 *		 
			 * 		@return		string
			 */					    
		    
			public 	function 	is_this_plugin_loaded()
			{
				return	true;
				
			}

		    
	}


	$kevinjohn_gallagher	=	new 	kevinjohn_gallagher();




	/**
	 *		the_content				=		get_the_content	
	 *		the_post 				= 		get_the_post
	 *		the_title 				= 		get_the_title
	 *		the_category			=		get_the_category
	 *		the_excerpt				=		get_the_excerpt
	 *		the_post_thumbnail		=		get_the_post_thumbnail
	 *		the_permalink			=		get_permalink	??
	 *
	 *		Where is the "_the" ? Come now...
	 * 		 
	 * 		@return		boolean
	 */	
	
	if( !function_exists('get_the_permalink') )
	{
			function 	get_the_permalink()
			{
			
					return get_permalink();
			}
	}
