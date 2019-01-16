CKEDITOR.dialog.add( 'quoteDialog', function( editor ) {
    return {
        title: 'Quote Properties',
        minWidth: 400,
        minHeight: 200,

        contents: [
            {
                id: 'tab-basic',
                label: 'Basic Settings',
                elements: [
                    {
		                type: 'text',
		                id: 'quote',
		                label: 'Quote',
		                validate: CKEDITOR.dialog.validate.notEmpty( "Title field cannot be empty." ),
		                setup: function( element ) {
                            this.setValue( element.findOne( "p" ).getText().slice(2, element.findOne( "p" ).getText().length - 2)  );
                        },

                        commit: function( element ) {
                            paragraph = element.findOne("p");
                            if (!paragraph) {
                                paragraph = editor.document.createElement( 'p' );
                            }
                            var locale = document.getElementById('locale-version').value;
                            var quoteStart = '“';
                            var quoteEnd = '”';
                            if (locale == 'fr' || locale == 'it') {
                                quoteStart = '«';
                                quoteEnd = '»';
                            }
                            paragraph.setText(quoteStart + ' ' + this.getValue()+ ' ' + quoteEnd);
                            element.append(paragraph);
                        }
		            },
                    {
                        type: 'text',
                        id: 'author',
                        label: 'Author',
                        setup: function( element ) {
                            this.setValue( element.findOne( "small" ).getText() );
                        },
                        commit: function( element ) {
                            small = element.findOne("small");
                            if (!small) {
                                small = editor.document.createElement( 'small' );
                            }
                            small.setText(this.getValue());
                            element.append(small);
                        }
                    }
                ]
            }
        ],

        onShow: function() {
            var selection = editor.getSelection();
            var element = selection.getStartElement();
            if ( element ) {
                element = element.getAscendant( 'blockquote', true );
            }
            if ( !element || element.getName() != 'blockquote' ) {
                element = editor.document.createElement( 'blockquote' );
                this.insertMode = true;
            }
            else {
                this.insertMode = false;
            }
            this.element = element;
            if ( !this.insertMode ) {
                this.setupContent( this.element );
            }
        },
        onOk: function() {
            var quote = this.element;
            this.commitContent( quote );
            if ( this.insertMode ) {
                editor.insertElement( quote );
            }
		}
    };
});