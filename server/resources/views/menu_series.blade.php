<ul class="side-nav">
    <li class="office"><label>Lessons</label></li>
    <?php $lesson_menu = \App\EieolSeries::where('published', '=', True)->get()->sortBy('menu_order'); ?>
    @foreach($lesson_menu as $lesson_menu_item)
        <li><a href="/eieol/{{$lesson_menu_item->slug}}" title="{!! $lesson_menu_item->expanded_title !!}">{{str_replace(' Online', '', $lesson_menu_item->menu_name)}}</a> </li>
    @endforeach
</ul>
