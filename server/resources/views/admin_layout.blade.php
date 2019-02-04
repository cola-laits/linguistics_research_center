<!DOCTYPE html>
<html lang='en'>
    <head>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>LRC Admin - @yield('title')</title>

        <link media="all" type="text/css" rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
        <link media="all" type="text/css" rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
        <link media="all" type="text/css" rel="stylesheet" href="/css/adminstyle.css">
        <link media="all" type="text/css" rel="stylesheet" href="/css/jquery.tagsinput.css">
        <link media="all" type="text/css" rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
        <script src="//cdn.ckeditor.com/4.4.5.1/full/ckeditor.js"></script>

        <script src="/js/jquery.tagsinput.js"></script>
        <script src="//code.jquery.com/ui/1.11.2/jquery-ui.min.js"></script>
        <script src="/js/specialedit.jquery.js"></script>
        <script src="/js/google_analytics.js"></script>

    </head>
    <body onload="top.scrollTo(0,0)">

    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <a class="navbar-brand" href="/admin">Admin</a>

        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">EIEOL <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="/admin2/eieol_series">Series</a></li>
                        <li><a href="/admin2/eieol_language">Languages</a></li>
                    </ul>
                </li>
                @if (\Illuminate\Support\Facades\Auth::user()->isAdmin())
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Lexicon <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="/admin2/FIXME">Etyma</a></li>
                            <li><a href="/admin2/FIXME">Reflex</a></li>
                            <li><a href="/admin2/FIXME">Reflex -> Entry</a></li>
                            <li><a href="/admin2/FIXME">Reflex -> Part of Speech</a></li>
                            <li><a href="/admin2/FIXME">Semantic Category</a></li>
                            <li><a href="/admin2/FIXME">Semantic Field</a></li>
                            <li><a href="/admin2/FIXME">Language Family</a></li>
                            <li><a href="/admin2/FIXME">Language Sub Family</a></li>
                            <li><a href="/admin2/FIXME">Language</a></li>
                            <li><a href="/admin2/FIXME">Source</a></li>
                            <li><a href="/admin2/FIXME">Part of Speech</a></li>
                        </ul>
                    </li>
                <li><a href="/admin2/user">Users</a></li>
                <li><a href="/admin2/page">Pages</a></li>
                @endif
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="/">Back to Site</a></li>
            </ul>
        </div>
        </div>
    </nav>

        <script type="text/javascript">
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

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
                { name: 'document', items : [ 'Source','Language'] },


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


