<?php
class EasyEmbedSettings {
	protected static $options = null;
	protected static $settings = array();

	public static function init(){
		add_action('admin_init', array(__CLASS__, 'register'));
		add_action('admin_menu', array(__CLASS__, 'addAdminMenu'));
		self::$options = get_option(EasyEmbedConfig::get('option_name'));
		self::$settings[] = new EasyEmbedKeepSetting();
		self::$settings[] = new EasyEmbedShowSetting();
	}
	
	public static function activate(){
		$name = EasyEmbedConfig::get('option_name');
		if(!is_array(get_option($name))){
			update_option($name, array(
				'show_in_media_toolbar' => true
				, 'show_in_editor_toolbar' => false
				, 'show_in_fullscreen' => true
				, 'keep_settings' => false
			));
		}
	}
	
	public static function deactivate(){
		if(self::get('keep_settings') !== true){
			delete_option(EasyEmbedConfig::get('option_name'));
		}
	}
	
	public static function get($key){
		return self::$options[$key];
	}
	
	/**
	 * 
	 * Adds plugin configuration page to admin menu
	 * 
	 * @return The resulting page's hook_suffix
	 */
	public static function addAdminMenu(){
		return add_options_page(
			EasyEmbedConfig::get('option_page_title')
			, EasyEmbedConfig::get('menu_title')
			, EasyEmbedConfig::get('menu_capability')
			, __FILE__
			, array(__CLASS__, 'getOptionsPage')
		);
	}
	
	/**
	 * register plugin configuration settings
	 */
	public static function register(){
		register_setting(
			'wp_easy_embed_options'
			, EasyEmbedConfig::get('option_name')
			, array(
				__CLASS__
				, '_validateOption'
			)
		);
		add_settings_section(
			EasyEmbedConfig::get('settings_section_name')
			, __('Settings', EasyEmbedConfig::getTextDomain())
			, array(__CLASS__, '_getOptionsSection'	)
			, EasyEmbedConfig::get('option_name')
		);
		foreach (self::$settings as $setting) {
			$setting->register();
		}
	}
	
	/**
	 * 
	 * Plugin configuration page
	 * 
	 * @return page in html
	 */
	public static function getOptionsPage(){
?>
		<div class="wrap">
		<h2><?php _e('WP Easy Embed', EasyEmbedConfig::getTextDomain());?></h2>
		
		<form method="post" action="options.php">
	    <?php
		    settings_fields( 'wp_easy_embed_options' );
		    do_settings_sections( 'wp_easy_embed' );
	    ?>
		    <p class="submit">
		    <input type="submit" class="button-primary" value="<?php _e('Save Changes', EasyEmbedConfig::getTextDomain()) ?>" />
		    </p>
		
		</form>
		</div>
<?php
	}
	
	/**
	 * validates configuration options
	 * @param $input value to validate
	 * @return beautified input string or empty string, if input didnt passed validation
	 */
	public static function _validateOption($input){
		foreach (self::$settings as $setting) {
			$input = $setting->validate($input);
		}
		return $input;
	}
	
	/**
	 * 
	 *  Does some stuff before section output. not used for now
	 */
	public static function _getOptionsSection(){}
}
?>