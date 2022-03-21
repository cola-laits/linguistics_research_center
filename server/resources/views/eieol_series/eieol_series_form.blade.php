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

    @if ($action == 'Create')
        <form method="POST" action="/admin2/eieol_series" accept-charset="UTF-8" role="form" class="form">
    @else
        <form method="POST" action="/admin2/eieol_series/{{$series->id}}" accept-charset="UTF-8" role="form" class="form">
            <input name="_method" type="hidden" value="PUT">
    @endif
            {{@csrf_field()}}

    <div class='form-row'>

    <div class='form-group col-sm-1 @if ($errors->has('published')) has-error @endif  '>
        <input type="checkbox" id="published" name="published" class="form-check-input" value="1"
            @if (isset($series) && $series->published) checked="checked" @endif>
        <label for="published" class="form-check-label">Published</label>
    </div>

    <div class='form-group col-sm-1 @if ($errors->has('order')) has-error @endif  '>
        <label for="order">Order</label>
        <input placeholder="" class="form-control" name="order" type="text" value="{{$series->order ?? ''}}">
      </div>

      <div class='form-group col-sm-2 @if ($errors->has('title')) has-error @endif  '>
          <label for="title">Title</label>
          <input placeholder="" class="form-control" name="title" type="text" value="{{$series->title ?? ''}}" id="title">
      </div>

      <div class='form-group col-sm-1 @if ($errors->has('menu_name')) has-error @endif  '>
          <label for="menu_name">Menu Name</label>
          <input placeholder="" class="form-control" name="menu_name" type="text" value="{{$series->menu_name ?? ''}}" id="menu_name">
      </div>

      <div class='form-group col-sm-1 @if ($errors->has('menu_order')) has-error @endif  '>
          <label for="menu_order">Menu Order</label>
          <input placeholder="" class="form-control" name="menu_order" type="text" value="{{$series->menu_order ?? ''}}" id="menu_order">
      </div>

      <div class='form-group col-sm-2 @if ($errors->has('expanded_title')) has-error @endif  '>
          <label for="expanded_title">Expanded Title</label>
          <input placeholder="" class="form-control" name="expanded_title" type="text" value="{{$series->expanded_title ?? ''}}" id="expanded_title">
      </div>

      <div class='form-group col-sm-1 @if ($errors->has('slug')) has-error @endif  '>
          <label for="slug">Slug</label>
          <input placeholder="" class="form-control" name="slug" type="text" value="{{$series->slug ?? ''}}" id="slug">
      </div>

      <div class='form-group col-sm-1 @if ($errors->has('meta_tags')) has-error @endif  '>
          <label for="meta_tags">Meta Tags</label>
          <input placeholder="" class="form-control" name="meta_tags" type="text" value="{{$series->meta_tags ?? ''}}" id="meta_tags">
      </div>

      <div class='form-group col-sm-1'>
          <input class="btn btn-primary" type="submit" value="Save">
      </div>


    </div>

    </form>

  @if ($action != 'Create')

    <i>Created {{{ $series->created_at->format('m/d/Y h:ia') }}} by {{{ $series->created_by }}} |
    Updated {{{ $series->updated_at->format('m/d/Y h:ia') }}} by {{{ $series->updated_by }}}</i>

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
