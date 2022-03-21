@extends('admin_layout')

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

    <related-languages-select series_id="{{$series->id}}"></related-languages-select>

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
