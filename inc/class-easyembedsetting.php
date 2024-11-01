<?php
abstract class EasyEmbedSetting {
	protected $id;
	protected $title;
	public function register(){
		add_settings_field(
			$this->id
			, $this->title
			, array(&$this, 'run')
			, EasyEmbedConfig::get('option_name')
			, EasyEmbedConfig::get('settings_section_name')
		);
	}
	
	abstract public function run();
	
	public function validate($input){
		return $input;
	}
}

class EasyEmbedKeepSetting extends EasyEmbedSetting {
	public function __construct(){
		$this->id = 'wp_easy_embed_keep';
		$this->title = __('Keep settings', EasyEmbedConfig::getTextDomain());
	}
	
	public function run(){
?>
		<input id="<?php echo $this->id;?>" type="checkbox" value="1" name="wp_easy_embed[keep_settings]" <?php checked(true, EasyEmbedSettings::get('keep_settings'));?> /> <label for="<?php echo $this->id;?>"><?php _e('Keep settings upon plugin deactivation. Otherwise they will be deleted', EasyEmbedConfig::getTextDomain());?></label>
<?php
	}
	
	public function validate($input){
		if($input['keep_settings'] === '1'){
			$input['keep_settings'] = true;
		}
		return parent::validate($input);
	}
}

class EasyEmbedShowSetting extends EasyEmbedSetting {
	public function __construct(){
		$this->id = 'wp_easy_embed_show';
		$this->title = __('Where to show', EasyEmbedConfig::getTextDomain());
	}
	
	public function run(){
		$checkboxes = array('show_in_media_toolbar' => __('in Media toolbar', EasyEmbedConfig::getTextDomain()), 'show_in_editor_toolbar' => __('in Editor toolbar', EasyEmbedConfig::getTextDomain()), 'show_in_fullscreen' => __('in FullScreen Mode', EasyEmbedConfig::getTextDomain()));
		echo '<ul>';
		foreach ($checkboxes as $name => $title){
?>
			<li><input id="<?php echo $id . '_' . $name;?>" type="checkbox" value="1" name="wp_easy_embed[<?php echo $name;?>]" <?php checked(true, EasyEmbedSettings::get($name));?> /> <label for="<?php echo $id . '_' . $name;?>"><?php echo $title;?></label></li>
<?php
		}
		echo '</ul>';
	}
	
	public function validate($input){
		if($input['show_in_media_toolbar'] === '1'){
			$input['show_in_media_toolbar'] = true;
		}
		if($input['show_in_editor_toolbar'] === '1'){
			$input['show_in_editor_toolbar'] = true;
		}
		if($input['show_in_fullscreen'] === '1'){
			$input['show_in_fullscreen'] = true;
		}
		return parent::validate($input);
	}
}
?>