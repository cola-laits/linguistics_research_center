<!DOCTYPE html>
<html lang='en'>
    <head>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>LRC Admin - @yield('title')</title>
 
        {{ HTML::style('//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css') }}
        {{ HTML::style('//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css') }}
        {{ HTML::style('css/adminstyle.css') }}
        {{ HTML::style('css/jquery.tagsinput.css') }}
        {{ HTML::style('//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css') }}
        
        {{ HTML::script('//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js') }}
        {{ HTML::script('//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js') }}
        {{ HTML::script('//cdn.ckeditor.com/4.4.5.1/full/ckeditor.js') }}
        {{ HTML::script('js/jquery.tagsinput.js') }}
        {{ HTML::script('//code.jquery.com/ui/1.11.2/jquery-ui.min.js') }}
        {{ HTML::script('js/specialedit.jquery.js') }}

 
    </head>
    <body onload="top.scrollTo(0,0)">
    
	    <!-- Google Analytics Script -->
		<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		
		  ga('create', 'UA-45942849-1', 'auto');
		  ga('send', 'pageview');
		
		</script>
		<!-- End Google Analytics Script -->
		    
	    <script type="text/javascript">
		    $(document).ready(function(){
				//highlight the menu for whichever page we are on - only works for main pages, not edit or create pages
				$('a[href$="' + this.location.pathname + '"]').parent().addClass('active');
		
				//generic delete confirmation
				$(".delete").click(function(e) {
				    e.preventDefault();
				    var $form=$(this).closest('form');
		
				    $("#delete_confirm").modal('show')
				    	.one('click', '#delete_confirmed', function (e) {
				            $form.trigger('submit');
				        });
				});
							
		    });
		
		    
		
		    $mytoolbar =
		    	[
		    		{ name: 'document', items : [ 'Source'] },
					{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
					{ name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','SpellChecker' ] },
					{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
					{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','-',
					                   			   'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
					{ name: 'links', items : [ 'Link','Unlink','Anchor' ] },
					{ name: 'insert', items : [ 'Table','HorizontalRule','SpecialChar'] },
					{ name: 'styles', items : [ 'Format','FontSize' ] },
					{ name: 'colors', items : [ 'TextColor','BGColor' ] },
					{ name: 'insert', items : [ 'Image' ]  },
					{ name: 'tools', items : [ 'Maximize'] }
		    	];
		
	    </script>
	    
		
		<div id="delete_confirm" class="modal fade">
		    <div class="modal-dialog">
		        <div class="modal-content">
		            <div class="modal-header">
		                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		                <h4 class="modal-title">Delete Confirmation</h4>
		            </div>
		            <div class="modal-body">
		                Are you sure you want to delete this record?  <br/><br/>
		                <p class="text-warning"><small>This action can not be undone later.</small></p>
		            </div>
		            <div class="modal-footer">
		                <button type="button" class="btn btn-primary" id="delete_confirmed">Delete</button>
		                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		            </div>
		        </div>
		    </div>
		</div>
		
	    <div class="container-fluid">		
	        @yield('content')
	    </div>
 
    </body>
</html>	


