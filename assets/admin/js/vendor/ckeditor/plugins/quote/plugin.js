CKEDITOR.plugins.add( 'quote', {
    icons: 'quote',
    init: function( editor ) {
    	editor.addCommand( 'quote', new CKEDITOR.dialogCommand( 'quoteDialog' ) );
		editor.ui.addButton( 'quote', {
		    label: 'Insert quote',
		    command: 'quote',
		    toolbar: 'paragraph'
		});

        CKEDITOR.dialog.add( 'quoteDialog', this.path + 'dialogs/quote.js' );
    }
});