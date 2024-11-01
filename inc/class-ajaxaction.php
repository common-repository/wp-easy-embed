<?php
abstract class EasyEmbedAjaxAction {
	protected $actionName = '';

	public function init(){
		add_action('wp_ajax_' . $this->actionName, array(&$this, 'run'));
	}
	
	public abstract function run();
}
?>