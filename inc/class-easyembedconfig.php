<?php
class EasyEmbedConfig {
	public static function get($key){
		static $config = false;
		if($config === false){
			$config = array(
				'option_page_title' => __('WP Easy Embed', self::getTextDomain())
				, 'menu_title' => __('WP Easy Embed', self::getTextDomain())
				, 'option_name' => 'wp_easy_embed'
				, 'settings_section_name' => 'wp_easy_embed_main'
				, 'menu_capability' => 'manage_options'
				, 'button_url' => WPEasyEmbed::_pluginsUrl('images/embed-button.png')
				, 'loading_icon_url' => admin_url('images/loading.gif')
				, 'i18n_path' => dirname(plugin_basename(__FILE__)) . '/languages'
				, 'show_in_both_modes' => false
				, 'supported_attributes' => array('url', 'maxwidth', 'maxheight')
				, 'supported_providers' => array('vimeo')
				, 'supported_attributes_vimeo' => array(
					'width' => array('type' => 'text', 'value' => '', 'title' => __('The exact width of the video', self::getTextDomain()))
					, 'height' => array('type' => 'text', 'value' => '', 'title' => __('The exact height of the video', self::getTextDomain()))
					, 'byline' => array('type' => 'checkbox', 'value' => true, 'title' => __('Show the byline on the video', self::getTextDomain()))
					, 'title' => array('type' => 'checkbox', 'value' => true, 'title' => __('Show the title on the video', self::getTextDomain()))
					, 'portrait' => array('type' => 'checkbox', 'value' => true, 'title' => __('Show the user\'s portrait on the video', self::getTextDomain()))
					, 'color' => array('type' => 'text', 'value' => '', title => __('Specify the color of the video controls', self::getTextDomain()))
					, 'autoplay' => array('type' => 'checkbox', 'value' => false, 'title' => __('Automatically start playback of the video', self::getTextDomain()))
					, 'loop' => array('type' => 'checkbox', 'value' => false, 'title' => __('Play the video again automatically when it reaches the end', self::getTextDomain()))
					, 'xhtml' => array('type' => 'checkbox', 'value' => true, 'title' => __('Make the embed code XHTML compliant', self::getTextDomain()))
					, 'api' => array('type' => 'checkbox', 'value' => false, 'title' => __('Enable the Javascript API for Moogaloop', self::getTextDomain()))
					, 'wmode' => array('type'=>'select', 'values'=>array('','opaque','transparent'), 'value' => '', 'title' => __('Add the "wmode" parameter', self::getTextDomain()))
					, 'iframe' => array('type' => 'checkbox', 'value' => true, 'title' => __('Use vimeo new embed code', self::getTextDomain()))
				)
			);
		}
		return apply_filters('wp_easy_embed_config_' . $key, $config[$key]);
	}
	
	public static function getTextDomain(){
		return 'wp-easy-embed';
	}
}
?>