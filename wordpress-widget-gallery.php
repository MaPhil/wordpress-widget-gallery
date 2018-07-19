<?php
/**
* @package wordpress-widget-gallery
*/
/*
Plugin Name: wordpress-widget-gallery
Plugin URI: https://varion.de
Description: plugin to manage galleries for the widget theme
Version: 1.0.0
Author: Philipp Mainz
Author URI: https://varion.de
License: MIT
Text Domain: wordpress-widget-gallery
*/

if( ! defined('ABSPATH') ){
	die;
}


/**
 * 
 */
class WordpressWidgetGallery {
	
	function __construct(){
		
	}
	function activate(){

	}
	function deactivate(){

	}

}
if(class_exists('WordpressWidgetGallery')){
	$wordpressWidgetGallery = new WordpressWidgetGallery();
}

register_activation_hook(__FILE__,   array($wordpressWidgetGallery,'activate'));
register_deactivation_hook(__FILE__,   array($wordpressWidgetGallery,'deactivate'));
