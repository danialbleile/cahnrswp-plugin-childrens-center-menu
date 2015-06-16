<?php
/**
* Plugin Name: CAHNRSWP Chilren's Center Menu
* Plugin URI:  http://cahnrs.wsu.edu/communications/
* Description: Adds Dynamic Menu Feature
* Version:     1.0.0
* Author:      CAHNRS Communications, Danial Bleile
* Author URI:  http://cahnrs.wsu.edu/communications/
* License:     Copyright Washington State University
* License URI: http://copyright.wsu.edu
*/

class CWPCC_Menu {
	
	public function __construct(){
		
		add_shortcode( 'ccmenu' , array( $this , 'ccmenu' ) );
		
		add_action( 'wp_enqueue_scripts', array( $this , 'cwpmenu_scripts' ) ); 
		
		//if ( isset( $_GET['ccmenu'] ) ){
		
			//add_action( 'template_include' , array( $this , 'get_json') );
		
		//} // end if
		
	}
	
	public function cwpmenu_scripts(){
		
		wp_enqueue_style( 'ccmenu-css', plugin_dir_url( __FILE__ ) . 'ccmenu.css');
		
	}
	
	//public function get_json( $template ){
		
		//$template = plugin_dir_url( __FILE__ ) . 'ajax.php';
		
		//return $template;
		
	//}
	
	public function ccmenu( $atts ){
		
		$response = wp_remote_get( 'http://dining.wsu.edu/umbraco/surface/CCMenu' );
		
		$body = wp_remote_retrieve_body($response);
		
		$data = json_decode( $body , true );
		
		$tabs = '<nav id="ccmenu-tabs">';
		
		$menu = '';
		
		
		foreach( $data as $index => $day_data ){
			
			$meals = '';
			
			$tab_class = ( 0 == $index )? 'active':'';
			
			$tabs .= '<a href="#" class="' . $tab_class . '">' . $day_data['Day'] . '</a>';
			
			$menu_class = ( 0 == $index )? ' active':''; 
			
			$menu .= '<div class="ccmenu-set' . $menu_class . '">'; 
			
			foreach( $day_data['Meals'] as $meal ){
				
				$meals .= '<div class="ccmenu-meal">';
				
					$meals .= '<h4>' . $meal['MealName'] . '</h4>';
					
					foreach( $meal['Items'] as $item ){
						
						$meals .= '<ul class="ccmenu-meal-item">';
						
							$meals .= '<li>' . $item['ItemName'];
							
							$meals .= '<ul>';
							
							foreach( $item['Traits'] as $trait ){
								
								$meals .= '<li class="' . $trait['TraitType'] . '">';
								
									$meals .= $trait['Trait'];
								
								$meals .= '</li>';
								
							} // end foreach
							
							$meals .= '</ul></li>';
						
						$meals .= '</ul>';
						
					} // end foreach
				
				$meals .= '</div>';
				
			} // end foreach
			
			$menu .= $meals;
			
			$menu .= '</div>';
			
		} // end foreach
		
		$tabs .= '</nav>';
		
		ob_start();
		
		include 'ccmenu_plugin.js';
		
		$script = '<script type="text/javascript">' . ob_get_clean() . '</script>';
		
		return '<div id="ccmenu">' . $tabs . $menu . $script . '</div>';
		
		//ob_start();
		
		//include 'ccmenu_plugin.js';
		
		//return '<script type="text/javascript">' . ob_get_clean() . '</script>';
		
	}
	
}

$cwpcc_menu = new CWPCC_Menu(); 