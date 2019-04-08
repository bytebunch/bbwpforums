/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For the complete reference:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config

	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbarGroups = [
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
		{ name: 'links' },
		{ name: 'insert' },
		{ name: 'forms' },
		{ name: 'tools' },
		{ name: 'colors' },
		{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'others' },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph',   groups: [ 'list', 'blocks', 'align', 'bidi' ] },
		{ name: 'styles' },
		
		//{ name: 'about' }
	];

	// Remove some buttons, provided by the standard plugins, which we don't
	// need to have in the Standard(s) toolbar.
	config.removeButtons = 'Underline,Subscript,Superscript,Source,Table,Maximize,Anchor';
	
	// Se the most common block elements.
	config.format_tags = 'p';
	config.extraAllowedContent = 'div';
	/*config.allowedContent = {
		'b i ul ol big small iframe': true,
		'p blockquote li': {
			styles: 'text-align'
		},
		a: { attributes: '!href,target' },
		img: {
			attributes: '!src,alt',
			styles: 'width,height',
			classes: 'left,right'
		}
	};*/
		
	config.skin = 'kama';
	config.extraPlugins = 'colorbutton,font';
	//config.removePlugins = 'blockquote';
	//config.resize_minWidth = 450;
	config.height = 300;
	//console.log(config);
	// Make dialogs simpler.
	config.removeDialogTabs = 'image:advanced;link:advanced;link:target';
};
