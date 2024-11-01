
(function() {
	tinymce.create('tinymce.plugins.wpeasyembed', {

		init : function(ed, url) {
			var t = this;

			t.url = url;
			ed.addCommand('wpeasyembed', function() {
				var el = ed.selection.getNode(), vp = tinymce.DOM.getViewPort(), H = vp.h, W = ( 720 < vp.w ) ? 720 : vp.w, cls = ed.dom.getAttrib(el, 'class');

				tb_show(tinymce.activeEditor.translate('wpeasyembed.embed_desc'), url + '/embedform.html?TB_iframe=true&v=1.1');
				tinymce.DOM.setStyles('TB_window', {
					'width':( W - 50 )+'px',
					'height':( H - 45 )+'px',
					'top':'20px',
					'marginTop':'0',
					'margin-left':'-'+parseInt((( W - 50 ) / 2),10) + 'px'
				});

				tinymce.DOM.setStyles('TB_iframeContent', {
					'width':( W - 50 )+'px',
					'height':( H - 75 )+'px'
				});
				tinymce.DOM.setStyle( ['TB_overlay','TB_window','TB_load'], 'z-index', '999999' );
			});

			ed.addButton('wpeasyembed', {
				title : 'wpeasyembed.embed_desc',
				image : wpEasyEmbedConfig().buttonURL,
				onclick : function(){
					ed.execCommand('wpeasyembed');
				}
			});
		},
		getInfo : function() {
			return {
				longname : tinymce.activeEditor.translate('wpeasyembed.longname'),
				author : 'ibarmin',
				authorurl : 'http://wordpress.org/support/profile/ibarmin',
				infourl : '',
				version : "1.1"
			};
		}
	});

	tinymce.PluginManager.add('wpeasyembed', tinymce.plugins.wpeasyembed);
})();
