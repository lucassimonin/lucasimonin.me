CKEDITOR.plugins.add( 'cta', {
    icons: 'cta',
    init: function( editor ) {
    	editor.addCommand( 'cta', new CKEDITOR.dialogCommand( 'ctaDialog' ) );
		editor.ui.addButton( 'cta', {
		    label: 'Insert cta',
		    command: 'cta',
		    toolbar: 'insert'
		});

        CKEDITOR.dialog.add( 'ctaDialog', this.path + 'dialogs/cta.js' );
    }
});