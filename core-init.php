<?php 
/*
*
*	***** Link Whisper Bot *****
*
*	This file initializes all LWB Core components
*	
*/
// If this file is called directly, abort. //
if ( ! defined( 'WPINC' ) ) {die;} // end if
// Define Our Constants
define('LWB_CORE_INC',dirname( __FILE__ ).'/assets/inc/');
define('LWB_CORE_IMG',plugins_url( 'assets/img/', __FILE__ ));
define('LWB_CORE_CSS',plugins_url( 'assets/css/', __FILE__ ));
define('LWB_CORE_JS',plugins_url( 'assets/js/', __FILE__ ));
/*
*
*  Register CSS
*
*/
function lwb_register_core_css(){
wp_enqueue_style('lwb-core', LWB_CORE_CSS . 'lwb-core.css',null,time(),'all');
};
add_action( 'wp_enqueue_scripts', 'lwb_register_core_css' );    
/*
*
*  Register JS/Jquery Ready
*
*/
function lwb_register_core_js(){
// Register Core Plugin JS	
wp_enqueue_script('lwb-core', LWB_CORE_JS . 'lwb-core.js','jquery',time(),true);
};
add_action( 'wp_enqueue_scripts', 'lwb_register_core_js' );    
/*
*
*  Includes
*
*/ 
// Load the Functions
if ( file_exists( LWB_CORE_INC . 'lwb-core-functions.php' ) ) {
	require_once LWB_CORE_INC . 'lwb-core-functions.php';
}     
// Load the ajax Request
if ( file_exists( LWB_CORE_INC . 'lwb-ajax-request.php' ) ) {
	require_once LWB_CORE_INC . 'lwb-ajax-request.php';
} 
// Load the Shortcodes
if ( file_exists( LWB_CORE_INC . 'lwb-shortcodes.php' ) ) {
	require_once LWB_CORE_INC . 'lwb-shortcodes.php';
}