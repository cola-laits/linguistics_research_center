@extends('admin_layout')

@section('title') {{{$action}}} Language @stop

@section('content')

    <div class='col-lg-8 offset-2'>

    <h1><i class='fa fa-comment'></i> {{{$action}}} Language</h1>

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
        <form method="POST"
              action="/admin2/eieol_language"
              accept-charset="UTF-8" role="form">
    @else
        <form method="POST"
              action="/admin2/eieol_language/{{$language->id}}"
              accept-charset="UTF-8" role="form">
            <input name="_method" type="hidden" value="PUT">
    @endif
        {{csrf_field()}}

            <div class='form-group @if ($errors->has('language')) has-error @endif  '>
        <label for="language">Language</label>
        <input placeholder="Language" name="language" type="text" value="{{$language->language ?? ''}}" class="form-control">
    </div>

    <div class='form-group @if ($errors->has('lang_attribute')) has-error @endif  '>
        <label for="lang_attribute">Lang Attribute</label>
        <input placeholder="Lang Attribute" name="lang_attribute" type="text" value="{{$language->lang_attribute ?? ''}}" class="form-control">
        <div class="alert-warning">
            This will be added to all span tags in lessons that use this language - &lt;span lang='xxx' class='yyy'&gt;
        </div>
    </div>

    <div class='form-group @if ($errors->has('custom_keyboard_layout')) has-error @endif  '>
        <label for="custom_keyboard_layout">Custom Keyboard Layout</label>
        <textarea placeholder="Custom Keyboard Layout" name="custom_keyboard_layout" cols="150" rows="4" class="form-control">{{$language->custom_keyboard_layout ?? ''}}</textarea>
        <div class="alert-warning">
            This should be a list of characters (either in unicode code points or pasted in) <br/>
            Example: 'Â','Ä','Å','\u042f', '\u03da', '\u03db', '\u03c0'
        </div>
    </div>

    <div class='form-group @if ($errors->has('substitutions')) has-error @endif  '>
        <label for="substitutions">Substitutions</label>
        <textarea placeholder="Substitutions" name="substitutions" cols="150" rows="4" class="form-control">{{$language->substitutions ?? ''}}</textarea>
        <div class="alert-warning timesy">
            This is used for sorting.
            If there are characters that should be treated differently when sorting, enter them here.<br/>
            Separate substituions by commas.  Use x>y notation.<br/>
            Example: If you enter Ѽ>Отъ, the every occurrence of Ѽ will be replaced with Отъ before sorting.
            <br/>
            Do not use unicode code points, just paste in unicode characters.<br/>
        </div>
    </div>

    <div class='form-group @if ($errors->has('custom_sort')) has-error @endif  '>
        <label for="custom_sort">Custom Sort</label>
        @if ($action == 'Create')
            <textarea placeholder="Custom Sort" name="custom_sort" cols="150" rows="4" class="form-control">A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z,a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z</textarea>
        @else
            <textarea placeholder="Custom Sort" name="custom_sort" cols="150" rows="4" class="form-control">{{$language->custom_sort ?? ''}}</textarea>
        @endif
        <div class="alert-warning">
            This should be a comma separated list of characters in the order the Gloss and Dictionary should be sorter.<br/>
            Do not use unicode code points, just paste in unicode characters.<br/>
            Example: A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z,a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,π,q,r,s,t,u,v,w,x,y,z<br/>
            If two characters should be considered equal, place an equal sign between them.<br/>
            In the next example, "a" and "A" are considered the same, "b" and "B" are considered the same, etc.
            Also "p","P" and "π" are considered the same.
            Further, "L", "l" and "ll" are the same.  In other words, "ll" is treated as a single character.<br/>
            Example: a=A,b=B,c=C,d=D,e=E,f=F,g=G,h=H,i=I,J=j,K=k,L=l=ll,M=m,N=n,O=o,P=p=π,Q=q,R=r,S=s,T=t,U=u,V=v,W=w,X=x,Y=y,Z=z
            <hr/>

        </div>

        @if ($action != 'Create')
            <div class="alert-warning">
                To help you out, here is a list of all the characters used in surface forms and head words within lessons that use this language.<br/>
            </div>
            <div class="alert-info timesy">
                @foreach ($chars as $char)
                    {{$char}}
                @endforeach
            </div>
        @endif
    </div>

    <div class='form-group'>
        <input type="submit" value="Save" class="btn btn-primary">
    </div>

    </form>

</div>

@stop
