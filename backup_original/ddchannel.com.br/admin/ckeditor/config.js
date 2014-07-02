/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';

	CKEDITOR.config.forcePasteAsPlainText = true;

	config.toolbar = 'Full';
 
	config.toolbar_Full =
	[
		{ name: 'document', items : [ 'Source'] },
		{ name: 'clipboard', items : [ 'PasteText','-','Undo','Redo' ] },
		{ name: 'basicstyles', items : [ 'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock', '-', 'Bold','Italic','Underline', ] },
		{ name: 'paragraph', items : [ 'Strike','Subscript','Superscript','-', 'NumberedList','BulletedList','Blockquote', '-','RemoveFormat'  ] },
		'/',
		{ name: 'colors', items : [ 'TextColor','BGColor' ] },
		{ name: 'styles', items : [ 'Format','FontSize' ] },
		{ name: 'links', items : [ 'Link','Unlink','Anchor' ] },
		{ name: 'insert', items : [ 'Image','Flash','Table','HorizontalRule','Iframe' ] },
		{ name: 'tools', items : [ 'ShowBlocks' ] }
	];
	 
	config.toolbar_Basic =
	[
		[ 'Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink' ]
	];
};