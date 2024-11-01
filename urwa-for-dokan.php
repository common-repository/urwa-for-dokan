<?php

/*
Plugin Name: URWA For Dokan
Description: Selectively display widgets to logged in users based on standard Dokan user roles. 
Version: 1.0
Requires at least: 3.9
Tested up to: 4.3.1
Stable Tag: 1.0
Author: Rob Smelik
Author URI: http://www.robsmelik.com
License: GPLv2
Copyright: Rob Smelik
*/
 
// SECURITY: This line exists for security reasons to keep things locked down.
 
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


// REGISTER SIDEBARS (AKA Widget Areas)
 
function register_dokan_user_sidebar(){
		

		// Register Dokan Customer sidebar

		register_sidebar(array(
			'name' => 'Users - Dokan Customer',
			'id'   => 'urwa-dokan-customer-widgets',
			'description'   => __( 'Widgets placed in this widget area only visible to Dokan Customers who are logged in.' ),
			'before_widget' => '<div id="urwa-dokan-customer" class="widget">',
			'after_widget'  => '</div>',
			'before_title' => '<h3 class="widgettitle">', 
			'after_title' => '</h3>', 
		));
		
		
		// Register Dokan Seller sidebar

		register_sidebar(array(
			'name' => 'Users - Dokan Seller',
			'id'   => 'urwa-dokan-seller-widgets',
			'description'   => __( 'Widgets placed in this widget area only visible to Dokan Sellers who are logged in.' ),
			'before_widget' => '<div id="urwa-dokan-seller" class="widget">',
			'after_widget'  => '</div>',
			'before_title' => '<h3 class="widgettitle">', 
			'after_title' => '</h3>', 
		));
		
		// Register Dokan Administrator sidebar

		register_sidebar(array(
			'name' => 'Users - Dokan Administrator',
			'id'   => 'urwa-dokan-administrator-widgets',
			'description'   => __( 'Widgets placed in this widget area only visible to Dokan Administrators who are logged in.' ),
			'before_widget' => '<div id="urwa-dokan-administrator" class="widget">',
			'after_widget'  => '</div>',
			'before_title' => '<h3 class="widgettitle">', 
			'after_title' => '</h3>', 
		));
		
		
}

add_action( 'widgets_init', 'register_dokan_user_sidebar' );


// REGISTER SHORTCODES

// Shortcode 1: Display sidebars based on Specific user roles

add_shortcode('dokan-user-role-widget-areas', 'shortcode_dokan_user_role_widget_areas');

// Creates the front-end display of the User Role Widget Areas
// This is where the magic happens

function shortcode_dokan_user_role_widget_areas(  ) {

if ( current_user_can( 'update_core' ) ) { //only Dokan Administrators can see this
   if ( is_active_sidebar( 'urwa-dokan-administrator-widgets' ) ) {
   dynamic_sidebar( 'urwa-dokan-administrator-widgets' );
   }
} 
elseif ( dokan_is_user_seller( get_current_user_id() ) ) { //only Dokan Sellers can see this
   if ( is_active_sidebar( 'urwa-dokan-seller-widgets' ) ) {
   dynamic_sidebar( 'urwa-dokan-seller-widgets' );
   }
} 

elseif ( is_user_logged_in() ) { //only Dokan Customers can see this
   if ( is_active_sidebar( 'urwa-dokan-customer-widgets' ) ) {
   dynamic_sidebar( 'urwa-dokan-customer-widgets' );
   }
} 

else {  //returns no widget content if none of the contitions above are met
	echo ''; 
}
}
		
add_filter('widget_text', 'do_shortcode');


// REGISTER WIDGET

// Display sidebars based on Dokan user roles

class urwa_dokan_widget extends WP_Widget {
	
function __construct() {
parent::__construct(
// Base ID of the widget
'urwa_dokan_widget', 

// Widget name as it appears in the UI
__('Dokan Users by Role', 'urwa_dokan_widget_domain'), 

// Widget description
array( 'description' => __( 'Place this widget in any existing NON-USER widget area to display Dokan user role widget areas.', 'urwa_widget_domain' ), ) 
);
}

// Creates the front-end display of the Dokan User Role Widget Areas
// This is where the magic happens

public function widget(  ) {

if ( current_user_can( 'update_core' ) ) { //only Dokan Administrators can see this
   if ( is_active_sidebar( 'urwa-dokan-administrator-widgets' ) ) {
   dynamic_sidebar( 'urwa-dokan-administrator-widgets' );
   }
} 
elseif ( dokan_is_user_seller( get_current_user_id() ) ) { //only Dokan Sellers can see this
   if ( is_active_sidebar( 'urwa-dokan-seller-widgets' ) ) {
   dynamic_sidebar( 'urwa-dokan-seller-widgets' );
   }
} 


elseif ( is_user_logged_in() ) { //only Dokan Customers can see this
   if ( is_active_sidebar( 'urwa-dokan-customer-widgets' ) ) {
   dynamic_sidebar( 'urwa-dokan-customer-widgets' );
   }
} 

else {  //returns no widget content if none of the contitions above are met
	echo ''; 
}
}
		
} // Class urwa_dokan_widget ends here

// Register and load the widget

function urwa_load_dokan_widget() {
	register_widget( 'urwa_dokan_widget' );
}
add_action( 'widgets_init', 'urwa_load_dokan_widget' );