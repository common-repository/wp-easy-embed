<?php
/*
Plugin Name: WP Easy Embed
Plugin URI: 
Version: 1.2
Description: WP Easy Embed allows you to embed videos, images and such via OEmbed service without manually editing the post (or page) 
Author: ibarmin
Author URI: http://wordpress.org/support/profile/ibarmin
Text Domain: wp-easy-embed
License: GPL2
*/
/*  Copyright 2011 Igor Barmin (email : ibarmin at gmail dot com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( is_admin() ){
	add_action('init', array('WPEasyEmbed', 'init'));
	register_activation_hook(__FILE__, array('WPEasyEmbed', 'activate'));
	register_deactivation_hook(__FILE__, array('WPEasyEmbed', 'deactivate'));
	add_action( 'wpmu_new_blog', array('WPEasyEmbed', 'activateNewBlog')); 	

class WPEasyEmbed {
	protected static $instance = null;
	protected static $ajaxActions = array();
	
	private function __construct() {}
	private function __clone() {}

	public static function getInstance() {
        if ( is_null(self::$instance) ) {
            self::$instance = new self;
        }
        return self::$instance;
    }
	
	/**
	 * 
	 * setting up all required hooks
	 */
	public static function init(){
		self::loadTranslation();
		do_action('wp_easy_embed_init');
		EasyEmbedSettings::init();
		self::loadAjaxActions();
		self::loadComponents();
		$buttons = new EasyEmbedButtons();
		$buttons->init();
	}

	protected static function loadTranslation() {
		load_plugin_textdomain(EasyEmbedConfig::getTextDomain(), false, EasyEmbedConfig::get('i18n_path'));
	}
	
	/**
	 * 
	 * plugin activation hook
	 */
	public static function activate() {
		if(
			function_exists('is_multisite')	&& is_multisite()
			&& isset($_GET['networkwide']) && ($_GET['networkwide'] == 1)
		){
			$oldBlogId = self::getBlogId();
			$blogIds = self::getBlogIds();
			foreach ($blogIds as $blogId) {
				switch_to_blog($blogId);
				EasyEmbedSettings::activate();
			}
			switch_to_blog($oldBlogId);
			return;
		} 
		EasyEmbedSettings::activate();
	}
	
	/**
	 * 
	 * plugin deactivation hook
	 */
	public static function deactivate() {
		if(
			function_exists('is_multisite')	&& is_multisite()
			&& isset($_GET['networkwide']) && ($_GET['networkwide'] == 1)
		){
			$oldBlogId = self::getBlogId();
			$blogIds = self::getBlogIds();
			foreach ($blogIds as $blogId) {
				switch_to_blog($blogId);
				EasyEmbedSettings::deactivate();
			}
			switch_to_blog($oldBlogId);
			return;
		} 
		EasyEmbedSettings::deactivate();
	}
	
	public static function activateNewBlog($blogId){
		if ( ! function_exists( 'is_plugin_active_for_network' ) ){
   			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}

 		if (is_plugin_active_for_network('wp-easy-embed/wp-easy-embed.php')) {
			$oldBlogId = self::getBlogId();
			switch_to_blog($blogId);
			EasyEmbedSettings::activate();
			switch_to_blog($oldBlogId);
		}		
	}
	
	protected static function getBlogIds(){
		global $wpdb;
		return $wpdb->get_col($wpdb->prepare("SELECT blog_id FROM $wpdb->blogs"));
	}
	
	protected static function getBlogId(){
		global $wpdb;
		return $wpdb->blogid;
	}
	
	public static function includeSrc($relPath){
		require_once( self::getIncludeDir() . $relPath);
	}
	
	protected static function getIncludeDir(){
		return dirname(__FILE__) . '/inc';
	}
		
	protected static function loadComponents(){
		self::loadSourceFiles('component');
	}
	
	protected static function loadAjaxActions(){
		self::loadSourceFiles('ajax');
	}
	
	protected static function loadSourceFiles($fromDir) {
		$fromDir = '/' . $fromDir . '/';
		$dir = dir(self::getIncludeDir() . $fromDir);
		
		while (false !== ($entry = $dir->read())) {
			if(self::isSourceFile($entry)){
   				self::includeSrc($fromDir . $entry);
			}
		}
		$dir->close();
	}
	
	protected static function isSourceFile($entry){
		return strpos($entry, '.php') !== false;
	}
	
	public static function addAjaxAction($action){
		$action->init();
		self::$ajaxActions[] = $action;
	}
	
	public static function _pluginsUrl($url) {
		return plugins_url($url, __FILE__);
	}
}
	WPEasyEmbed::includeSrc('/class-easyembedconfig.php');
	WPEasyEmbed::includeSrc('/class-easyembedsetting.php' );
	WPEasyEmbed::includeSrc('/class-easyembedsettings.php' );
	WPEasyEmbed::includeSrc('/class-ajaxaction.php' );
	WPEasyEmbed::includeSrc('/class-component.php' );
}//is_admin()
?>