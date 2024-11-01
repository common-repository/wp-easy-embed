<?php
class EasyEmbedButtons extends EasyEmbedComponent{
	public function init() {
		parent::init();
		if(self::hasToShowAnyButton()){
			$this->setupMediaButton();		
			$this->setupEditorButton();
			$this->setupFullscreenButton();
		}
	}
	
	protected static function hasToShowAnyButton(){
		static $hasToShow = null;
		if($hasToShow === null){
			global $pagenow, $typenow;
			$type = $typenow == '' ? 'post' : $typenow;
			$hasToShow = apply_filters('wp_easy_embed_show_any_button', 
				get_user_option('rich_editing') == 'true'
				&& ($pagenow === "post.php" || $pagenow === "post-new.php") 
				&& (
					($type === "post" && current_user_can('edit_posts'))
					|| ($type === "page" && current_user_can('edit_pages'))
				)
			);
			echo '<!--!!' . print_r($hasToShow, 1) . '-->';
		}
		return $hasToShow;
	}
	
	protected function setupMediaButton() {
		if($this->hasToShowMediaButton()){
			add_action('media_buttons_context', array(&$this, 'addToMediaButtons'));
		}
	}
	
	protected function hasToShowMediaButton(){
		return EasyEmbedSettings::get('show_in_media_toolbar') === true;
	}
	
	public function addToMediaButtons($html) {
		return $html . '<a href="#" id="embed_button" onclick="tinymce.execCommand(\'wpeasyembed\');return false;"><img
			src="' . esc_url( EasyEmbedConfig::get('button_url') ) . '" alt="' . __('Embed content', EasyEmbedConfig::getTextDomain()) . '"/></a>'; 
	}
	
	protected function setupEditorButton() {
		if($this->hasToShowEditorButton()){
			$this->setupButtonScripts();
			add_filter('mce_buttons', array(&$this, 'addToEditorButtons'));
		}
	}
	
	protected function hasToShowEditorButton(){
		return EasyEmbedSettings::get('show_in_editor_toolbar') === true;
	}

	public function addToEditorButtons($buttons) {
		array_push($buttons, 'separator', 'wpeasyembed');
		return $buttons;
	}
	
	protected function setupFullscreenButton() {
		if($this->hasToShowFullscreenButton()){
			parent::setUsed();
			$this->setupButtonScripts();
			add_filter('wp_fullscreen_buttons', array(&$this, 'addToFullscreenButtons'));
		}
	}
	
	protected function hasToShowFullscreenButton(){
		return EasyEmbedSettings::get('show_in_fullscreen') === true;
	}

	public function addToFullscreenButtons($buttons) {
		$buttons[] = 'separator';
		$buttons['wpeasyembed'] = array(
			'title' => __('Embed content', EasyEmbedConfig::getTextDomain())
			, 'onclick' => 'tinymce.execCommand(\'wpeasyembed\');'
			, 'both' => EasyEmbedConfig::get('show_in_both_modes')
		);
		return $buttons;
	}
	
	protected function setupButtonScripts() {
		$this->setUsed();
		add_filter("mce_external_plugins", array(__CLASS__, '_addTinyMCEPlugin'));
	}

	public function _addTinyMCEPlugin($plugin_array) {
		$plugin_array['wpeasyembed'] = WPEasyEmbed::_pluginsUrl('tinymce/editor_plugin.js');
		return $plugin_array;
	}
	
	public function includeStylesInHeader() {
		if(self::hasToShowAnyButton()){
			wp_enqueue_style('wpeasyembed-buttons', WPEasyEmbed::_pluginsUrl('css/style.css'));
?><style>
span.mce_wpeasyembed {
	background: url('<?php echo EasyEmbedConfig::get('button_url')?>') no-repeat center !important;
}
</style><?php
		}
	}
	
	protected function includeScriptsInFooter() {
?><script type="text/javascript">
	var wpEasyEmbedConfig = function(){
		return {
			buttonURL : '<?php echo EasyEmbedConfig::get('button_url')?>'
			, loadingIconURL : '<?php echo EasyEmbedConfig::get('loading_icon_url')?>'
			, providers : ['<?php echo implode("','", EasyEmbedConfig::get('supported_providers'))?>']
		};
	};
</script><?php
	}	
}

?>