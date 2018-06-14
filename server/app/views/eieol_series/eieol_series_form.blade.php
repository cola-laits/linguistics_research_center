@extends('admin_layout')
 
@section('content')

@if ($action != 'Create')

{{ HTML::script('js/vue.js') }}
{{ HTML::script('js/vue-search-select.js') }}
{{ HTML::script('js/axios.min.js') }}

<script>

  var seriesId = {{$series->id}};

  </script>
 
<script src="/js/related_languages.js"></script>

@endif


<div class='col-lg-12'>
 
    <h1><i class='fa fa-book'></i> {{{$action}}} Series</h1>
    <p><a href="/guides/eieol_author" target=_new>Author Guide</a></p>
    
    @if (Session::has('message'))
      <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif
    
    @if ($errors->has())
      <div class='bg-danger alert'>
        <ul>
          @foreach ($errors->all() as $error)
              <li>{{{ $error }}}</li>
          @endforeach
          </ul>
        </div>
    @endif
    
    <div class='row'>
 
    @if ($action == 'Create')
      {{ Form::open(['role' => 'form', 'url' => '/admin2/eieol_series', 'class' => 'form']) }}
    @else
        {{ Form::model($series, ['role' => 'form', 'url' => '/admin2/eieol_series/' . $series->id, 'method' => 'PUT', 'class' => 'form']) }}
    @endif
    
    <div class='form-group col-sm-1 @if ($errors->has('published')) has-error @endif  '>
          {{ Form::label('published', 'Published') }}
          {{ Form::checkbox('published', 1, false, ['class' => 'form-control']) }}
    </div>
    
    <div class='form-group col-sm-1 @if ($errors->has('use_old_gloss_ui')) has-error @endif  '>
          {{ Form::label('use_old_gloss_ui', 'Old Gloss UI') }}
          {{ Form::checkbox('use_old_gloss_ui', 1, false, ['class' => 'form-control']) }}
    </div>
    
    <div class='form-group col-sm-1 @if ($errors->has('order')) has-error @endif  '>
          {{ Form::label('order', 'Order') }}
          {{ Form::text('order', null, ['placeholder' => '', 'class' => 'form-control']) }}
      </div>
        
      <div class='form-group col-sm-2 @if ($errors->has('title')) has-error @endif  '>
          {{ Form::label('title', 'Title') }}
          {{ Form::text('title', null, ['placeholder' => '', 'class' => 'form-control']) }}
      </div>
      
      <div class='form-group col-sm-1 @if ($errors->has('menu_name')) has-error @endif  '>
          {{ Form::label('menu_name', 'Menu Name') }}
          {{ Form::text('menu_name', null, ['placeholder' => '', 'class' => 'form-control']) }}
      </div>
      
      <div class='form-group col-sm-1 @if ($errors->has('menu_order')) has-error @endif  '>
          {{ Form::label('menu_order', 'Menu Order') }}
          {{ Form::text('menu_order', null, ['placeholder' => '', 'class' => 'form-control']) }}
      </div>
      
      <div class='form-group col-sm-2 @if ($errors->has('expanded_title')) has-error @endif  '>
          {{ Form::label('expanded_title', 'Expanded Title') }}
          {{ Form::text('expanded_title', null, ['placeholder' => '', 'class' => 'form-control']) }}
      </div>

      <div class='form-group col-sm-1 @if ($errors->has('slug')) has-error @endif  '>
        {{ Form::label('slug', 'Slug') }}
        {{ Form::text('slug', null, ['placeholder' => '', 'class' => 'form-control']) }}
      </div>  

      <div class='form-group col-sm-1 @if ($errors->has('meta_tags')) has-error @endif  '>
        {{ Form::label('meta_tags', 'Meta Tags') }}
        {{ Form::text('meta_tags', null, ['placeholder' => '', 'class' => 'form-control']) }}
      </div>  
    
      <div class='form-group col-sm-1'>
          {{ Form::submit($action, ['class' => 'btn btn-primary']) }}
      </div>
  
 
      {{ Form::close() }}
    </div>
    
  @if ($action != 'Create')
  
    <i>Created {{{ $series->created_at->format('m/d/Y h:ia') }}} by {{{ $series->created_by }}} | 
    Updated {{{ $series->updated_at->format('m/d/Y h:ia') }}} by {{{ $series->updated_by }}}</i>
   
    <hr/>
    
    <div id="related_languages" class='row'>
              
      <div class='form-group col-sm-4 ng-cloak'>
        
            <h2>Related Languages</h2>
          
            <basic-select :options="dropdown_options"
                          :selected-option="dropdown_selected"
                          placeholder="choose language"
                          @select="selectLanguage">
            </basic-select>
          
            <br/>
          
            <button v-on:click.prevent="addLanguage()" :disabled="dropdown_selected.value == ''" class="btn btn-xs btn-primary">Attach</button>
        
      </div>
        
      <div class='col-sm-4 ng-cloak'>
        
            <ul>

            <li v-for="language in languages">
            {# language['text'] #}
            &nbsp;<a v-on:click.prevent="removeLanguage(language)" href="#">remove</a>
            </li>

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