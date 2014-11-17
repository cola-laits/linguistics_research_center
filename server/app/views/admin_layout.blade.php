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
    });
    </script>
    
    	@if(Request::path() != 'login')
    
	        <!-- navbar -->
		    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		      <div class="container">
		        <div class="navbar-header">
		          <div class="navbar-brand">LRC Admin</div>
		        </div>
		        
		        <ul class="nav navbar-nav">
		          <li>{{ HTML::link('/admin/', 'Menu', array('title' => 'Admin Menu')) }}</li>
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


