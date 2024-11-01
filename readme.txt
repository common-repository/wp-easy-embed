=== WP Easy Embed ===
Contributors: ibarmin
Tags: embed, post, video, youtube, vimeo, bliptv, flickr, oembed
Requires at least: 3.3.0
Tested up to: 3.3.1
Stable tag: 1.2
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=Y885NYRSWNSS4&lc=RU&item_name=WP%20Easy%20Embed&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted

WP Easy Embed allows you to embed videos, images and such via OEmbed service without manually editing the post (or page)

== Description ==

WP Easy Embed allows you to embed videos, images and such via [OEmbed service](http://oembed.com/) without manually editing the post (or page).
If content provider support additional fields they can be used (right now only [vimeo ones](http://vimeo.com/api/docs/oembed) are supported)

== Installation ==

As usual, upload the folder of WP Easy Embed to the wordpress plugin directory, activate it then.

To alter settings of this plugin, go to **Settings->WP Easy Embed**.

== Screenshots ==

1. You can preview how embed will look like before inserting shortcode into post
2. Some providers support additional fields

== Changelog ==

= 1.2 =
* multisite issues with activating and deactivating fixed
* fixed style.css not applying to page
* fixed js issue - removed 'provider' field from [embed] tag
* reworked inner part even more

= 1.1 =
* added additional oembed fields. right now only [vimeo ones](http://vimeo.com/api/docs/oembed) are supported
* preview can be hided
* reworked inner part a bit - reduced calls of get_option to one per instance
* added loading animation for preview area

= 1.0 =
* initial release

== Other notes ==

= i18n =
Plugin is ready for l10n, just a little note - there are two language files to translate,
languages/wp-easy-embed.pot and tinymce/langs/en.js

= Plugin's actions & filters =
* wp_easy_embed_config_{$config_key} filter
* wp_easy_embed_init action. Runs before any button created
* wp_easy_embed_additional_field_{$field_type} filter with $args parameter. See below.

= Adding support for some provider additional fields =
* Add wp_easy_embed_config_supported_providers filter and push to passed array one more string - provider name (its domain name, please)
* Add wp_easy_embed_config_supported_attributes_{$provider_name} filter and return assoc array in format, described in next section

= Supported Atrributes Array Format =
`array(
	'Attribute name #1' => array('type' => 'text', 'value' => 'Default value', 'title' => 'Title for field #1')
	, 'Attribute name #2' => array('type' => 'checkbox', 'value' => [true|false], 'title' => 'Title for field #2')
	, 'Attribute name #3' => array('type'=>'select', 'values'=>array('value 1','value 2', ...), 'value' => 'Selected one', 'title' => 'Title for field #3')
	, 'Attribute name #4' => array('type' => 'your own type', 'value' => '', 'title' => 'Title for field #4')
)`

To add type, add filter handler for wp_easy_embed_additional_field_{$field_type} and return html. Name is 1st parameter and config for that attribute is passed in second parameter