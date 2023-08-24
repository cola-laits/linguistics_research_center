@extends('admin_layout')

@section('head_extra')
    <script>
        function relatedLanguagesSelectChange() {
            var select = document.getElementById('relatedLanguagesSelect');
            var submit = document.getElementById('relatedLanguagesSubmit');
            if (select.selectedIndex === 0) {
                submit.setAttribute('disabled', 'disabled');
            } else {
                submit.removeAttribute('disabled');
            }
            return false;
        }

        function removeLanguage(id) {
            var form = document.getElementById('deleteRelatedLangForm');
            form.setAttribute('action', '/admin2/related_languages/{{$series->id}}/detach_language/' + id);
            form.submit();
        }

        /*
        function  removeLanguage(l) {

            axios.post('/admin2/related_languages/{{$series->id}}/detach_language/' + l.value).then(function(response){

                window.fetchlanguages();

            }).catch(function(error){console.log(error);});

        }
        */

    </script>
@endsection

@section('content')

<div class='col-lg-12'>

    <h1><i class='fa fa-book'></i> {{{$action}}} Series</h1>
    <p><a href="/guides/eieol_author" target=_new>Author Guide</a></p>

    @if (Session::has('message'))
      <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif

    @if (count($errors)>0)
      <div class='bg-danger alert'>
        <ul>
          @foreach ($errors->all() as $error)
              <li>{{{ $error }}}</li>
          @endforeach
          </ul>
        </div>
    @endif

  @if ($action != 'Create')

        <h1>{{$series->title}}</h1>

    <hr/>

        <div id="related_languages" class='row'>
            <div class='form-group col-sm-4'>

                <h2>Related Languages</h2>

                <form class="form" method="POST" action="/admin2/related_languages/attach_language">
                    @csrf
                    <input type="hidden" name="id" value="{{ $series->id }}"/>
                <select class="form-control" id="relatedLanguagesSelect"
                        name="lang"
                    onchange="relatedLanguagesSelectChange()">
                    <option value="">Choose language</option>
                    @foreach ($languages as $language)
                        <option value="{{ $language['value'] }}">{{ $language['text'] }}</option>
                    @endforeach
                </select>

                <br/>

                <button type="submit"
                        id="relatedLanguagesSubmit"
                        disabled="disabled"
                        class="btn btn-sm btn-primary">Attach</button>
                </form>

            </div>

            <div class='col-sm-4'>
                <form method="POST" action="" id="deleteRelatedLangForm">
                    @csrf
                </form>
                <ul>
                    @foreach ($attached_languages as $att_lang)
                    <li>
                        {{ $att_lang['text'] }}
                        &nbsp;<a onclick="removeLanguage({{$att_lang['id']}})" href="#">remove</a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

    <h2>Lessons</h2>

    <div class="table-responsive">
          <table class="table table-bordered table-striped">

              <thead>
                  <tr>
                      <th>Order</th>
                      <th>Title</th>
                      <th>Language</th>
                      <th>Updated</th>
                      <th></th>
                  </tr>
              </thead>

              <tbody>
                  @foreach ($lessons as $lesson)
                  <tr>
                      <td>{{{ $lesson->order }}}</td>
                      <td>{{ $lesson->title }}</td>
                      <td>{{ $lesson->language->language }}</td>
                      <td>{{{ $lesson->updated_at->format('m/d/Y h:ia') }}} by {{{ $lesson->updated_by }}}</td>
                      <td>
                          <a href="/admin2/eieol_lesson/{{{ $lesson->id }}}/edit" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>
                      </td>
                  </tr>
                  @endforeach
              </tbody>

          </table>
      </div>
    <a href="/admin2/eieol_lesson/create?series_id={{{ $series->id }}}" class="btn btn-success">Add New Lesson</a>

  @endif

</div>

@stop
