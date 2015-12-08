//This adds a button to CK Editor.  The button will add a span tag and specify the class and lang vars.
//Example: <span class="Unicode" lang="non">text</span>
//
//Usage:
//  1. Add CKEDITOR.plugins.addExternal( 'eieol_language', '/js/', 'eieollanguageplugin.js' ); to your js
//  2. When defining your ckeditor, use something like the following:
//      		CKEDITOR.replace( 'text_area_name',{toolbar : $mytoolbar, extraPlugins : 'eieol_language', language_class:'Unicode', language_lang:'non');
//  3. Add to your button list, something like this:
//       $mytoolbar = [{ name: 'document', items : [ 'Language'] }];


CKEDITOR.plugins.add( 'eieol_language', {
	init : function( editor ) {
		var language_class = editor.config.language_class;
		var language_lang = editor.config.language_lang;

		//the style we want to apply
		var style = new CKEDITOR.style({
				element : 'span',
				attributes : { 'lang' : language_lang, 'class' : language_class }				
		});
		
		// Creates a command for our plugin, here command will apply style. All the logic is
        // inside CKEDITOR.styleCommand#exec function so we don't need to implement anything.
        editor.addCommand( 'eieol_language', new CKEDITOR.styleCommand( style ) );
        
        // This part will provide toolbar button highlighting in editor.
        editor.attachStyleStateChange( style, function( state ) {
            !editor.readOnly && editor.getCommand( 'eieol_language' ).setState( state );
        } );
        
        // This will add button to the toolbar.
        editor.ui.addButton('EieolLanguage',
            {
                label: 'Insert Language Span',
                command: 'eieol_language',
                icon : this.path + 'icon.png'
            });
	}
});