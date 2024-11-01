<?php
class EasyEmbedGetAdditionalFieldsAjaxAction extends EasyEmbedAjaxAction {
	public function __construct(){
		$this->actionName = 'wp_easy_embed_additional_fields';
	}
	
	/**
	 *	Echoes additional form fields depending on provider
	 *	used as dispatcher for ajax request
	 */
	public function run() {
		$provider = strval($_REQUEST['provider']);
		if(!in_array($provider, EasyEmbedConfig::get('supported_providers'))){
			die;
		}
		$attributes = EasyEmbedConfig::get('supported_attributes_' . $provider);
		if(!is_array($attributes)){
			die;
		}
?>		<tr class="additional_field" style="display:none;">
			<td><input type="hidden" name="provider" value="<?php echo $provider;?>" /></td>
		</tr>
<?php
		foreach ($attributes as $name => $args) {
			$html = '';
			switch ($args['type']) {
				case 'text': $html = $this->getTextHtml($name, $args['value']);
					break;
				case 'checkbox': $html = $this->getCheckboxHtml($name, $args['value']);
					break;
				case 'select': $html = $this->getSelectHtml($name, $args['values'], $args['value']);
					break;
				default:
					$html = $this->getCustomHtml($name, $args);
			}
?>
		<tr class="additional_field">
			<th scope="row"><?php echo $args['title'];?></th>
			<td><?php echo $html;?></td>
		</tr>
<?php			
		}
		die;
	}    
	
	protected function getTextHtml($name, $value) {
		return '<input name="' . $name . '" value="' . $value . '" type="text" />';
	}
	
	protected function getCheckboxHtml($name, $value) {
		return '<input name="' . $name . '" value="1" type="checkbox" ' . checked(true, $value, false) . '/>';
	}
	
	protected function getSelectHtml($name, $values, $current) {
		if(!is_array($values)){
			return '';
		}
		$html = '<select name="' . $name . '">';
		foreach ($values as $value){
			$html .= '<option' . selected($value, $current, false) . '>' . $value . '</option>';
		}
		$html .= '</select>';
		return $html;
	}
	
	protected function getCustomHtml($name, $args) {
		return apply_filters('wp_easy_embed_additional_field_' . $args['type'], $name, $args);
	}

}

WPEasyEmbed::addAjaxAction(new EasyEmbedGetAdditionalFieldsAjaxAction());
?>