CKEDITOR.dialog.add( 'ctaDialog', function( editor ) {
    return {
        title: 'CTA Properties',
        minWidth: 400,
        minHeight: 200,

        contents: [
            {
                id: 'tab-basic',
                label: 'Basic Settings',
                elements: [
                    {
		                type: 'text',
		                id: 'title',
		                label: 'Title',
		                validate: CKEDITOR.dialog.validate.notEmpty( "Title field cannot be empty." ),
		                setup: function( element ) {
                            this.setValue( element.getText() );
                        },

                        commit: function( element ) {
                            element.setText( this.getValue() );
                        }
		            },
                    {
                        type: 'text',
                        id: 'link',
                        label: 'URL',
                        validate: CKEDITOR.dialog.validate.notEmpty( "URL field cannot be empty." ),
                        setup: function( element ) {
                            this.setValue( element.getAttribute( "href" ) );
                        },
                        commit: function( element ) {
                            element.setAttribute( "href", this.getValue() );
                        }
                    }
                ]
            }
        ],

        onShow: function() {
            var selection = editor.getSelection();
            var element = selection.getStartElement();

            if ( element ) {
                element = element.getAscendant( 'a', true );
            }
            

            if ( !element || element.getName() != 'a' ) {
                element = editor.document.createElement( 'a' );
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
		    var dialog = this;
            var cta = this.element;
            cta.setAttribute('class', 'btn btn--large');
            this.commitContent( cta );

            if ( this.insertMode ) {
                editor.insertElement( cta );
            }
		}
    };
});