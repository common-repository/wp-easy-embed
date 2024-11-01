<?php
class EasyEmbedComponent {
	private $used = false;
	public function init(){
		add_action('wp_enqueue_scripts', array(&$this, 'includeScriptsInHeader'));
		add_action('admin_print_styles', array(&$this, 'includeStylesInHeader'));
		add_action('admin_print_footer_scripts', array(&$this, 'includeIfUsed'));
	}
	
	public function run($atts){
		$this->setUsed();
	}
	
	public function includeIfUsed() {
		if(!$this->isUsed()){
			return;
		}
		$this->includeScriptsInFooter();
	} 
	
	protected function setUsed(){
		$this->used = true;
	}
	
	protected function isUsed() {
		return $this->used === true;
	}
	
	public function includeStylesInHeader(){}
	public function includeScriptsInHeader(){}
	protected function includeScriptsInFooter(){}
}