window.addEvent('domready', function(){
	if($('form_pages_text'))
		CKEDITOR.replace('form_pages_text', {
			toolbarCanCollapse: false,
			toolbar:
			[
				{ name: 'document', items: ['Source'] },
				{ name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'] },
				{ name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl'] },
				{ name: 'colors', items : [ 'TextColor', 'BGColor' ] },
				{ name: 'tools', items : [ 'Maximize', 'ShowBlocks' ] },
				{ name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'] },
				{ name: 'links', items: ['Link', 'Unlink', 'Anchor'] },
				{ name: 'insert', items : [ 'Image', 'Flash','Smiley','SpecialChar' ] },
				{ name: 'styles', items : [ 'Styles', 'Format', 'Font', 'FontSize' ] }
			]
		});
});