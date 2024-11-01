<?php
class EasyEmbedPreviewEmbedAjaxAction extends EasyEmbedAjaxAction {
	public function __construct() {
		$this->actionName = 'wp_easy_embed_preview';
		
	}

	public function run() {
		if($_REQUEST['url'] == ''){
			die;
		}

		$args = $this->getEmbedAttributes($_REQUEST);
		$url = $args['url'];
		unset($args['url']);
		
		WPEasyEmbed::includeSrc('/class-easyoembed.php');
		die(EasyOEmbed::getInstance()->get_html($url, $args));
	}

	protected function getEmbedAttributes($args) {
		$provider = $args['provider'];
		$supportedProviders = EasyEmbedConfig::get('supported_providers');
		$supportedAttributes = EasyEmbedConfig::get('supported_attributes');
		
		if(in_array($provider, $supportedProviders)){
			$supportedAttributesForProvider = array_keys(EasyEmbedConfig::get('supported_attributes_' . $provider));
			$supportedAttributes = array_merge($supportedAttributes, $supportedAttributesForProvider);
		}
		
		$attributes = array_keys($args);
		foreach ($attributes as $attr){
			if(!in_array($attr, $supportedAttributes) || $args[$attr] === ''){
				unset($args[$attr]);
			}
		}
		return $args;
	}
}

WPEasyEmbed::addAjaxAction(new EasyEmbedPreviewEmbedAjaxAction());
?>