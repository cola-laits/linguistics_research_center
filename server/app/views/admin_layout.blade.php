<!DOCTYPE html>
<html lang='en'>
    <head>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <title>LRC Admin - @yield('title')</title>
 
        {{ HTML::style('//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css') }}
        {{ HTML::style('//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css') }}
        
        {{ HTML::script('https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js') }}
        {{ HTML::script('http://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js') }}
  
        <style>
            body {
                margin-top: 70px;
            }
        </style>

    </head>
    <body>
    
    <script type="text/javascript">
    $(document).ready(function(){
		//highlight the menu for whichever page we are on
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
    
    	@if(Request::path() != 'login')
    
	        <!-- navbar -->
		    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		      <div class="container-fluid">
		        <div class="navbar-header">
		          <div class="navbar-brand">LRC Admin</div>
		        </div>
		        
		        <ul class="nav navbar-nav">
		          <li>{{ HTML::link('/admin/eieol_series', 'Series', array('title' => 'Series Maintenance')) }}</li>
		          <li>{{ HTML::link('/admin/user', 'Users', array('title' => 'User Maintenance' )) }}</li>
		        </ul>
		        
		        <ul class="nav navbar-nav navbar-right">
		          <li>{{ HTML::link('logout', 'Logout', array('title' => 'Logout' )) }}</li>
		        </ul>
		      </div>
		    </nav>
	    
	    @endif
	
	    <div class="container-fluid">		
	        @yield('content')
	    </div>
 
    </body>
</html>	


