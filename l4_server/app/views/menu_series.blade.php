<ul class="side-nav">
	<li class="office"><label>Lessons</label></li>
	@foreach($lesson_menu as $lesson_menu_item)
        <li>{{ HTML::link('eieol/' . $lesson_menu_item->slug, str_replace(' Online', '', $lesson_menu_item->menu_name), array('title' => $lesson_menu_item->expanded_title )) }} </li>
    @endforeach
</ul>