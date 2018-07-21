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

if( ! class_exists('WordpressWidgetGallery')){
	class WordpressWidgetGallery {
		public $plugin;

		public $gallery_table;
		public $image_entry_table;


		function register(){
			add_action('admin_enqueue_scripts',array($this,'enqueue'));
			add_action('admin_menu',array($this, 'add_admin_pages'));
			add_action( 'admin_post_add_gallery', array($this,'admin_create_new_gallery') );
			add_action( 'admin_post_del_gallery', array($this,'admin_delete_gallery') );
			add_action( 'admin_post_add_image', array($this,'admin_add_new_image') );
			add_action( 'admin_post_upd_gallery', array($this,'admin_update_gallery'));
			add_action( 'admin_post_upd_image', array($this,'admin_update_image') );
			add_action( 'admin_post_rep_image', array($this,'admin_replace_image') );
			add_action( 'admin_post_del_image', array($this,'admin_delete_image') );

			add_filter( 'plugin_action_links_'.$this->plugin, array( $this, 'settings_link' ) );
		}


		function admin_delete_gallery(){
			global $wpdb;
			$gallery_table = $wpdb->prefix . "wgp_gallery";
 			$image_entry_table = $wpdb->prefix . "wgp_image_entry"; 
			$id = $_REQUEST['id'];
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			$wpdb->query("DELETE FROM `$gallery_table` WHERE id=$id");
			$wpdb->query("DELETE FROM `$image_entry_table` WHERE gallery_id=$id");

			status_header(200);
			die('');
		}

		function admin_delete_image(){
			global $wpdb;
 			$image_entry_table = $wpdb->prefix . "wgp_image_entry"; 
			$id = $_REQUEST['id'];
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			$wpdb->query("DELETE FROM `$image_entry_table` WHERE id=$id");

			status_header(200);
			die('');
		}
		function admin_create_new_gallery(){
			global $wpdb;
			$gallery_table = $wpdb->prefix . "wgp_gallery";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			$wpdb->query("INSERT INTO `$gallery_table` (`name`) VALUES ('new gallery')");

			status_header(200);
			die('');
		}
		function admin_add_new_image(){
			global $wpdb;
 			$image_entry_table = $wpdb->prefix . "wgp_image_entry"; 
			$id = $_REQUEST['id'];
			$path = $_REQUEST['path'];
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			//echo  "INSERT INTO `$image_entry_table`(`gallery_id`,`path`) VALUES ($id,'$path')";
			$wpdb->query("INSERT INTO `$image_entry_table`(`gallery_id`,`path`) VALUES ($id,'$path')");
			status_header(200);
			die(''); 
		}
		function admin_replace_image(){
			
			global $wpdb;
 			$image_entry_table = $wpdb->prefix . "wgp_image_entry"; 
			$id = $_REQUEST['id'];
			$path = $_REQUEST['path'];
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

			//echo  "INSERT INTO `$image_entry_table`(`gallery_id`,`path`) VALUES ($id,'$path')";
			$wpdb->query("UPDATE `$image_entry_table` SET `path`='$path' WHERE `id`=$id");
			status_header(200);
			die('');  
		}
		function admin_update_gallery(){
			
			global $wpdb;
 			$gallery_table = $wpdb->prefix . "wgp_gallery";
			$id = $_REQUEST['id'];
			$name = $_REQUEST['name'];

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			echo "UPDATE `$gallery_table` SET `name`='$name' WHERE `id`=$id";
			$wpdb->query("UPDATE `$gallery_table` SET `name`='$name' WHERE `id`=$id");
			status_header(200);
			die('');  
		}
		function admin_update_image(){
			
			global $wpdb;
 			$image_entry_table = $wpdb->prefix . "wgp_image_entry"; 
			$gid = $_REQUEST['g_id'];
			$iid = $_REQUEST['i_id'];
			$caption = $_REQUEST['change_image_entry_caption'];
			$sub_caption = $_REQUEST['change_image_entry_sub_caption'];
			$url = $_REQUEST['change_image_entry_url'];

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

			//echo  "INSERT INTO `$image_entry_table`(`gallery_id`,`path`) VALUES ($id,'$path')";
			$wpdb->query("UPDATE `$image_entry_table` SET `caption`='$caption',`sub_caption`='$sub_caption',`url`='$url' WHERE `id`=$iid");
			status_header(200);
			die('');  
		}
		function __construct(){
			$this->plugin = plugin_basename( __FILE__ );
		}
		function activate(){
			global $wpdb;	
 			$gallery_table = $wpdb->prefix . "wgp_gallery";
 			$image_entry_table = $wpdb->prefix . "wgp_image_entry"; 

			$charset_collate = $wpdb->get_charset_collate();

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

 			if($wpdb->get_var("show tables like '$gallery_table'") != $gallery_table) {
 				$sql = "CREATE TABLE $gallery_table (
				  `id` int NOT NULL AUTO_INCREMENT,
				  `name` text NOT NULL,
				  `text` text,
				  PRIMARY KEY  (id)
				) $charset_collate;";

				dbDelta( $sql );
 			}
			if($wpdb->get_var("show tables like '$image_entry_table'") != $image_entry_table) {

				$sql = "CREATE TABLE $image_entry_table (
				  `id` int NOT NULL AUTO_INCREMENT,
				  `gallery_id`int NOT NULL,
				  `path` text not NULL,
				  `caption` text,
				  `sub_caption` text,
				  `url` text,
				  PRIMARY KEY  (id)
				) $charset_collate;";

				dbDelta( $sql );

			}

			flush_rewrite_rules();
		}

		function deactivate(){

			flush_rewrite_rules();
		}
		public function settings_link( $links ) {
			$settings_link = '<a href="admin.php?page=wordpress_widget_plugin">Settings</a>';
			array_push( $links, $settings_link );
			return $links;
		}
		public function add_admin_pages(){
			add_menu_page(
				'Widget Galleries',
				'Widget Galleries',
				'manage_options',
				'wordpress_widget_plugin',
				array($this,'admin_index'),
				'dashicons-format-gallery',
				10
			);
		}
		public function admin_index(){
			require_once plugin_dir_path( __FILE__ ) . 'templates/admin.php';
		}
		function enqueue($hook) {
		// enqueue all our scripts
			if('toplevel_page_wordpress_widget_plugin' != $hook) return;
			wp_enqueue_style( 'wordpress-widget-gallery', plugins_url( '/assets/style.css', __FILE__ ) );
			wp_enqueue_script( 'wordpress-widget-gallery', plugins_url( '/assets/code.js', __FILE__ ) );
		}
	}
	$wordpressWidgetGallery = new WordpressWidgetGallery();
	$wordpressWidgetGallery->register();


	register_activation_hook(__FILE__,   array($wordpressWidgetGallery,'activate'));
	register_deactivation_hook(__FILE__,   array($wordpressWidgetGallery,'deactivate'));
}